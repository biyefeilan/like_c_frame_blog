<?php
final class dict {
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
		
		if (empty($data['name_pattern']))
		{
			//设定为系统默认，拼音和汉字替换
			$_POST['name_pattern'] = "/{$data['name']}/i";	
		}
		
		if (empty($data['replace_pattern']))
		{
			$_POST['replace_pattern'] = '<font color="red">'.$data['name'].'</font>';	
		}
		
		return true;
	}
	
	public static function add()
	{
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
		{
			if (($msg=self::_checkData($_POST)) !== true)
				return array(ERROR=>array('message' => $msg));
			
			$_POST['time'] = time();
			
			if (DB::insert(DB::DICT, $_POST))
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
					'_action'   	=> Url::mkurl('dict', 'add'),  
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
				
			if (DB::update(DB::DICT, $_POST, array('id'=>$id)))
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
			$data = DB::findOne(DB::DICT, array('id'=>$id));
			if (!empty($data))
			{
				$data = array_merge($data, array(
					'submited' 		=> SYS_TIME,
					'_title'    	=> 'edit',
					'_action'   	=> Url::mkurl('dict', 'edit', '', $id), 
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
		if (count($ids) != DB::count(DB::DICT, 'id in ('.implode(',', $ids).')'))
		{
			return array(ERROR=>array('message'=>Lang::ILLEGAL_OPERATE));	
		}
		
		$ref_infos = DB::findAll(DB::DICT, 'ref_id in ('.implode(',', $ids).')');
		
		foreach ($ref_infos as $ref_info)
		{
			$ids[] = $ref_info['id'];
		}
		
		if (DB::delete(DB::DICT, 'id in ('.implode(',', $ids).')'))
		{
			return SUCCESS;
		}
		
		return ERROR;	
	}
	
}
?>