<?php (defined ( 'APP_PATH' )) || exit ( 'Access deny!' );

class _C_Builder {
	
	public static function is_writable($file) {
		//如果不是文件或目录
		if (! is_file ( $file ) && ! is_dir ( $file )) {
			return false;
		}
		
		//如果是unix服务器并且safe_mode关闭了，直接调用is_writable
		if (DIRECTORY_SEPARATOR == '/' and @ini_get ( "safe_mode" ) == FALSE) {
			return is_writable ( $file );
		}
		
		//如果是windows服务器并且safe_mode开启了
		if (is_dir ( $file )) {
			$file = rtrim ( $file, '/' ) . '/' . md5 ( mt_rand ( 1, 100 ) . mt_rand ( 1, 100 ) );
			
			if (($fp = @fopen ( $file, 'ab' )) === FALSE) {
				return FALSE;
			}
			
			fclose ( $fp );
			@chmod ( $file, 0777 );
			@unlink ( $file );
			return TRUE;
		}
		
		if (($fp = @fopen ( $file, 'ab' )) === FALSE) {
			fclose ( $fp );
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	*make sure dir
	*确保$path是一个dir，如果不存在创建，创建失败或不是dir返回false
	*/
	public static function mksdir($path, $mode = 0777)
	{
		if (version_compare(PHP_VERSION, '5.0.0') >= 0)
		{
			@mkdir($path, $mode, true);
		}
		else
		{
			$path = rtrim(preg_replace(array("#\\\\#", "#/{2,}#"), "/", $path), "/");
		    $e = explode("/", ltrim($path, "/"));
		    if($path[0] == "/") 
		    {
		        $e[0] = "/".$e[0];
		    }
		    $c = count($e);
		    $cp = $e[0];
		    for($i = 1; $i < $c; $i++) 
		    {
		        if(!is_dir($cp) && !@mkdir($cp, $mode)) 
		        {
		            return false;
		        }
		        $cp .= "/".$e[$i];
		    }
		    @mkdir($path, $mode);
		}
		return is_dir($path);
	}
	
	/**
	*整合核心类的缓存，经常要用到的类用$files增加缓存，注意不要把有相同类的文件包含进来
	*非类文件不能整合，model类文件不能整合
	*@param string $core_cache 缓存文件
	*@param string $files 要增加的文件类
	*@return bool 成功整合返回true,失败返回false
	*/
	public static function buildCoreCache($core_cache, $files = array())
	{
		if (self::mksdir(dirname($core_cache))  && self::is_writable(dirname($core_cache)))
		{
			$new_files = array();
			foreach ($files as $file)
			{
				if (is_dir($file))
				{
					$file = rtrim(preg_replace(array("#\\\\#", "#/{2,}#"), "/", $file), "/") .DS.'*'.PHP_SUFFIX;
					$new_files = array_merge($new_files, glob($file));
				}	
				else if (is_file($file)) 
				{
					$new_files[] = $file;
				}
			}
			$new_files = array_merge(glob(SYSTEM_PATH . DS . 'lib' . DS . 'core' . DS . '*' . PHP_SUFFIX), $new_files);
			$new_files = array_unique($new_files);
			$contents = array();
			foreach ($new_files as $file)
			{
				$contents[] = self::classSource($file);
			}
			if (count($contents))
			{
				if (file_put_contents($core_cache, '<?php ' . implode('', $contents) . '?>', LOCK_EX))
				{
					@chmod($core_cache, 0777);	
					return true;
				}	
			}
		}	
		return false;
	}
	
	public static function classSource($file, $className = null)
	{
		$class = '';
		$signCout = 0;
		$classBegin = false;
		$testBegin = false;
		$db_quotation_start = false;
		$className = $className == null ? basename($file, PHP_SUFFIX) : $className;
		$file_source = php_strip_whitespace($file);
		$tokens = token_get_all($file_source);
		for ( $i = 0, $j = count($tokens); $i < $j; ++$i )
		{
			if ( is_string($tokens[$i]) )
			{
				$class .= $tokens[$i];
				if ($tokens[$i]=='"')
				{
					$db_quotation_start = $db_quotation_start === false ? true : false;
				}
				if ($db_quotation_start === false)
				{
					if ($tokens[$i]=='{')
					{
						++$signCout;
					}
					if ($tokens[$i]=='}')
					{
						if (--$signCout == 0)
							return $class;
					}
				}
			}
			else
			{
				if ($classBegin == false)
				{
					$tokename = token_name($tokens[$i][0]);
					if ($testBegin == false)
					{
						if ($tokename == 'T_ABSTRACT' || $tokename== 'T_FINAL' || $tokename== 'T_CLASS')
						{
							$testBegin = true;
							$class = '';
						}
					}
					else
					{
						if (($tokename != 'T_WHITESPACE' && $tokename != 'T_CLASS' && $tokename != 'T_STRING')
								||
								$tokename == 'T_STRING' && $tokens[$i][1] != $className
						)
						{
							$testBegin = false;
						}
						if ($tokename == 'T_STRING' && $tokens[$i][1] == $className)
						{
							$classBegin = true;
						}
					}
				}
				$class .= $tokens[$i][1];
			}
		}
	}
	
	/**
	 * 将文件中第一个array数组定义的源代码按照以文件名为变量的定义返回
	 * @param string $filename
	 * @param string $package
	 * @return string
	 * @access public
	 */
	public static function configSource($filename, $package)
	{
		$array = '';
		$signCout = 0;
		$start = false;
		$file = self::filePath($filename, $package);
		$file_source = php_strip_whitespace($file);
		$tokens = token_get_all($file_source);
		for ( $i = 0, $j = count($tokens); $i < $j; ++$i )
		{
			if ( is_string($tokens[$i]) )
			{
				if ($start == true)
				{
					$array .= $tokens[$i];
					if ($tokens[$i]=='(')
					{
						++$signCout;
					}
					if ($tokens[$i]==')')
					{
						if (--$signCout == 0)
							return '$'.self::$TYPES['config'].'[\''.$package.'\'][\''.$filename.'\']='.$array.';';
					}
				}
			}
			else
			{
				if ($start === false)
				{
					$tokename = token_name($tokens[$i][0]);
					if ($tokename == 'T_ARRAY')
					{
						$start = true;
						$array = '';
					}
				}
				$array .= $tokens[$i][1];
			}
		}
	}
	
	/**
	 * 不保存资源和对象类型
	 * @param array $arr
	 * @return string
	 */
	public static function getArrayDefineSource($arr)
	{
		if (is_array($arr))
		{
			$str = 'array(';
			foreach ($arr as $k=>$v)
			{
				if (is_resource($v) || is_object($v))
					continue;
				$str .= is_string($k) ? "'{$k}'" : $k;
				$str .= ' => ';
				if (is_bool($v))
				{
					$str .= $v === true ? 'true' : 'false';
				}
				else if (is_string($v))
				{
					$str .= "'{$v}'";
				}
				else if (is_array($v))
				{
					$str .= self::getArrayDefineSource($v);
				}
				else
				{
					$str .= $v;
				}
				$str .= ',';
	
			}
			$str .= ')';
			return $str;
		}
		else
			return false;
	}

}

?>