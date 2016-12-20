<?php
final class comment {
	
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
		if (empty($data['content']))
		{
			return Lang::CONTENT . Lang::NOEMPTY;
		}	
		
		$_POST['article_id'] = intval($_POST['article_id']);
		
		$_POST['ref_id'] = isset($_POST['ref_id']) ? intval($_POST['ref_id']) : 0;
		
		if (!DB::exists(DB::ARTICLE, array('id'=>$_POST['article_id'])))
		{
			return Lang::ARTICLE . Lang::NOTFOUND;	
		}
		
		$_POST['time'] = time();
		
		if (user::_isLogin())
		{
			$_POST['author'] = $_SESSION['user']['name'];
			$_POST['author_id'] = $_SESSION['user']['id'];	
		}
		else
		{
			$_POST['author'] = _C_Router::getClientIp();
		}
		
		return true;
	}
	
	public static function add($id)
	{
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
		{
			if (($msg=self::_checkData($_POST)) !== true)
				return array(ERROR=>array('message' => $msg));
			
			if (DB::insert(DB::COMMENT, $_POST))
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
			return array(SHOW, array(
					'submited' 		=> SYS_TIME,
					'_action'   	=> Url::mkurl('comment', 'add'),  
					'article_id'	=> (int)$id,
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
		if (count($ids) != DB::count(DB::COMMENT, 'id in ('.implode(',', $ids).')'))
		{
			return array(ERROR=>array('message'=>Lang::ILLEGAL_OPERATE));	
		}
		
		$ref_infos = DB::findAll(DB::COMMENT, 'ref_id in ('.implode(',', $ids).')');
		
		foreach ($ref_infos as $ref_info)
		{
			$ids[] = $ref_info['id'];
		}
		
		if (DB::delete(DB::COMMENT, 'id in ('.implode(',', $ids).')'))
		{
			return SUCCESS;
		}
		else
		{
			return ERROR;	
		}
	}
	
	public static function up($id)
	{
		if (DB::update(DB::COMMENT, array('up'=>'up+1'), array('id'=>(int)$id)))
		{
			return SUCCESS;
		}
		return ERROR;
	}
	
	public static function down($id)
	{
		if (DB::update(DB::COMMENT, array('down'=>'down+1'), array('id'=>(int)$id)))
		{
			return SUCCESS;
		}
		return ERROR;	
	}
	
	public static function index()
	{
		
	}
		
}

?>