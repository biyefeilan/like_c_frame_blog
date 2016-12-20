<?php

(defined ( 'APP_PATH' )) || exit ( 'Access deny!' );

class _C_File {
	
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
	
	public static function exists() {
		if (func_num_args () === 0)
			return true;
		$files = array ();
		if (func_num_args () === 1) {
			if (is_string ( func_get_arg ( 0 ) )) {
				return file_exists ( func_get_arg ( 0 ) );
			} else if (is_array ( func_get_arg ( 0 ) )) {
				$files = func_get_arg ( 0 );
			}
		} else {
			$files = func_get_args ();
		}
		foreach ( $files as $file ) {
			if (file_exists ( $file ) === false)
				return false;
		}
		return true;
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
}

?>