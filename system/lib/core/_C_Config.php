<?php (defined ( 'APP_PATH' )) || exit ( 'Access deny!' );

final class _C_Config 
{
	//在模块未确定之前，调用此类都将返回此类中的配置
	private static $_vars = array(
		'_reg' => array(
			'module'	=> array(
				'global' => '_M_',//url解析后会定义一个全局的常量，常量名将会设为这个
				'uri'	 => 'm',  //用QueryStrings获取时会找这个比如$_GET['m'] = 'home'
			),
			'class' 	=> array(
				'global' => '_C_',
				'uri'	 => 'c',
			),
			'method'	=> array(
				'global' => '_A_',
				'uri'	 => 'a',
			),
			'data'		=> array(
				'global' => '_D_',
				'uri'	 => 'd',
			),
		),
	);
	
	/**
	*对于controler_init每个uri访问时就已定，所以没必要传uri参数
	*/
	public static function _controller_init()
	{
		if (class_exists('Config', false) && in_array('_controller_init', get_class_methods('Config')))
		{
			return Config::_controller_init();
		}
		return true;
	}
	
	public static function _controller_destory()
	{
		if (class_exists('Config', false) && in_array('_controller_destory', get_class_methods('Config')))
		{
			return Config::_controller_destory();
		}
		return true;
	}
	
	/**
	*对于model_init，由于direct结果会重新调用类方法，所以得传参数
	*@param string $class 调用的类
	*@param string $method 调用类的方法
	*@param string $data 解析uri类及方法以后剩余的字符串，做数据及方法参数用
	*@return mix 如果返回为true， 则会继续执行类的方法；如果返回为结果，则会直接解析结果，不调用类方法。
	*/
	public static function _model_init($class, $method, $data)
	{
		if (class_exists('Config', false) && in_array('_model_init', get_class_methods('Config')))
		{
			return Config::_model_init($class, $method, $data);
		}
		return true;
	}
	
	public static function _model_destroy($class, $method, $data)
	{
		if (class_exists('Config', false) && in_array('_model_destroy', get_class_methods('Config')))
		{
			return Config::_model_destroy($class, $method, $data);
		}
		return true;
	}
	
	public static function _resultDef()
	{
		define('DATA', 		'_C_data');
		define('SUCCESS',   '_C_success');
		define('ERROR', 	'_C_error');
		define('SHOW', 		'_C_show');
		define('SHOW404', 	'_C_show404');
		define('DIRECT', 	'_C_direct');
		define('JUMP', 		'_C_jump');
	}
	
	public static function vars($class)
	{
		//用户自定义的Config是否存在，其是否对$class做了定义
		if (class_exists('Config', false) && ($tmp = Config::vars($class)) !== NULL )
		{
			return self::$_vars[$class] = $tmp;
		}
		
		return isset(self::$_vars[$class]) ? self::$_vars[$class] : NULL;
	}
}
?>