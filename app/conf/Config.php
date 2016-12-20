<?php (defined ( 'APP_PATH' )) || exit ( 'Access deny!' );

final class Config 
{
	/**
	*对于controler_init每个uri访问时就已定，所以没必要传uri参数
	*/
	public static function _controller_init()
	{
		_C_Session::init();
		
		//定义当前访问时间
		define('SYS_TIME', time());
		
		if (!isset($_SESSION['PREV_TIME']))
			$_SESSION['PREV_TIME'] = SYS_TIME;
		
		//定义上一次的访问时间	
		define('PREV_TIME', $_SESSION['PREV_TIME']);
		
		DB::init();
		
		//公共的
		if (_C_ === 'home')
		{
			return true;	
		}
		
		if (!user::_isLogin() && ( _C_!='user' || !in_array(_A_, array('input_login', 'login'))))
		{
			return array(DIRECT=>'user/login');
		}
		return true;
	}
	
	public static function _controller_destory()
	{
		$_SESSION['PREV_TIME'] = SYS_TIME;
		DB::destroy();
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
		return true;
	}
	
	public static function _model_destroy($class, $method, $data)
	{
		return true;
	}
	
	private static $_vars = array(
		
		'attach' => array (
			'max_size'	=> 10240,
			'file_ext'	=> array('jpg', 'bmp', 'gif', 'png'),		
		),	
			
		'_C_App' => array (
			'debug' 				=> true, //true调试模式
			'error_log'				=> true, //是否记录错误日志,如果debug==false && error_log==true会记录错误日志
			'error_reporting_level' => E_ALL, //报错等级
			'app_max_time' 			=> 30, //程序执行的最大时间以秒记
			'time_zone' 			=> 'PRC', //时区
			'base_url' 				=> 'http://localhost/',//'http://localhost/index.php/', 
			'js_url' 				=> '/static/js/', 
			'img_url' 				=> '/static/img/', 
			'css_url' 				=> '/static/css/', 
			'auto_trim_gpc' 		=> true, //自动去除get,post,cookie,request的值的两边的空白
			'lang'					=> 'zh-cn',	
		),
		
		'_C_Controller' => array(
			
			SUCCESS => array (
				'title' => 'success', 
				'message' => 'success', 
				'template' => 'success' 
			), 
				
			ERROR => array (
				'title' => 'error', 
				'message' => 'message', 
				'template' => 'error' 
			), 
				
			SHOW => array (
				'template' => 'index' 
			), 
						
			SHOW404 => array (
				'template' => 'error_404' 
			),
				
			JUMP => array (
				'title' => '', 
				'message' => 'jump', 
				'template' => 'jump', 
				'link' => '',
				'time' => '3' 
			),
			
			DIRECT => 'index/index',
		),
		
		'_C_Db' => array (
			'default' => array (
					'hostname' => 'localhost', 
					'hostport' => '3306', 
					'username' => 'root', 
					'password' => '1234', 
					'database' => 'like_c_frame_blog', 
					'driver' => 'mysql', 
					'pconnect' => false, 
					'charset' => 'gbk', 
					'collate' => 'gbk_chinese_ci' 
			) 
		),
		
		'_C_Model' =>  array(
				
		),
		
		'_C_Router' => array (
			'/'	=> 'index/index/',
			'class/method/action' => 'class1/method1/action1',
			'c/m/a' => 'c2/m2/a2',
			'c/w/.*' => 'c/c/w/d',
			'product/(\d+)' => 'catalog/product_lookup_by_id/$1',
		),
		
		'_C_Response' => array (
			'compress_content' => false, 
			'charset' => 'GBK', 
			'status_codes' => array (
				100 => 'Continue', 
				101 => 'Switching Protocols', 
				200 => 'OK', 
				201 => 'Created', 
				202 => 'Accepted', 
				203 => 'Non-Authoritative Information', 
				204 => 'No Content', 
				205 => 'Reset Content', 
				206 => 'Partial Content', 
				300 => 'Multiple Choices', 
				301 => 'Moved Permanently', 
				302 => 'Found', 
				303 => 'See Other', 
				304 => 'Not Modified', 
				305 => 'Use Proxy', 
				307 => 'Temporary Redirect', 
				400 => 'Bad Request', 
				401 => 'Unauthorized', 
				402 => 'Payment Required', 
				403 => 'Forbidden', 
				404 => 'Not Found', 
				405 => 'Method Not Allowed', 
				406 => 'Not Acceptable', 
				407 => 'Proxy Authentication Required', 
				408 => 'Request Time-out', 
				409 => 'Conflict', 
				410 => 'Gone', 
				411 => 'Length Required', 
				412 => 'Precondition Failed', 
				413 => 'Request Entity Too Large', 
				414 => 'Request-URI Too Large', 
				415 => 'Unsupported Media Type', 
				416 => 'Requested range not satisfiable', 
				417 => 'Expectation Failed', 
				500 => 'Internal Server Error', 
				501 => 'Not Implemented', 
				502 => 'Bad Gateway', 
				503 => 'Service Unavailable', 
				504 => 'Gateway Time-out' 
			), 

			'mime_types' => array (
				'ai' => 'application/postscript', 
				'bcpio' => 'application/x-bcpio', 
				'bin' => 'application/octet-stream', 
				'ccad' => 'application/clariscad', 
				'cdf' => 'application/x-netcdf', 
				'class' => 'application/octet-stream', 
				'cpio' => 'application/x-cpio', 
				'cpt' => 'application/mac-compactpro', 
				'csh' => 'application/x-csh', 
				'csv' => array ('text/csv', 'application/vnd.ms-excel', 'text/plain' ), 
				'dcr' => 'application/x-director', 
				'dir' => 'application/x-director', 
				'dms' => 'application/octet-stream', 
				'doc' => 'application/msword', 
				'drw' => 'application/drafting', 
				'dvi' => 'application/x-dvi', 
				'dwg' => 'application/acad', 
				'dxf' => 'application/dxf', 
				'dxr' => 'application/x-director', 
				'eot' => 'application/vnd.ms-fontobject', 
				'eps' => 'application/postscript', 
				'exe' => 'application/octet-stream', 
				'ez' => 'application/andrew-inset', 
				'flv' => 'video/x-flv', 
				'gtar' => 'application/x-gtar', 
				'gz' => 'application/x-gzip', 
				'bz2' => 'application/x-bzip', 
				'7z' => 'application/x-7z-compressed', 
				'hdf' => 'application/x-hdf', 
				'hqx' => 'application/mac-binhex40', 
				'ico' => 'image/vnd.microsoft.icon', 
				'ips' => 'application/x-ipscript', 
				'ipx' => 'application/x-ipix', 
				'js' => 'text/javascript', 
				'latex' => 'application/x-latex', 
				'lha' => 'application/octet-stream', 
				'lsp' => 'application/x-lisp', 
				'lzh' => 'application/octet-stream', 
				'man' => 'application/x-troff-man', 
				'me' => 'application/x-troff-me', 
				'mif' => 'application/vnd.mif', 
				'ms' => 'application/x-troff-ms', 
				'nc' => 'application/x-netcdf', 
				'oda' => 'application/oda', 
				'otf' => 'font/otf', 
				'pdf' => 'application/pdf', 
				'pgn' => 'application/x-chess-pgn', 
				'pot' => 'application/mspowerpoint', 
				'pps' => 'application/mspowerpoint', 
				'ppt' => 'application/mspowerpoint', 
				'ppz' => 'application/mspowerpoint', 
				'pre' => 'application/x-freelance', 
				'prt' => 'application/pro_eng', 
				'ps' => 'application/postscript', 
				'roff' => 'application/x-troff', 
				'scm' => 'application/x-lotusscreencam', 
				'set' => 'application/set', 
				'sh' => 'application/x-sh', 
				'shar' => 'application/x-shar', 
				'sit' => 'application/x-stuffit', 
				'skd' => 'application/x-koan', 
				'skm' => 'application/x-koan', 
				'skp' => 'application/x-koan', 
				'skt' => 'application/x-koan', 
				'smi' => 'application/smil', 
				'smil' => 'application/smil', 
				'sol' => 'application/solids', 
				'spl' => 'application/x-futuresplash', 
				'src' => 'application/x-wais-source', 
				'step' => 'application/STEP', 
				'stl' => 'application/SLA', 
				'stp' => 'application/STEP', 
				'sv4cpio' => 'application/x-sv4cpio', 
				'sv4crc' => 'application/x-sv4crc', 
				'svg' => 'image/svg+xml', 
				'svgz' => 'image/svg+xml', 
				'swf' => 'application/x-shockwave-flash', 
				't' => 'application/x-troff', 
				'tar' => 'application/x-tar', 
				'tcl' => 'application/x-tcl', 
				'tex' => 'application/x-tex', 
				'texi' => 'application/x-texinfo', 
				'texinfo' => 'application/x-texinfo', 
				'tr' => 'application/x-troff', 
				'tsp' => 'application/dsptype', 
				'ttf' => 'font/ttf', 
				'unv' => 'application/i-deas', 
				'ustar' => 'application/x-ustar', 
				'vcd' => 'application/x-cdlink', 
				'vda' => 'application/vda', 
				'xlc' => 'application/vnd.ms-excel', 
				'xll' => 'application/vnd.ms-excel', 
				'xlm' => 'application/vnd.ms-excel', 
				'xls' => 'application/vnd.ms-excel', 
				'xlw' => 'application/vnd.ms-excel', 
				'zip' => 'application/zip', 
				'aif' => 'audio/x-aiff', 
				'aifc' => 'audio/x-aiff', 
				'aiff' => 'audio/x-aiff', 
				'au' => 'audio/basic', 
				'kar' => 'audio/midi', 
				'mid' => 'audio/midi', 
				'midi' => 'audio/midi', 
				'mp2' => 'audio/mpeg', 
				'mp3' => 'audio/mpeg', 
				'mpga' => 'audio/mpeg', 
				'ogg' => 'audio/ogg', 
				'ra' => 'audio/x-realaudio', 
				'ram' => 'audio/x-pn-realaudio', 
				'rm' => 'audio/x-pn-realaudio', 
				'rpm' => 'audio/x-pn-realaudio-plugin', 
				'snd' => 'audio/basic', 
				'tsi' => 'audio/TSP-audio', 
				'wav' => 'audio/x-wav', 
				'asc' => 'text/plain', 
				'c' => 'text/plain', 
				'cc' => 'text/plain', 
				'css' => 'text/css', 
				'etx' => 'text/x-setext', 
				'f' => 'text/plain', 
				'f90' => 'text/plain', 
				'h' => 'text/plain', 
				'hh' => 'text/plain', 
				'html' => array ('text/html', '*/*' ), 
				'htm' => array ('text/html', '*/*' ), 
				'm' => 'text/plain', 
				'rtf' => 'text/rtf', 
				'rtx' => 'text/richtext', 
				'sgm' => 'text/sgml', 
				'sgml' => 'text/sgml', 
				'tsv' => 'text/tab-separated-values', 
				'tpl' => 'text/template', 
				'txt' => 'text/plain', 
				'text' => 'text/plain', 
				'xml' => array ('application/xml', 'text/xml' ), 
				'avi' => 'video/x-msvideo', 
				'fli' => 'video/x-fli', 
				'mov' => 'video/quicktime', 
				'movie' => 'video/x-sgi-movie', 
				'mpe' => 'video/mpeg', 
				'mpeg' => 'video/mpeg', 
				'mpg' => 'video/mpeg', 
				'qt' => 'video/quicktime', 
				'viv' => 'video/vnd.vivo', 
				'vivo' => 'video/vnd.vivo', 
				'gif' => 'image/gif', 
				'ief' => 'image/ief', 
				'jpe' => 'image/jpeg', 
				'jpeg' => 'image/jpeg', 
				'jpg' => 'image/jpeg', 
				'pbm' => 'image/x-portable-bitmap', 
				'pgm' => 'image/x-portable-graymap', 
				'png' => 'image/png', 
				'pnm' => 'image/x-portable-anymap', 
				'ppm' => 'image/x-portable-pixmap', 
				'ras' => 'image/cmu-raster', 
				'rgb' => 'image/x-rgb', 
				'tif' => 'image/tiff', 
				'tiff' => 'image/tiff', 
				'xbm' => 'image/x-xbitmap', 
				'xpm' => 'image/x-xpixmap', 
				'xwd' => 'image/x-xwindowdump', 
				'ice' => 'x-conference/x-cooltalk', 
				'iges' => 'model/iges', 
				'igs' => 'model/iges', 
				'mesh' => 'model/mesh', 
				'msh' => 'model/mesh', 
				'silo' => 'model/mesh', 
				'vrml' => 'model/vrml', 
				'wrl' => 'model/vrml', 
				'mime' => 'www/mime', 
				'pdb' => 'chemical/x-pdb', 
				'xyz' => 'chemical/x-pdb', 
				'javascript' => 'text/javascript', 
				'json' => 'application/json', 
				'form' => 'application/x-www-form-urlencoded', 
				'file' => 'multipart/form-data', 
				'xhtml' => array ('application/xhtml+xml', 'application/xhtml', 'text/xhtml' ), 
				'xhtml-mobile' => 'application/vnd.wap.xhtml+xml', 
				'rss' => 'application/rss+xml', 
				'atom' => 'application/atom+xml', 
				'amf' => 'application/x-amf', 
				'wap' => array ('text/vnd.wap.wml', 'text/vnd.wap.wmlscript', 'image/vnd.wap.wbmp' ), 
				'wml' => 'text/vnd.wap.wml', 
				'wmlscript' => 'text/vnd.wap.wmlscript', 
				'wbmp' => 'image/vnd.wap.wbmp' 
				) 
			),
	
	);
	
	public static function vars($class)
	{	
		return isset(self::$_vars[$class]) ? self::$_vars[$class] : null;
	}
}
?>