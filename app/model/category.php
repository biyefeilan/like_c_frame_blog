<?php
final class category {
	public static function _init()
	{
		return true;	
	}
	
	public static function _destroy()
	{
		return true;
	}
	
	public static function _checkData(&$data)
	{
		if (empty($data['name']))
		{
			return Lang::NOEMPTY;	
		}
		else
		{
			if (DB::exists(DB::CATEGORY, array('name'=>$data['name'])))
			{
				return Lang::FAIL;	
			}	
		}
		
		if (empty($data['folder']))
		{
			return Lang::NOEMPTY;	
		}
		else
		{
			if (DB::exists(DB::CATEGORY, array('folder'=>$data['folder'])))
				return Lang::FAIL;	
		}
		
		$data['parent_id'] = isset($data['parent_id']) ? (int)$data['parent_id'] : 0; 
		
		$data['url'] = isset($data['url']) ? $data['url'] : '/' . $data['folder'] . '/';
 		
		return true;
	}
	
	public static function add()
	{
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
		{
			if (($msg=self::_checkData($_POST)) !== true)
				return array(ERROR=>array('message' => $msg));
		
			if (DB::insert(DB::CATEGORY, $_POST))
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
			return array(SHOW=>array('template'=>'add_edit'), array(
					'submited' 		=> SYS_TIME,
					'_title'    	=> 'Article',
					'_action'   	=> Url::mkurl('category', 'add'),  
				)
			);
		}	
	}
	
	public static function delete($id_list)
	{
		$ids = array_map('intval', preg_split('/,\s*/', $id_list));
		if (!count($ids))
			return array(ERROR=>array('message'=>Lang::ILLEGAL_OPERATE));
		//如果取整后和数据库中的数目不匹配，认定为非法操作
		if (count($ids) != DB::count(DB::CATEGORY, 'id in ('.implode(',', $ids).')'))
		{
			return array(ERROR=>array('message'=>Lang::ILLEGAL_OPERATE));	
		}
		//系统栏目只有超级管理员可以删除
		if ($_SESSION['user']['type'] != 0 && count($ids) > 0)
		{
			$sys_cats = DB::findAll(DB::CATEGORY, array('system' => '1'), '', 'id');
			$sys_ids = array();
			foreach ($sys_cats as $cat)
			{
				$sys_ids[] = $cat['id'];
			}	
			$ids = array_diff($ids, $sys_ids);	
		}
		
		foreach ($ids as $id)
		{
			if (!DB::delete(DB::CATEGORY, 'id in ('.implode(',', $ids).')', 1))
			{
				return ERROR;	
			}
		}
		return SUCCESS;	
	}
	
	public static function edit($id)
	{
		$id = (int)$id;
		if ($id<=0) return ERROR;
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
		{
			if (($msg=self::_checkData($_POST)) !== true)
				return array(ERROR=>array('message' => $msg));
				
			if (DB::update(DB::CATEGORY, $_POST, array('id'=>$id)))
			{
				return array(SUCCESS=>array('title'=>Lang::OPERATE_SUCCESS, 'message'=>Lang::ARTICLE_EDIT_SUCCESS));	
			}
			else
			{
				return ERROR;	
			}
		}
		else
		{
			$data = DB::findOne(DB::CATEGORY, array('id'=>$id));
			if ($data['system'] == 1 && $_SESSION['user']['type'] != 0)
			{
				return array(ERROR=>array('message'=>Lang::PERMISSION_DENY));
			}
			if (!empty($data))
			{
				$data = array_merge($data, array(
					'submited' 		=> SYS_TIME,
					'_title'    	=> 'department',
					'_action'   	=> Url::mkurl('category', 'edit', '', $id), 
				));
				return array(SHOW=>array('template'=>'add_edit'), $data);
			}
			else 
			{
				return array(JUMP=>array('message'=>Lang::NOTFOUND));
			}
		}
	}
	
}

?>