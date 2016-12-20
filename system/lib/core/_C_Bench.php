<?php

class _C_Bench {
	
	/**
	 * 存储相应测试记录的数据
	 * @var array
	 */
	private static $records = array ();
	
	public static function mark($id) {
		self::$records [$id] [] = microtime ( true );
	}
	
	public static function show($id) {
		echo '<p><b>', $id, ' use time:</b> ', self::get ( $id ) . '</p>';
	}
	
	public static function get($id, $mark = false) {
		$mark === true && self::mark ( $id );
		return isset ( self::$records [$id] ) ? self::$records [$id] [count ( self::$records [$id] ) - 1] - self::$records [$id] [0] : 0;
	}

}

?>