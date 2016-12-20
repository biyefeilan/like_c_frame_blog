<?php 
final class position {
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
		
		return true;
	}
	
	public static function add()
	{
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
		{
			if (($msg=self::_checkData($_POST)) !== true)
				return array(ERROR=>array('message' => $msg));
			
			$_POST['add_time'] = time();
			
			if (DB::insert(DB::POSITION, $_POST))
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
					'_title'		=> 'category add',
					'submited' 		=> SYS_TIME,
					'_action'   	=> Url::mkurl('position', 'add'),  
				)
			);
		}	
	}
	
	public static function edit($id)
	{
		$id = (int)$id;
		if ($id<=0) return ERROR;
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
		{
			if (($msg=self::_checkData($_POST)) !== true)
				return array(ERROR=>array('message' => $msg));
		
			if (DB::update(DB::POSITION, $_POST, array('id'=>$id)))
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
			$data = DB::findOne(DB::POSITION, array('id'=>$id));
			if (!empty($data))
			{
				$data = array_merge($data, array(
						'submited' 		=> SYS_TIME,
						'_title'    	=> 'Tag',
						'_action'   	=> Url::mkurl('position', 'edit', '', $id),
				));
				return array(SHOW=>array('template'=>'add_edit'), $data);
			}
			else
			{
				return array(JUMP=>array('message'=>Lang::NOTFOUND));
			}
		}
	}
	
	public static function delete($id_list)
	{
		$ids = array_map('intval', preg_split('/,\s*/', $id_list));
		if (!count($ids))
			return array(ERROR=>array('message'=>Lang::ILLEGAL_OPERATE));
		//如果取整后和数据库中的数目不匹配，认定为非法操作
		if (count($ids) != DB::count(DB::POSITION, 'id in ('.implode(',', $ids).')'))
		{
			return array(ERROR=>array('message'=>Lang::ILLEGAL_OPERATE));	
		}
		
		if (DB::delete(DB::POSITION, 'id in ('.implode(',', $ids).')'))
		{
			return SUCCESS;
		}
		else
		{
			return ERROR;	
		}
	}
	
}

?>