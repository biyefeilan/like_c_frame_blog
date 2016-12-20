<?php 
		
if (!defined('DS'))
{
	define ( 'DS', DIRECTORY_SEPARATOR );
}

if (!defined('APP_PATH'))
{
	define('APP_PATH', dirname ( __FILE__ ));
}

if (!defined('BASE_PATH'))
{
	define ( 'BASE_PATH', dirname ( APP_PATH ) );
}

if (!defined('SYSTEM_PATH'))
{
	define ( 'SYSTEM_PATH', BASE_PATH . DS . 'system' );
}

if (!defined('PHP_SUFFIX'))
{
	//php文件后缀
	define ( 'PHP_SUFFIX', '.php' );
}

if (0)
{
	$core_cache = BASE_PATH .DS . 'cache' . DS . 'core' .PHP_SUFFIX;

	if (file_exists($core_cache))
	{
		include $core_cache;	
	}
	else
	{
		include SYSTEM_PATH . DS . 'lib' . DS . 'core' . DS . '_C_App' . PHP_SUFFIX;
		include BASE_PATH . DS . '_C_Builder' . PHP_SUFFIX;
		
		$cache_files = array(
			SYSTEM_PATH . DS . 'lib' . DS . 'database' . DS . 'drivers' . DS . 'mysql' .  DS . 'Mysql'.PHP_SUFFIX,
			APP_PATH .DS.'home'.DS.'conf',
			APP_PATH .DS.'home'.DS.'model',
		);
 		_C_Builder::buildCoreCache($core_cache, $cache_files);
	}
}
else
{
	include SYSTEM_PATH . DS . 'lib' . DS . 'core' . DS . '_C_App' . PHP_SUFFIX;
}

_C_App::_init();

_C_App::_run();

_C_App::_shutdown();

?>