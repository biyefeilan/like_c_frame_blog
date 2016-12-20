<?php
final class article {

	public static function _init()
	{
		return true;	
	}	
	
	public static function _destory()
	{
		return true;	
	}
	
	private static function _checkData( &$data )
	{
		if (empty($data['title']))
		{
			return Lang::TITLE_NO_EMPTY;	
		}	
		if (empty($data['author']))
		{
			return Lang::AUTHOR_NO_EMPTY;	
		}
		if (empty($data['content']))
		{
			return Lang::CONTENT_NO_EMPTY;	
		}
		
		$data['description'] = Util::mb_substr($data['content'], 0, 200);
		
		return true;
	}
	
	public static function add()
	{
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
		{
			if (($msg=self::_checkData($_POST)) !== true)
				return array(ERROR=>array('message' => $msg));
			
			$_POST['add_time'] = time();
			
			$_POST['author_id'] = $_SESSION['user']['id'];
			
			if (DB::insert(DB::ARTICLE, $_POST))
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
					'author'		=> $_SESSION['user']['name'],
					'_title'    	=> 'Article',
					'_action'   	=> Url::mkurl('article', 'add'),  
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
				
			$_POST['modify_time'] = time();
			
			$_POST['status'] = '9';
				
			if (DB::update(DB::ARTICLE, $_POST, array('id'=>$id)))
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
			if ($_SESSION['user']['type'] == 2 && DB::exists(DB::ARTICLE, array('id'=>$id, 'author_id'=>$_SESSION['user']['id'])))
			{
				$data = DB::findOne(DB::ARTICLE, array('id'=>$id));
				if (!empty($data))
				{
					$data = array_merge($data, array(
						'submited' 		=> SYS_TIME,
						'_title'    	=> 'Article',
						'_action'   	=> Url::mkurl('article', 'edit', '', $id), 
					));
					return array(SHOW=>array('template'=>'add_edit'), $data);
				}
				else 
				{
					return array(JUMP=>array('message'=>Lang:: ARTICLE_NOT_FOUND));
				}
			}
			else
			{
				return array(ERROR=>array('message'=>Lang::PERMISSION_DENY));
			}
		}
	}
	
	public static function delete($id_list)
	{
		$ids = array_map('intval', preg_split('/,\s*/', $id_list));
		if (!count($ids))
			return array(ERROR=>array('message'=>Lang::ILLEGAL_OPERATE));
		//如果取整后和数据库中的数目不匹配，认定为非法操作
		if (count($ids) != DB::count(DB::ARTICLE, 'id in ('.implode(',', $ids).')'))
		{
			return array(ERROR=>array('message'=>Lang::ILLEGAL_OPERATE));	
		}
		$del_ids = array();
		foreach ($ids as $id)
		{
			if ($_SESSION['user']['type'] == 2 && DB::exists(DB::ARTICLE, array('id'=>$id, 'author_id'=>$_SESSION['user']['id'])))
			{
				$del_ids[] = $id; 
			}
		}
		if (count($del_ids))
		{
			if (DB::delete(DB::ARTICLE, 'id in ('.implode(',', $del_ids).')'))
			{
				return SUCCESS;	
			}
			else
			{
				return ERROR;	
			}
		}
		return array(ERROR=>array('message'=>Lang::PERMISSION_DENY));
	}
	
	public static function show($id)
	{
		$id = (int)$id;
		if ($id)
		{
			$data = DB::findOne(DB::ARTICLE, array('id'=>$id));
			if (!empty($data))
			{
				//审核通过或者审核未通过但是作者自己看或者管理员看
				if ( ((int)$data['status'] & 3) == 0 || 
						(user::_isLogin() && 
							(!user::_isAdmin() && $data['author_id']==$_SESSION['user']['id']) ||
								user::_isAdmin()			
				) )
				{
					return array(SHOW, $data);
				}
			}
			
			return array(JUMP=>array('message'=>Lang:: ARTICLE_NOT_FOUND));
		}
		return ERROR;
	}
	
	
	public static function page()
	{
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$result = DB::pages(DB::ARTICLE, null, null, '*', $page, $page_size=2);
	
		print_r($result['data']);
		echo View::pagesList($result['info'], Url::mkurl('article', 'page'));	
	}
}
?>