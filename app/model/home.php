<?php

final class home {
	
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
		return SHOW;	
	}
	
	public static function cat($folder)
	{
		$data = array();
		if (($cat_info = DB::findOne(DB::CATEGORY, array('folder'=>$folder, 'display'=>'1', 'parent_id'=>'0'), null, 'id, name'))!==false)
		{
			$data['cat_id'] = $cat_info['id'];
			$page = isset($_GET['page']) ? $_GET['page'] : 1;
			$result = DB::pages(DB::ARTICLE, array('category_id'=>$cat_info['id'], 'status'=>'0'), array('add_time'=>'DESC'), 'id, title, author, img, description, url, add_time, hits', $page, $page_size=10);
			$data = array_merge(array('articles'=>$result['data']), $data);
		}
		else 
		{
			return ERROR;
		}
		
		return array(SHOW=>array('template'=>'category'), $data);
	}
	
	public static function show($id)
	{
		$result = article::show($id);
		if (isset($result[1]) && is_array($result[1]))
			$result[1] = array_merge($result[1],  array('_title'=>'article show'));
		return $result;
	}
	
	public static function verify()
	{
		Image::verify();
		exit;
	}
}

?>