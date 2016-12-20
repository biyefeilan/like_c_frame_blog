<?php
final class Url {
	
	public static function mkurl($class, $method, $module=null, $data=null)
	{
		if (is_null($module))
			$module = _C_Router::getModule();
		if (is_null($data))
			$data = _C_Router::getData();
			
		$url = array();
		
		$mode = _C_Router::getMode();
		
		$config = _C_Config::vars('_reg');
		
		foreach (array($config['module']['uri']=>$module, $config['class']['uri']=>$class, $config['method']['uri']=>$method, $config['data']['uri']=>$data) as $k=>$v)
		{
			if (!empty($v))
				$url[] = $mode==0 ? $v : "{$k}={$v}";	
		}
			
		$glue = _C_Router::getMode() == 0 ? '/' : '&';
		
		$url = implode($glue, $url);
		
		return $mode==0 ? $url : '?'.$url;
	}
		
}

?>