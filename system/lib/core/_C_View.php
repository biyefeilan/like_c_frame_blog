<?php
(defined ( 'APP_PATH' )) || exit ( 'Access deny!' );

final class _C_View {
	
	/**
	 * 输出模板
	 * @param string $template
	 * @param mixed $data
	 * @return bool 未找到模板返回false
	 */
	public static function show($template, $data = null) 
	{
		
		if (($file=self::templateExists($template)) !== false)
		{
			is_array ( $data ) && extract ( $data );
			include ($file);
		}
		else 
		{
			echo $file;
			return false;
		}
	}
	
	public static function templateExists($template)
	{
		return file_exists($file= _C_App::filePath ( $template, 'template')) ? $file : false;
	}
	
	public static function showResult($result) 
	{
		return self::show($result['template'], $result['data']);
	}
	
	public static function layout($layout_name, $data=null)
	{
		
		if (file_exists($file= _C_App::filePath ( $layout_name, 'layout')))
		{
			is_array ( $data ) && extract ( $data );
			ob_start();
			include ($file);
			$content = ob_get_contents();
			@ob_end_clean();
			return $content;
		}
		
		return false;
	}
	
	private function __construct() {
	}
	
	private function __clone() {
	}
}

?>