<?php

final class DB extends _C_Db {
	
	private static $_table;
	
	/**
	 * 连接名
	 */
	private static $_connection;
	
	public static function init($connection = 'default')
	{
		if (isset(self::$_connection) && is_resource(self::$_connections[self::$_connection]['link']))
		{
			return true;	
		}
		if (self::connect($connection))
		{
			self::$_connection = $connection;	
			return true;
		}
		return false;	
	}
	
	public static function count($table, $where=null) 
	{
		return self::doCall (self::$_connection, $table, 'count', array($where) );
	}
	
	public static function desc($table) 
	{
		return self::doCall (self::$_connection, $table, 'desc' );
	}
	
	public static function insert($table, $data) 
	{
		return self::doCall (self::$_connection, $table, 'insert', array ($data ) , true);
	}
	
	public static function update($table, $data, $where) 
	{
		return self::doCall (self::$_connection, $table, 'update', array ($data, $where ) , true);
	}
	
	public static function delete($table, $where=null, $limit=null) 
	{
		return self::doCall (self::$_connection, $table, 'delete', array ($where, $limit ) , true);
	}
	
	public static function findAll($table, $where, $order, $data, $limit=null) 
	{
		return self::doCall (self::$_connection, $table, 'select', array ($data, $where, $order, $limit ) , true);
	}
	
	public static function findOne($table, $where=null, $order=null, $data='*') 
	{
		$data = self::doCall (self::$_connection, $table, 'select', array ($data, $where, $order, '1' ), true);
		if (! empty ( $data ))
			return $data [0];
		return false;
	}
	
	public static function exists($table, $where, $check=false) {
		$result = self::doCall (self::$_connection, $table, 'select', array ('*', $where, null, '1' ), $check );
		return empty ( $result ) ? false : true;
	}
	
	public static function pages($table, $where, $order, $data, $page, $page_size=10)
	{
		//当前页
		$page = max((int)$page, 1);
		//总记录数
		$records_count = DB::count($table, $where);
		//页数
		$pages_count = ceil($records_count / $page_size);
		
		$limit = ($page_size * ($page-1)) . ',' . $page_size;
		
		$infos = array();
		
		if ($records_count > 0)
		{
			$infos = self::doCall (self::$_connection, $table, 'select', array ($data, $where, $order, $limit) , true);;	
		}
		
		return array(
			'data' => $infos,
			'info' => array(
				'page_now' => $page,
				'page_size' => $page_size,
				'records_count' => $records_count,
				'pages_count' => $pages_count,	
			),
		);
	}	
	
	public static function destroy(){}
	
	
	const ARTICLE = 'articles';
	
	const USER = 'authors';
	
	const ADMIN = 'admins';
	
	const ARTCHECK = 'articles_check';
	
	const TAG = 'tags';

	const ARTTAG = 'articles_tags';

	const COMMENT = 'comments';
	
	const DEPARTMENT = 'departments';
	
	const LOGINLOG = 'login_logs';
	
	const CATEGORY = 'categories';
	
	const POSITION = 'positions';
	
	const ARTPOS = 'articles_positions';
	
	const DICT = 'dicts';
	
	const PAG = 'pages';
	
	const ATTACH = 'attachments';
}

?>