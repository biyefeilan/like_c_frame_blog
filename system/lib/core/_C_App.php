<?php

(defined ( 'APP_PATH' )) || exit ( 'Access deny!' );
/**
 * _C_App
 * @author Jone
 *
 */
final class _C_App {
	
	/**
	 * 包的类型
	 * @var array
	 */
	private static $_TYPES = array (
		'cache' 	=> 'T_CACHE', 
		'class'  	=> 'T_CLASS',
		'template'  => 'T_TEMPLATE'
	);
	
	/**
	 * 根据key返回要缓存的包中文件的类型
	 * @param string $key
	 * @return string
	 * @access public
	 */
	public static function types($key) {
		return isset ( self::$_TYPES [$key] ) ? self::$_TYPES [$key] : _C_App::triggerError ( 'No such key ' . $key . ' in TYPES' );
	}
	
	/**
	 * 系统的包
	 * @var array
	 */
	private static $_packages;
	
	private static function _initPackages() 
	{
		if (!isset(self::$_packages))
		{
			self::$_packages = array (
			
				'core' => array (
					'path' => SYSTEM_PATH . DS . 'lib' . DS . 'core' . DS, 
					'type' => _C_App::types ( 'class' )
				), 
					
				'mysql' => array (
					'path' => SYSTEM_PATH . DS . 'lib' . DS . 'database' . DS . 'drivers' . DS . 'mysql' . DS,
					'type' => _C_App::types ( 'class' )
				),
					
				'util'	=> array (
					'path' => SYSTEM_PATH . DS . 'lib' . DS . 'util' . DS, 
					'type' => _C_App::types ( 'class' )
				),
			
				'template' => array (
					'path' => APP_PATH . DS . 'template' . DS, 
					'type' => _C_App::types( 'template' )
				), 
			
				'model' => array (
					'path' => APP_PATH . DS . 'model' . DS,
					'type' => _C_App::types ( 'class' )
				), 
				
				'conf'   => array (
					'path' => APP_PATH . DS . 'conf' . DS,
					'type' => _C_App::types ( 'class' )
				),
				
				'lang' => array(
					'path' => APP_PATH . DS . 'lang' . DS,
					'type' => _C_App::types('class'),
				),
				
				'layout' => array(
					'path' => APP_PATH . DS . 'layout' . DS,
					'type' => _C_App::types('template'),
				),
				
				'lib'	=> array (
					'path' => APP_PATH . DS . 'lib' . DS,
					'type' => _C_App::types ( 'class' )
				),
			);
		}
	}
	
	/**
	 * 返回包中文件的路径
	 * @param string $filename
	 * @param string $package
	 * @return string
	 */
	public static function filePath($filename, $package) 
	{
		return self::$_packages [$package] ['path'] . $filename . PHP_SUFFIX;
	}
	
	public static function getPackageType($package) {
		return isset ( self::$_packages [$package] ['type'] ) ? self::$_packages [$package] ['type'] : false;
	}
	
	/**
	 * 根据包的类型返回包
	 * @param string $type
	 * @return array
	 */
	public static function getPackagesByType($type) 
	{
		$packages = array ();
		foreach ( self::$_packages as $name => $package ) 
		{
			if (isset ( $package ['type'] ) && isset ( self::$_TYPES [$type] ) && $package ['type'] == self::$_TYPES [$type])
				$packages [] = $name;
		}
		return $packages;
	}
	
	/**
	 * 返回包的路径
	 * @param string $package
	 * @return string
	 */
	public static function getPackagePath($package) 
	{
		return isset ( self::$_packages [$package] ) && isset ( self::$_packages [$package] ['path'] ) ? self::$_packages [$package] ['path'] : null;
	}
	
	/**
	 * 返回包的所有文件
	 * @param string $package
	 * @param string $pattern
	 * @return array
	 */
	public static function getPackageFiles($package, $pattern = '*.php') 
	{
		$files = glob ( self::getPackagePath ( $package ) . $pattern );
		return $files === false ? array () : $files;
	}
	
	/**
	 * 动态添加包
	 * @param array $packages
	 */
	public static function addPackages($packages) 
	{
		foreach ( $packages as $package => $val ) 
		{
			_C_App::assert(! isset ( self::$_packages [$package] ), 'Package ' .$package.' already exists');
			self::$_packages [$package] = $val;
		}
	}
	
	//----------------------------------------------------------------------------------------
	
	private static $_loaded = array();
	
	/**
	 * spl_autoload_register注册函数
	 * @access private
	 */
	private static function _autoLoad($class) 
	{
		foreach ( self::$_packages as $package => $val ) 
		{
			if (isset ( $val ['type'] ) && $val ['type'] == self::$_TYPES ['class']) 
			{
				self::load ( $class, $package );
				if (class_exists($class, false)) 
				{
					break;
				}
			}
		}
	}
	
	/**
	 * 加载文件,如果使用缓存并已经加载过此文件直接返回值,加载失败返回false
	 * @param string $filename 加载文件名 
	 * @param string $package  包名	eg. core 调用 packages($id);获取
	 * @return mixed
	 * @access public
	 */
	public static function load($filename, $package)
 	{
		if (file_exists ( $file = self::filePath ( $filename, $package ) )) 
		{
			self::$_loaded[$package][$filename] = include $file;
			return self::$_loaded[$package][$filename];
		} 
		else 
		{
			return false;
		}
	}
	
	public static function getLoaded()
	{
		return self::$_loaded;
	}
	
	//----------------------------------------------------------------------------------------
	
	/**
	*@param array $modules 注册的模块列表
	*@param int $mode 如何获取uri，path_info==0 else query strings 
	*/
	public static function _init($modules = array(), $mode=1)
	{
		self::_initPackages ();
		
		spl_autoload_register ( array ('_C_App', '_autoLoad' ) );
		
		_C_Bench::mark('app');
		
		$uri = _C_Router::_getUri($mode);
		
		//如果设置了模块，那么uri中的第一个必须是模块名
		if (!empty($modules) && is_array($modules))
		{
			$segs = explode('/', trim($uri, '/'));	
			if (!in_array($segs[0], $modules))
			{
				exit('Bad URL.');	
			}
			_C_Router::_setModule($segs[0]);
			//设定包
			$package_format = APP_PATH . DS . $segs[0] . DS . '%s' . DS;
			self::$_packages['template']['path'] = sprintf($package_format, 'template');
			self::$_packages['model']['path'] = sprintf($package_format, 'model');
			self::$_packages['conf']['path'] = sprintf($package_format, 'conf');
			self::$_packages['lib']['path'] = sprintf($package_format, 'lib');
			self::$_packages['layout']['path'] = sprintf($package_format, 'layout');
			self::$_packages['lang']['path'] = sprintf($package_format, 'lang');
			
			unset($segs[0]);
			//连起来，如果没模块，可以直接把项目放到app下，形成统一
			$uri = implode('/', $segs);
		}
		
		_C_Config::_resultDef();
		if (!class_exists('Config', false))
		{ 
			_C_App::load('Config', 'conf');
		}
		//此前不能使用_C_Config或Config的vars()方法调用模块的配置，因为Mod未确定
		_C_Router::_setRouting($uri);
		
		$config = _C_Config::vars('_C_App');
		
		if (!empty($config['lang']))
		{
			self::$_packages['lang']['path'] .= $config['lang'] . DS;
		}
		
		error_reporting ( isset($config['error_reporting_level']) ? E_ALL : $config['error_reporting_level'] );
		
		self::assert ( is_bool ( $config['debug'] ) && is_bool ( $config['error_log'] ), 'app setting error.' );
		
		if ($config['debug'] === true) 
		{
			ini_set ( 'display_errors', 'on' );
		} 
		else 
		{
			ini_set ( 'display_errors', 'off' );
			$config['error_log'] === true ? set_error_handler ( array ('_C_App', '_errorHandler' ) ) : error_reporting ( 0 );
		}
		
		//时区设定
		isset($config['time_zone']) && date_default_timezone_set ( $config['time_zone'] );
		
		//设置最大脚本执行时间
		if (! ini_get ( 'safe_mode' ) && is_numeric ( $config['app_max_time'] )) 
		{
			set_time_limit ( $config['app_max_time'] );
		}
		
		if (ini_get ( 'magic_quotes_gpc' ) === '1') 
		{
			isset ( $_REQUEST ) && _C_Util::stripslashes ( $_REQUEST );
			isset ( $_POST ) && _C_Util::stripslashes ( $_POST );
			isset ( $_GET ) && _C_Util::stripslashes ( $_GET );
			isset ( $_COOKIE ) && _C_Util::stripslashes ( $_COOKIE );
		}
		
		if ($config['auto_trim_gpc'] === true) {
			isset ( $_REQUEST ) && _C_Util::trim ( $_REQUEST );
			isset ( $_POST ) && _C_Util::trim ( $_POST );
			isset ( $_GET ) && _C_Util::trim ( $_GET );
			isset ( $_COOKIE ) && _C_Util::trim ( $_COOKIE );
		}
		
		if (!defined('BASE_URL') && isset($config['base_url']))
		{
			define ( 'BASE_URL', $config['base_url'] );
		}
		
		if (!defined('IMG_URL') && isset($config['img_url']))
		{
			define('IMG_URL', $config['img_url']);
		}
		
		if (!defined('JS_URL') && isset($config['js_url']))
		{
			define('JS_URL', $config['js_url']);
		}
		
		if (!defined('CSS_URL') && isset($config['css_url']))
		{
			define('CSS_URL', $config['css_url']);
		}
		
	}
	
	/**
	 * run()
	 */
	public static function _run() 
	{	
		if (($result = _C_Controller::_init()) === true)
		{
			$result = _C_Controller::_getResult(_C_Router::getClass(), _C_Router::getMethod(), _C_Router::getData());
		}
		
		$result = _C_Controller::processResult($result);

		_C_Controller::showResult($result);
		
		_C_Controller::_destroy();
	}
	
	public static function _shutdown()
	{
		
	}
	
	/**
	 * 错误处理函数
	 * @access public
	 */
	private static function _errorHandler($errno, $errstr, $errfile, $errline) {
		if (! (error_reporting () & $errno)) {
			return true;
		}
		
		$error_msg = '[' . date ( 'Y-m-d H:i:s', time () ) . '] ';
		
		switch ($errno) {
			case E_USER_ERROR :
				$error_msg .= "ERROR: [$errno] $errstr, ";
				$error_msg .= "fatal error on line $errline in file $errfile";
				$error_msg .= ', PHP ' . PHP_VERSION . ' (' . PHP_OS . '), client IP: [' . _C_Router::getClientIp () . '], ';
				$error_msg .= "aborting...\r\n";
				_C_Log::write ( $error_msg );
				exit ( 1 );
				break;
			
			case E_USER_WARNING :
				$error_msg .= "WARNING: [$errno] $errstr\r\n";
				break;
			
			case E_USER_NOTICE :
				$error_msg .= "NOTICE: [$errno] $errstr\r\n";
				break;
			
			default :
				$error_msg .= "Unknown error type: [$errno] $errstr\r\n";
				break;
		}
		_C_Log::write ( $error_msg );
		return true;
	}
	
	/**
	 * 如果表达式为假，触发E_USER_ERROR级别错误
	 * @param bool $expression
	 * @param string $error_msg
	 * @access public 
	 */
	public static function assert($expression, $error_msg = 'An error occured!') {
		! $expression && _C_App::triggerError ( $error_msg, E_USER_ERROR );
	}
	
	/**
	 * 触发错误
	 * @param string $error_msg
	 * @param int $error_type
	 */
	public static function triggerError($error_msg, $error_type = E_USER_ERROR) {
		trigger_error ( $error_msg, $error_type );
	}
	
}
?>