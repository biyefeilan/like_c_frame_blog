<?php

(defined ( 'APP_PATH' )) || exit ( 'Access deny!' );

class _C_Log 
{
	private static $log_path = null;
	
	/**
	 * 写入日志
	 */
	public static function write($error_msg) 
	{
		if (self::$log_path !== null || (self::$log_path === null && self::_setLogPath ())) 
		{
			return file_put_contents ( self::$log_path . 'log-' . date ( 'd', time () ), $error_msg, FILE_APPEND );
		}
	}
	
	private static function _setLogPath()
	{
		self::$log_path = BASE_PATH . DS . 'logs' . DS . date ( 'Y-m', time () ) . DS;
		
		if (_C_File::mksdir(self::$log_path) && _C_File::is_writable ( self::$log_path ))
		{
			return true;
		}
		self::$log_path = null;
		return false;
	}
	
	private function __construct() {
	}
	
	private function __clone() {
	}
}

?>