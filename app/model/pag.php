<?php
final class pag {
	public static function _init()
	{
		return true;	
	}	
	
	public static function _destroy()
	{
		return true;	
	}
	
	private static function _checkData(&$data)
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
			
			if (DB::insert(DB::PAG, $_POST))
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
					'_title'		=> 'add',
					'_action'   	=> Url::mkurl('pag', 'add'),  
				)
			);
		}
		return ERROR;	
	}
	
	public static function edit($id)
	{
		$id = (int)$id;
		if ($id<=0) return ERROR;
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
		{
			if (($msg=self::_checkData($_POST)) !== true)
				return array(ERROR=>array('message' => $msg));
				
			if (DB::update(DB::PAG, $_POST, array('id'=>$id)))
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
			$data = DB::findOne(DB::PAG, array('id'=>$id));
			if (!empty($data))
			{
				$data = array_merge($data, array(
					'submited' 		=> SYS_TIME,
					'_title'    	=> 'edit',
					'_action'   	=> Url::mkurl('pag', 'edit', '', $id), 
				));
				return array(SHOW=>array('template'=>'add_edit'), $data);
			}
			else 
			{
				return array(JUMP=>array('message'=>Lang::NOTFOUND));
			}
		}
		return ERROR;	
	}
	
	public static function delete($id_list)
	{
		$ids = array_map('intval', preg_split('/,\s*/', $id_list));
		if (!count($ids))
			return array(ERROR=>array('message'=>Lang::ILLEGAL_OPERATE));
		//如果取整后和数据库中的数目不匹配，认定为非法操作
		if (count($ids) != DB::count(DB::PAG, 'id in ('.implode(',', $ids).')'))
		{
			return array(ERROR=>array('message'=>Lang::ILLEGAL_OPERATE));	
		}
		
		if (DB::delete(DB::PAG, 'id in ('.implode(',', $ids).')'))
		{
			return SUCCESS;
		}
		
		return ERROR;	
	}
	
}
?>