<?php

final class _C_Controller 
{	
	
	public static function _init()
	{
		return _C_Config::_controller_init();
	}
	
	public static function _destroy()
	{
		return _C_Config::_controller_destory();
	}
	
	/**
	*运行模型方法，返回结果
	*
	*/
	public static function _getResult($class, $method, $data_str)
	{	
		/*
		//对于data的处理，如果是偶数，则按照k=>v的方式
		$data = array ();
		$segs = explode ( '/', $data_str );
		if (count ( $segs ) > 0 && count ( $segs ) % 2 == 0)
		{
			for($i = 0; $i != count ( $segs ); $i += 2)
			{
				$data [$segs [$i]] = $segs [$i + 1];
			}
			//作为数据传给方法
			$data = array($data);
		}
		else
		{
			$data = $segs;
		}
		*/
		$data = explode ( '/', $data_str ); 
		
		$return  = SHOW404;
		
		$model_init = _C_Model::_init($class, $method, $data_str);
		
		if ($model_init===true)
		{
			if (($result = _C_Model::getResult($class, $method, $data)) !== false)
			{
				$return = $result;
			}
			else
			{
				if (! class_exists ( $class ))
				{
					if (_C_View::templateExists($class))
						$template = $class;
				}
				else
				{
					if (_C_View::templateExists($class.DS.$method))
					{
						$template = $class.DS.$method;
					}
					else if (_C_View::templateExists($method))
					{
						$template = $method;
					}
				}
			
				if (isset($template))
					$return = array(SHOW=>array('template'=>$template), VIEW_DATA=>$data);
			}
		}
		else
		{
			$return = $model_init;
		}
		_C_Model::_destroy($class, $method, $data_str);
		return $return;
	}

	
	/**
	 * 处理结果
	 * @param array or string $result
	 * 1. result_type (string) result_type为SUCCESS ERROR DIRECT SHOW JUMP SHOW404中的一种
	 * 2. array(result_type, array()) array()为模型的数据，为模版显示
	 * 		e.g.array(SHOW, array('info'=>''hello world))
	 * 3. array(result_type=>array(), array()) result_type同上所对的value为一些设置
	 * 		e.g.array(SHOW=>array('template'=>'..'), array('content'=>'hello world'))
	 * 4. array(result_type) e.g. array(SHOW);
	 * $result必须有一个result_type，Result::VIEW_DATA可有可无
	 */
	public static function processResult($result, $class='', $method='', $data_str='')
	{	
		$class = empty($class) ? strtolower(_C_Router::getClass()) : $class;
		$method = empty($method) ? strtolower(_C_Router::getMethod()) : $method;
		$data_str = empty($data_str) ? _C_Router::getData() : $data_str;
		
		$self_config = _C_Config::vars('_C_Controller');
		$self_config = is_array($self_config)? $self_config : array();
		
		$result_info = array (
				0 => $result, //result_type
				1 => array (), //result_config 这个会覆盖配置文件中结果的配置
				2 => array (), //数据
		);
		if (is_array ( $result ))
		{
			if (count ( $result ) == 2)
			{
				if (isset ( $result [1] ))
				{
					$result_info [2] = $result [1];
					unset ( $result [1] );
				}
				else
				{
					$result_info [2] = $result [0];
					unset ( $result [0] );	
				}
			}
	
			$r = each ( $result );
			if (is_numeric ( $r [0] ))
			{
				$result_info [0] = $r [1];
			}
			else
			{
				$result_info [0] = $r [0];
				$result_info [1] = $r [1];
			}
		}
	
		$result_type = $result_info[0];
		$result_config = $result_info[1];
		$result_data = $result_info[2];
	
		$m_config = _C_Config::vars($class);
		
		$m_config = is_array($m_config) && isset($m_config[$method]) && is_array($m_config[$method]) ? $m_config[$method] : array();
		//要传给模板的数据
		$data = null;
		//模板
		$template = null;
		
		switch ($result_type)
		{
			case DIRECT:
				if (!empty($result_config))
				{
					$uri = $result_config;
				}
				else if (isset ( $m_config [DIRECT] ))
				{
					$uri = $m_config [DIRECT];
				}
				else if (isset($self_config[DIRECT]))
				{
					$uri = $self_config[DIRECT];
				}
				
				if (!isset($uri) || !$uri) $uri = 'index';
				
				$segs = explode ( '/', trim ( $uri, '/' ) );
				if (($count = count ( $segs )) < 4)
				{
					switch ($count) {
						case 1 : //改变method重新组合
							$method = $segs [0];
							break;
						case 2 : //改变class和method重新组合
							$class = $segs [0];
							$method = $segs [1];
							break;
						case 3 : //改变class/method/data重新组合
							$class = $segs [0];
							$method = $segs [1];
							$data_str = $segs [2];
							break;
						default :
							break;
					}
				}
				return self::processResult(self::_getResult($class, $method, $data_str), $class, $method, $data_str);
				break;
			case SUCCESS :
				if (!is_array($result_config))
				{
					if (is_string($result_config))
						$result_config = array('message'=>$result_config);
					//more...
				}
				$data = array_merge ( $result_config, $result_data );
				$data = array (
						'title' => isset ( $data ['title'] ) ? $data ['title'] : $self_config[SUCCESS]['title'],
						'message' => isset ( $data ['message'] ) ? $data ['message'] : $self_config[SUCCESS]['message']
				);
				break;
	
			case ERROR :
				if (!is_array($result_config))
				{
					if (is_string($result_config))
						$result_config = array('message'=>$result_config);
					//more...
				}
				$data = array_merge ( $result_config, $result_data );
				$data = array (
						'title' => isset ( $data ['title'] ) ? $data ['title'] : $self_config[ERROR]['title'],
						'message' => isset ( $data ['message'] ) ? $data ['message'] : $self_config[ERROR]['message']
				);
				break;
	
			case SHOW :
				if (is_string($result_config))
					$result_config = array('template'=>$result_config);
				//more...
				$data = $result_data;
				break;
	
			case JUMP :
				if (!is_array($result_config))
				{
					if (is_string($result_config))
						$result_config = array('message'=>$result_config);
					//more...
				}
				$data = array_merge ( $result_config, $result_data );
				$data = array (
						'title' => isset ( $data ['title'] ) ? $data ['title'] : $self_config[JUMP]['title'],
						'message' => isset ( $data ['message'] ) ? $data ['message'] : $self_config[JUMP]['message'],
						'link' => isset ( $data ['link'] ) ? $data ['link'] : $self_config[JUMP]['link'],
						'time' => isset ( $data ['time'] ) ? $data ['time'] : $self_config[JUMP]['time']
				);
				break;
	
			case SHOW404 :
				$data = array ('error_msg' =>'Page not found!');
				break;
			default:
				_C_App::triggerError('Result Type: ' . $result_type .' Not Found!');
				break;
		}
		
		if (isset ( $result_config['template'] ))
		{
			$template = $result_config['template'];
		}
		else if (isset($m_config[$result_type]) && isset ( $m_config [$result_type] ['template']))
		{
			$template = $m_config [$result_type] ['template'];
		}
		else 
		{
			if ($result_type == SHOW)
			{
				$template = $class.DS.$method;
			}
			else if (isset($self_config[$result_type]) && isset($self_config[$result_type]['template']))
			{
				$template = $self_config[$result_type]['template'];
			}	
		}
		
		if (empty($template) || _C_View::templateExists($template) === false)
		{
			if (!empty($template) && _C_View::templateExists($class.'/'.$template))
			{
				$template = $class.DS.$template;
			}
			else if (_C_View::templateExists($class.'/'.$method))
			{
				$template = $class.DS.$method;
			}
			else
			{
				return self::processResult(SHOW404);
			}
		}
		return array('template'=>$template, 'data'=>$data);
	}
	
	public static function showResult($result)
	{
		_C_View::showResult($result);
	}
}

?>