<?php
final class artpos {
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
		if (empty($data['title']))
		{
			return Lang::NOEMPTY;	
		}
		
		if (empty($data['author']))
		{
			return Lang::NOEMPTY;	
		}
		
		if (empty($data['guide']))
		{
			return Lang::NOEMPTY;	
		}
		
		if (empty($data['reason']))
		{
			return Lang::NOEMPTY;	
		}
		
		return true;	
	}
	
	public static function add($article_id)
	{
		$article = DB::findOne(DB::ARTICLE, array('id'=>(int)$article_id));
		if (!empty($article))
		{
			if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
			{
				if ((int)$_POST['position_id']>0)
				{
					if (($msg=self::_checkData($_POST)) !== true)
						return array(ERROR=>array('message' => $msg));
					$data['title'] = $_POST['title'];
					$data['author'] = $_POST['author'];
					$data['guide'] = $_POST['guide'];
					$data['img'] = $_POST['img'];	
					$data['reason'] = $_POST['reason'];
					
					$data['user_id'] = $_SESSION['user']['id'];
					$data['user_type'] = $_SESSION['user']['type'];
					$data['article_id'] = $article['id'];
					$data['position_id'] = (int)$_POST['position_id'];
					$data['author_id'] = $article['author_id'];
					$data['url'] = $article['url'];
					$data['sort'] = 0;
					$data['add_time'] = time();
					if (DB::insert(DB::ARTPOS, $data))
					{
						return SUCCESS;	
					}
				}
			}
			else
			{
				$data['title'] = $article['title'];
				$data['author'] = $article['author'];
				$data['guide'] = $article['guide'];
				$data['img'] = $article['img'];
				return array(SHOW=>array('template'=>'add_edit'), array_merge(array(
						'submited' 		=> SYS_TIME,
						'_title'		=> 'title',
						'_action'   	=> Url::mkurl('artpos', 'add', '', $article_id),  
					), $data)
				);
			}
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
			$data['title'] = $_POST['title'];
			$data['author'] = $_POST['author'];
			$data['guide'] = $_POST['guide'];
			$data['img'] = $_POST['img'];	
			$data['reason'] = $_POST['reason'];
			if (DB::update(DB::ARTPOS, $data, array('id'=>$id)))
			{
				return SUCCESS;	
			}
		}
		else
		{
			$data = DB::findOne(DB::ARTPOS, array('id'=>$id));
			if (!empty($data))
			{
				return array(SHOW=>array('template'=>'add_edit'), array_merge(array(
						'submited' 		=> SYS_TIME,
						'_title'		=> 'title',
						'_action'   	=> Url::mkurl('artpos', 'edit', '', $id),  
					), $data)
				);
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
		if (count($ids) != DB::count(DB::TAG, 'id in ('.implode(',', $ids).')'))
		{
			return array(ERROR=>array('message'=>Lang::ILLEGAL_OPERATE));	
		}
		foreach ($ids as $id)
		{
			if (!DB::delete(DB::ARTPOS, array('id'=>$id), 1))
			{
				return ERROR;	
			}
		}
		return SUCCESS;	
	}
		
}


?>