<?php

class user {
	
	public static function _init()
	{
		return true;
	}
	
	public static function _destroy()
	{
		return true;
	}
	
	public static function index() {
		if (isset ( $_SESSION ['user']['name'] ))
			return SHOW;
		else
			return DIRECT;
	}
	
	public static function _checkData(&$data, $admin=0)
	{
		if ($admin == 0)
		{
			if (empty($data['pseudonym']))
			{
				return Lang::NOEMPTY;
			}
			
			if (empty($data['password']))
				return Lang::NOEMPTY;
				
			if (!empty($data['email']) && DB::exists(DB::ADMIN, array('email'=>$_POST['email'])))
			{
				return Lang::EXISTS;	
			}	
				
		}
		else if ($admin == 1)
		{
			if (empty($data['username']))
			{
				return Lang::NOEMPTY;
			}
			else if (DB::exists(DB::ADMIN, array('username'=>$_POST['username'])))
			{
				return Lang::EXISTS;
			}
			
			if (empty($data['password']))
				return Lang::NOEMPTY;
			
			if (!empty($data['email']) && DB::exists(DB::ADMIN, array('email'=>$_POST['email'])))
			{
				return Lang::EXISTS;	
			}	
				
			if (empty($data['department_id']))
				return Lang::NOEMPTY;
				
			if (($info=DB::findOne(DB::DEPARTMENT, array('id'=>$data['department_id']), null, 'name'))===false)
			{
				return Lang::NOTFOUND;	
			}
			else
			{
				$_POST['department'] = $info['name'];	
			}
		
			return true;
		}
		else
		{
			
		}	
		return false;
	}
	
	public static function add() 
	{
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME)
		{
			if (!self::_isLogin())
			{
				//普通用户注册
				if (($msg=self::_checkData($_POST, 0)) !== true)
					return array(ERROR=>array('message' => $msg));
					
				$_POST['reg_time'] = time();
				$_POST['status'] = 2;
				$_POST['rank_id'] = 0;
				
				if (DB::insert(DB::USER, $_POST))
				{
					return SUCCESS;	
				}
				else
				{
					return ERROR;	
				}
			}
			else if (self::_isLogin() && $_SESSION['user']['type']<2)
			{
				//添加管理员
				if (($msg=self::_checkData($_POST, 1)) !== true)
					return array(ERROR=>array('message' => $msg));
				
				$_POST['add_time'] = time();
				$_POST['admin_id'] = $_SESSION['user']['id'];
				
				if (DB::insert(DB::ADMIN, $_POST))
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
				
			}	
		}
		else
		{
			if (!self::_isLogin())
			{
				return array(
					SHOW => array('template'=>'author_add'),
					array(
						'submited'=> SYS_TIME,
						'_action' => Url::mkurl('user', 'add'),
					),
				);
			}
			else if (self::_isLogin() && $_SESSION['user']['type']<2)
			{
				return array(
					SHOW => array('template'=>'admin_add'),
					array(
						'submited'	=> SYS_TIME,
						'_action' 	=> Url::mkurl('user', 'add')
					),
				);
			}
			else
			{
				
			}	
		}
	}
	
	public static function edit() {
		$_POST ['user_modify_time'] = date ( 'Y-m-d H:i:s', time () );
		if (Db::update ( $_POST ))
			return SUCCESS;
		return ERROR;
	}
	
	public static function _isSuperAdmin()
	{
		return isset( $_SESSION ['user'] ) && $_SESSION['user']['type'] == 0;
	}
	
	public static function _isAdmin()
	{
		return isset( $_SESSION ['user'] ) && $_SESSION['user']['type'] < 2;	
	}
	
	public static function _isLogin() {
		return isset( $_SESSION ['user'] );
	}
	
	public static function login($type) 
	{
		if (user::_isLogin ())
		{
			return array (JUMP => array ('title' => Lang::USER_LOGINED, 'message' => Lang::USER_LOGINED, 'link' => Url::mkurl('index', 'index') ) );	
		}
		
		if (isset($_POST['submited']) && $_POST['submited'] == PREV_TIME && isset($_POST['password']) && (isset($_POST['username']) || isset($_POST['email'])))
		{
			if (!isset($_POST['verify']) || !isset($_SESSION['verify']) || $_SESSION['verify'] != md5($_POST['verify']) )
				return ERROR;
			
			//author
			if ($_POST['author'] == '1')
			{
				
				if (($data = DB::findOne ( DB::USER, $_POST )) !== false)
				{
					//status低4位中最高位为0表示不在线，为1表示在线
					//中间位为0表示已验证，为1表示未验证
					//最低位为0表示验证通过，为1表示验证为通过，参考中间位
					//disabled 0 正常 1 自己注销 2 被管理员禁用
					if ($data['disabled'] == 1)
					{
						//提示是否开启账户
						return array(SHOW=>array('template'=>''));	
					}
					else if ($data['disabled'] == 2)
					{
						//提示被禁用
						return array(ERROR);
					}
					else if ($data['disabled'] != 0)
					{
						//提示状态异常
						return array(ERROR);
					}
					
					$_SESSION ['user']['name'] = $data ['pseudonym'];
					$_SESSION ['user']['id'] = $data ['id'];
					$_SESSION ['user']['type'] = 2;
					$_SESSION ['user']['login_time'] = time();
					$_SESSION ['user']['status'] = $data['status'];
					DB::update(DB::USER, array('status'=>((int)$_SESSION['user']['status'] | 4)), array('id'=>$_SESSION['user']['id']));
					return DIRECT;
					/*
					if ($data['status'] == 0)
					{
						$_SESSION ['user']['name'] = $data ['pseudonym'];
						$_SESSION ['user']['id'] = $data ['id'];
						$_SESSION ['user']['type'] = 2;
						$_SESSION ['user']['login_time'] = time();
						DB::update(DB::USER, array('status'=>((int)$_SESSION['user']['status'] | 4)), array('id'=>$_SESSION['user']['id']));
						return DIRECT;
					}
					else if ($data['status'] == 1)
					{
						return array(JUMP=>array('title'=>Lang::TIPS, 'message'=>Lang::CHECK .Lang::FAIL));	
					}
					else if ($data['status'] == 2)
					{
						return array(SUCCESS=>array('message'=>'please check first'));	
					}
					else if ($data['status'] == 7)
					{
						return array(ERROR=>array('title'=>Lang::TIPS, 'message'=>Lang::NO . Lang::REPEAT . Lang::LOGIN));	
					}
					
					return ERROR;
					*/
				}
			}
			//admin
			else if ($_POST['author'] == '0')
			{
				if (($data = DB::findOne ( DB::ADMIN, $_POST )) !== false)
				{
					$_SESSION ['user']['name'] = $data ['username'];
					$_SESSION ['user']['id'] = $data ['id'];
					$_SESSION ['user']['login_time'] = time();
					if ($data['admin_id']== '0')
					{
						$_SESSION ['user']['type'] = 0;
					}
					else
					{
						$_SESSION ['user']['type'] = 1;	
					}
					
					return DIRECT;
				}
			}
			else
			{
				return ERROR;	
			}
			
			return array (ERROR => array ('message' => Lang::USERNAME_OR_PASSWORD_WORNG ) );
		}
		else
		{
			if (!isset($type) || $type!='admin')
				return array(SHOW, array('submited'=>SYS_TIME));	
			else
				return array(SHOW=>array('template'=>'admin_login'), array('submited'=>SYS_TIME));
		}
	}
	
	public static function loginOut() {
		if (isset ( $_SESSION ['user'])) 
		{
			//登录信息记录
			$data  = array(
				'login_time' 	=> $_SESSION['user']['login_time'],
				'loginout_time' => time(),
				'login_ip'		=> _C_Router::getClientIp(),
				'login_addr' 	=> _C_Router::getClientIp(),
				'user_type'		=> $_SESSION['user']['type'],
				'user_id'		=> $_SESSION['user']['id'],	
			);
			DB::insert(DB::LOGINLOG, $data);
			if ($_SESSION['user']['type'] == 2)
			{
				//普通用户登出
				DB::update( DB::USER, array('login_times'=>'login_times+1', 'status'=>((int)$_SESSION['user']['status'] & 0x73)), array('id'=>$_SESSION['user']['id']));
			}
			
			unset ( $_SESSION );
			_C_Session::destroy ();
			return array (JUMP => array ('link' => Url::mkurl('index', 'index'), 'title' => Lang::LOGIN_OUT, 'message' => Lang::LOGIN_OUT_MESSAGE ) );
		}
		return array (ERROR => array ('message' => Lang::PLASE_LOGIN_FIRST ) );
	}
	
	private function __construct() {
	}
	
	private function __clone() {
	}
}

?>