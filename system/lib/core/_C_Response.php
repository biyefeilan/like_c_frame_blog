<?php

(defined ( 'APP_PATH' )) || exit ( 'Access deny!' );

class _C_Response {
	private static $status_code = 200;
	
	private static $protocol = 'HTTP/1.1';
	
	private static $content_type = 'text/html';
	
	private static $headers = array ();
	
	/**
	 * 最终输出的内容
	 * @var string
	 */
	private static $content = '';
	
	/**
	 * 记录程序开始时的缓存等级
	 * @var int
	 */
	private static $ob_init_level;
	
	private static $charset = 'UTF-8';
	
	private static $_config = array();
	
	/**
	 * 标记此类是否已经准备好
	 * @var bool
	 */
	private static $ready = false;
	
	public static function obInitLevel() {
		return self::$ob_init_level;
	}
	
	public static function setStatusCode($code) {
		_C_App::assert ( self::$_config['status_codes'][$code] !== null, 'Unknown status code');
		self::$status_code = $code;
	}
	
	public static function setContent($content) {
		self::$content = $content;
	}
	
	public static function appendContent($content) {
		self::$content .= $content;
	}
	
	public static function setCharset($chartset) {
		self::$charset = $chartset;
	}
	
	/**
	 * 初始化
	 */
	public static function ready() 
	{
		if (self::$ready === false) {
			self::$_config = _C_Config::vars('_C_Response');
			if (self::$_config['charset'] !== null)
				self::$charset = self::$_config['charset'];
			
			self::$ob_init_level = ob_get_level ();
			
			ob_start ();
			
			self::$ready = true;
		}
	}
	
	public static function compressContent() {
		if (! in_array ( 'ob_gzhandler', ob_list_handlers () )) {
			return ini_get ( 'zlib.output_compression' ) !== '1' && extension_loaded ( 'zlib' ) && isset ( $_SERVER ['HTTP_ACCEPT_ENCODING'] ) && strpos ( $_SERVER ['HTTP_ACCEPT_ENCODING'], 'gzip' ) !== false && ob_start ( 'ob_gzhandler' );
		}
		return true;
	}
	
	/**
	 * #header('Location: www.yoursite.com');
	 * #header(array('Location'=>' www.yoursite.com'));
	 * #header(array( array('Content-type: application/pdf', 'X-Extra' => 'My header', array('Content-type', 'application/pdf', false)) ));
	 * @param mixed $header string or array
	 * @param bool $replace
	 * @access public
	 */
	public static function addHeader($header, $replace = true) {
		if (is_string ( $header )) {
			self::$headers [] = array ($header, $replace );
			return;
		}
		if (is_array ( $header )) {
			foreach ( $header as $k => $v ) {
				if (is_numeric ( $k )) {
					if (is_string ( $v )) {
						self::$headers [] = array ($v, $replace );
						continue;
					} else {
						self::$headers [] = array ("{$v[0]}: {$v[1]}", isset ( $v [2] ) ? $v [2] : $replace );
					}
				} else {
					self::$headers [] = array ("{$k}: {$v}", $replace );
				}
			}
		}
	}
	
	public static function headerContentLength() {
		$compressed = isset ( $_SERVER ['HTTP_ACCEPT_ENCODING'] ) && strpos ( $_SERVER ['HTTP_ACCEPT_ENCODING'], 'gzip' ) !== false && (ini_get ( 'zlib.output_compression' ) === '1' || in_array ( 'ob_gzhandler', ob_list_handlers () ));
		if (! in_array ( self::$status_code, range ( 301, 307 ) ) && ! $compressed) {
			if (ini_get ( 'mbstring.func_overload' ) & 2 && function_exists ( 'mb_strlen' )) {
				$len = mb_strlen ( self::$content, '8bit' );
			} else {
				$len = strlen ( self::$content );
			}
			self::addHeader ( array ('Content-Length' => $len ) );
		}
	}
	
	public static function headerStatusCode() {
		$code_message = self::$_config['status_codes'][self::$status_code];
		if (substr ( php_sapi_name (), 0, 3 ) == 'cgi') {
			header ( 'Status: ' . self::$status_code . ' ' . $code_message );
		} else {
			header ( self::$protocol . ' ' . self::$status_code . ' ' . $code_message, true, self::$status_code );
		}
	}
	
	public static function headerNoCache() {
		self::addHeader ( array ('Expires' => 'Fri, 12 Jul 1987 06:00:00 GMT', 'Last-Modified' => gmdate ( 'D, d M Y H:i:s' ) . " GMT", 'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0', 'Pragma' => 'no-cache' ) );
	}
	
	public static function cache($since, $time = '+1 day') {
		if (! is_integer ( $time )) {
			$time = strtotime ( $time );
		}
		self::addHeader ( array ('Date' => gmdate ( 'D, j M Y G:i:s ', time () ) . 'GMT', 'Last-Modified' => gmdate ( 'D, j M Y G:i:s ', $since ) . 'GMT', 'Expires' => gmdate ( 'D, j M Y H:i:s', $time ) . " GMT", 'Cache-Control' => 'public, max-age=' . ($time - time ()), 'Pragma' => 'cache' ) );
	}
	
	public static function download($filename) {
		self::addHeader ( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	}
	
	/**
	 * 输出
	 */
	public static function output() {
		_C_App::assert ( self::$ready, 'Response does not be inited' );
		//获取缓存区内容
		if (ob_get_level () > self::obInitLevel ()) {
			$content = '';
			$ob_contents = array ();
			while ( ob_get_level () > self::obInitLevel () ) {
				$ob_contents [] = ob_get_contents ();
				@ob_end_clean ();
			}
			if (($max = (count ( $ob_contents )) - 1) >= 0) {
				for($i = $max; $i >= 0; -- $i)
					$content .= $ob_contents [$i];
			}
			self::$content = $content . self::$content;
		}
		
		self::$_config['compress_content'] && self::compressContent ();
		
		self::headerStatusCode ();
		
		self::addHeader ( array ('Content-Type' => self::$content_type . '; charset=' . self::$charset ) );
		
		self::headerContentLength ();
		
		foreach ( self::$headers as $header ) {
			header ( $header [0], $header [1] );
		}
		
		echo self::$content;
	}
	
	private function __construct() {
	}
	
	private function __clone() {
	}
}

?>