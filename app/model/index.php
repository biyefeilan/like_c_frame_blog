<?php
class Index {
	
	public static function index() 
	{
		return array (
				SHOW,
				array ('elapsed_time' => _C_Bench::get ( 'app', true ) ) 
		);
	}
	
	public static function _init()
	{
		return true;
	}
	
	public static function _destroy()
	{
		return true;
	}
	
	private function __construct(){}
}

?>