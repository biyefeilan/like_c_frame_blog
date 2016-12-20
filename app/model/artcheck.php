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
	
	public static function index()
	{
		$data = DB::findAll(DB::ARTCHECK, array('status'=>9));
		return array(SHOW, $data);	
	}
	
	public static function add()
	{
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
		{	
			$_POST['time'] = time();
			
			if ($_SESSION['user']['type']  > 1)
			{
				$_POST['author_id'] = $_SESSION['user']['id'];
			}
			else
			{
				$_POST['admin_id'] = $_SESSION['user']['id'];	
			}
			
			$_POST['read'] = 1;
			
			if (DB::insert(DB::ARTCHECK, $_POST))
			{
				return SUCCESS;	
			}
			else
			{
				return ERROR;	
			}
		}
		else
		{
			return array(SHOW, array('submited' => SYS_TIME));
		}	
	}
	
	public static function delete()
	{
		
	}
}
?>