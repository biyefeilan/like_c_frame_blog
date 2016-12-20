<?php

final class Curl 
{
	private static $_defaultOptions = array(
			CURLOPT_RETURNTRANSFER => true,         //获取的信息以文件流的形式返回，而不是直接输出
			CURLOPT_HEADER         => 0,        	//启用时会将头文件的信息作为数据流输出
			CURLOPT_ENCODING       => "",     		//请求头会发送所有支持的编码类型
			CURLOPT_AUTOREFERER    => true,  		//当根据Location:重定向时，自动设置header中的Referer:信息
			CURLOPT_CONNECTTIMEOUT => 0,         	//在发起连接前等待的时间，如果设置为0，则无限等待
			CURLOPT_TIMEOUT        => 0,         	//设置cURL允许执行的最长秒数
			CURLOPT_HTTPHEADER     => Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15")
	);
	
	public static function get($url, $options=array())
	{
		return self::doCurl($url, self::optionsMerge(self::$_defaultOptions, $options));
	}	
	
	public static function post($url, $data, $options=array())
	{
		$options = self::optionsMerge(self::$_defaultOptions, $options);
		$options = self::optionsMerge($options, array(CURLOPT_POST=>true));
		if (!empty($data))
		{
			$data = is_array($data) ? http_build_query($data) : $data;
			$options = self::optionsMerge($options, array(CURLOPT_POSTFIELDS=>$data));
		}
		return self::doCurl($url, $options);
	}
	
	private static function doCurl($url, $options)
	{
		$return = false;
		
		$handler = curl_init($url);
		
		if ($handler !== false)
		{
			curl_setopt_array($handler, $options);
			$contents = curl_exec($handler);
			$curlInfo = curl_getinfo($handler);
			if (!curl_error($handler) && $curlInfo['http_code'] === 200)
			{
				$return = $contents;
			}
		}
		
		curl_close($handler);
		
		return $return;
	}
	
	private static function optionsMerge($options, $new_opts)
	{
		if (is_array($new_opts) && !empty($new_opts))
		{
			foreach ($new_opts as $k=>$v)
			{
				$options[$k] = $v;
			}
		}
		return $options;
	}
	
}

?>