<?php

final class _C_Model 
{
	
	public static function _init($class, $method, $data)
	{
		if (!class_exists($class, false))
		{
			_C_App::load($class, 'model');
		}
		
		//配置的_model_init返回true会继续执行$class的_init方法
		if (($result=_C_Config::_model_init($class, $method, $data))!==true)
		{
			return $result;
		}
		
		if (!class_exists($class, false))
		{
			return false;	
		} 
		
		if (in_array('_init', get_class_methods($class)))
		{
			return call_user_func_array(array($class, '_init'), array());
		}
		
		return true;
	}
	
	public static function _destroy($class, $method, $data_str)
	{
		_C_Config::_model_destroy($class, $method, $data_str);
		if (class_exists($class) && in_array('_destroy', get_class_methods($class)))
		{
			return call_user_func_array(array($class, '_destroy'), array());
		}
	}
	
	public static function getResult($class, $method, $data)
	{
		$error = false;
		
		if (!class_exists($class, false))
		{
			$error = true;
		}
		else if ($method[0]==='_')
		{
			$error = true;
		}
		else if (in_array ( '_remap', get_class_methods ( $class )))
		{
			$method = '_remap';
		}
		else if (!in_array ( $method, array_map ( 'strtolower', get_class_methods ( $class ) ) ))
		{
			$error = true;
		}
		
		return $error===false ? call_user_func_array ( array ($class, $method ), $data ) : false;
	}
}

?>