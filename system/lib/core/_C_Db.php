<?php

class _C_Db {
	
	/**
	 * 连接
	 * array('connection_name'=>array('link'=>'resource', 'driver'=>'driver_name'));
	 * @var array
	 */
	protected static $_connections;
	
	/**
	 * 连接数据库，完成对数据库的连接和各种初始化工作
	 * @param string $connection
	 * @return bool
	 */
	public static function connect($connection) {
		
		$self_config = _C_Config::vars('_C_Db');
		
		//没有配置
		if (!isset($self_config[$connection]))
			return false;
		
		$config = $self_config[$connection];
		
		self::$_connections [$connection] ['driver'] = isset ( $config ['driver'] ) ? $config ['driver'] : 'Mysql';
		
		//数据库驱动不存在
		if (!class_exists ( self::$_connections [$connection] ['driver'] ))
			return false;
		
		self::$_connections [$connection] ['link'] = call_user_func_array ( array (self::$_connections [$connection] ['driver'], 'init' ), array ('config' => $config ) );
		
		return is_resource(self::$_connections [$connection] ['link']);
	}
	
	/**
	 * 
	 * @param string $table
	 * @param string $connection
	 * @param array $data
	 */
	protected static function filterFields($connection, $table, $data) {
		$table_info = self::doCall ($connection, $table, 'desc' );
		$fields = array();
		foreach ( $table_info as $info ) 
		{
			$fields[] = $info ['Field'];
		}
		if (count($fields)>0) 
		{
			$new_data = array ();
			$fields = array_map ( 'strtolower', $fields );
			foreach ( $data as $key => $value ) {
				if (in_array ( strtolower ( $key ), $fields )) {
					$new_data [$key] = $value;
				}
			}
			return $new_data;
		}
		return $data;
	}
	
	protected static function doCall($connection, $table, $func, $param_arr = array(), $check=false) 
	{
		if ($check === true) {
			switch ($func) {
				case 'insert' :
					if (is_array ( $param_arr [0] )) {
						$param_arr [0] = self::filterFields ( $connection, $table, $param_arr [0] );
					}
					break;
				
				case 'update' :
					if (is_array ( $param_arr [0] )) {
						$param_arr [0] = self::filterFields ( $connection, $table, $param_arr [0] );
					}
					if (is_array ( $param_arr [1] )) {
						$param_arr [1] = self::filterFields ( $connection, $table, $param_arr [1] );
					}
					break;
				
				case 'select' :
					//对where进行校验，不存在的字段过滤掉
					if (is_array ( $param_arr [1] )) {
						$param_arr [1] = self::filterFields ( $connection, $table, $param_arr [1] );
					}
					break;
			}
		}
		$param_arr = array_merge ( array (0 => $table, 1 => self::$_connections [$connection] ['link'] ), $param_arr );
		return call_user_func_array ( array (self::$_connections [$connection] ['driver'], $func ), $param_arr );
	}
}

?>