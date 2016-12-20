<?php
final class artcheck {
	
	public static function _init()
	{
		return true;	
	}
	
	public static function _destroy()
	{
		return true;	
	}
	
	public static function deRef($url)
	{
		
	}
	
	public static function add()
	{
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
		{	
			$_file = $_FILES['userfile'];
			
			if ($_file['error'] == 0)
			{
				$_POST['add_time'] = time();
					
				$_POST['url'] = '';
				
				$_POST['ref_count'] = 0;
				
				$_POST['size'] = 0;
				
				$_POST['is_img'] = 0;
					
				if (DB::insert(DB::ATTACH, $_POST))
				{
					return SUCCESS;
				}
			}
			
			return ERROR;
		}
		else
		{
			return array(SHOW, array(
					'submited' => SYS_TIME,
					'_action'  => Url::mkurl('attach', 'add'),
			));
		}	
	}
	
	public static function delete()
	{
		
	}
}
?>