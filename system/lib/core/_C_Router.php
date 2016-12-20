<?php

(defined ( 'APP_PATH' )) || exit ( 'Access deny!' );

/**
 * 根据Uri所提供的uri对比路由表得出真正的uri
 * (REQUEST)
 * @author Jone
 *
 */
final class _C_Router 
{
	private static $_reg_config;
	
	private static function _regConfig()
	{
		return (self::$_reg_config = isset(self::$_reg_config) ? self::$_reg_config : _C_Config::vars('_reg'));
	}
	
	private static $_mode; 
	
	public static function getMode()
	{
		return self::$_mode;	
	}
	
	private static $_uri;
	
	/**
	*@param int $mode 0将使用path info 等获取uri信息，非0将使用query string
	*/
	public static function _getUri($mode)
	{
		self::$_mode = $mode;
		
		if (isset(self::$_uri))
		{
			return self::$_uri;	
		}
		
		if ((php_sapi_name () == 'cli' || defined ( 'STDIN' ))) 
		{
			$argv = $_SERVER ['argv'];
			return self::$_uri = strpos($argv [0], '/')===0 ? $argv [0] : '/'.$argv [0];
		}
		
		if (self::$_mode == 0)
		{ 
			if (isset($_SERVER ['PATH_INFO']) && !empty ( $_SERVER ['PATH_INFO'] )) 
			{
				return self::$_uri = $_SERVER ['PATH_INFO'];
			} 
			else if (isset ( $_SERVER ['REQUEST_URI'] ) && isset ( $_SERVER ['SCRIPT_NAME'] )) 
			{
				if (strpos ( $_SERVER ['REQUEST_URI'], $_SERVER ['SCRIPT_NAME'] ) === 0) 
				{
					$uri = substr ( $_SERVER ['REQUEST_URI'], strlen ( $_SERVER ['SCRIPT_NAME'] ) );
				} 
				else if (strpos ( $_SERVER ['REQUEST_URI'], dirname ( $_SERVER ['SCRIPT_NAME'] ) ) === 0) 
				{
					$uri = substr ( $_SERVER ['REQUEST_URI'], strlen ( dirname ( $_SERVER ['SCRIPT_NAME'] ) ) );
				}
			} 
			
			if (!isset($uri))
			{
				if (isset ( $_SERVER ['PHP_SELF'] ) && isset ( $_SERVER ['SCRIPT_NAME'] )) 
				{
					$uri = str_replace ( $_SERVER ['SCRIPT_NAME'], '', $_SERVER ['PHP_SELF'] );
				} 
				else if (isset ( $_SERVER ['QUERY_STRING'] ) && trim ( $_SERVER ['QUERY_STRING'], '/' ) != '') 
				{
					$uri = $_SERVER ['QUERY_STRING'];
				}
			}
			
			if (isset($uri) && strpos ( $uri, '?' ) !== false) 
			{
				list ( $uri ) = explode ( '?', $uri, 2 );
			}
		}
		else
		{
			$reg = self::_regConfig();
			
			$uri  = '/' . (isset($_GET[$reg['module']['uri']]) ? $_GET[$reg['module']['uri']] : '');
			$uri .= isset($_GET[$reg['class']['uri']]) ? $_GET[$reg['class']['uri']] . '/' : '';
			$uri .= isset($_GET[$reg['method']['uri']]) ? $_GET[$reg['method']['uri']] .'/' : '';
			$uri .= isset($_GET[$reg['data']['uri']]) ? $_GET[$reg['data']['uri']] . '/' : '';
			
		}
		
		return self::$_uri = empty ( $uri ) ? '/' : preg_replace ('#//+#', '/', $uri );	
	}
	
//--------------------------------------------------------------------

	private static $_module;
	
	private static $_class;
	
	private static $_method;
	
	private static $_data;
	
	public static function _setModule($module)
	{
		self::$_module = $module;	
	}
	
	public static function getModule()
	{
		return self::$_module;	
	}
	
	public static function getClass()
	{
		return self::$_class;	
	}
	
	public static function getMethod()
	{
		return self::$_method;	
	}
	
	public static function getData()
	{
		return self::$_data;	
	}
	
//----------------------------------------------------------------
	
	public static function _setRouting($uri)
	{
		$segs = explode('/', trim(self::_parse($uri), '/'), 3);
		
		self::$_class = isset($segs[0]) ? $segs[0] : 'index';
		
		self::$_method = isset($segs[1]) ? $segs[1] : 'index';
		
		self::$_data = isset($segs[2]) ? $segs[2] : '';
		
		$reg = self::_regConfig();
		
		if (isset($reg['module']['global']) && isset(self::$_module))
			define($reg['module']['global'], self::$_module);
		
		if (isset($reg['class']['global']))
			define($reg['class']['global'], self::$_class);
			
		if (isset($reg['method']['global']))
			define($reg['method']['global'], self::$_method);
			
		if (isset($reg['data']['global']))
			define($reg['data']['global'], self::$_data);
	}
	
	/**
	 * 根据路由的配置分析实际要访问的uri
	 */
	private static function _parse($uri) 
	{	
		$uri = empty($uri) ? '/' : $uri;
		
		$config = _C_Config::vars('_C_Router');
		
		if (is_array($config))
		{
			if (isset ( $config[$uri] ))
			{
				$uri = $config[$uri];
			}	
			else 
			{
				foreach ( $config as $pattern => $replacement ) 
				{
					if (preg_match ( '#^' . $pattern . '$#', $uri )) 
					{
						if (strpos ( $replacement, '$' ) !== false && strpos ( $pattern, '(' ) !== false) 
						{
							$replacement = preg_replace ( '#^' . $pattern . '$#', $replacement, $uri );
						}
		
						$uri = $replacement;
						break;
					}
				}
			}
		}
		return $uri;
	}
	
	/**
	 * 获取客户端ip
	 */
	private static $_client_ip;
	
	public static function getClientIp() 
	{
		return isset ( self::$_client_ip ) ? self::$_client_ip : self::_clientIp ();
	}
	
	private static function _clientIp() 
	{
		$client_ip = null;
		if ((isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) && ($ips = $_SERVER ['HTTP_X_FORWARDED_FOR'])) || ($ips = getenv ( 'HTTP_X_FORWARDED_FOR' ))) 
		{
			$client_ip = preg_replace ( '/(?:,.*)/', '', $ips );
		}
		if (! $client_ip || stristr ( $client_ip, 'unknown' ) !== false) 
		{
			$client_ip = isset ( $_SERVER ['HTTP_CLIENT_IP'] ) ? $_SERVER ['HTTP_CLIENT_IP'] : getenv ( 'HTTP_CLIENT_IP' );
		}
		if (! $client_ip || stristr ( $client_ip, 'unknown' ) !== false) 
		{
			$client_ip = isset ( $_SERVER ['REMOTE_ADDR'] ) ? $_SERVER ['REMOTE_ADDR'] : getenv ( 'REMOTE_ADDR' );
		}
		return self::$_client_ip = trim ( $client_ip );
	}
	
	private function __construct() {}
	
	private function __clone() {}
}

?>