<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo BASE_URL;?>" />
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>用户添加</title>
</head>

<body>
<form id="user_login" name="user_login" method="post" action="<?php echo Url::mkurl('user', 'login');?>">
    <input type="hidden" name="submited" value="<?php echo $submited;?>" />
    <input type="hidden" name="author" value="0" />
    用户名:<input type="text" name="username" /><br /> 密码:<input type="text"
        name="password" /><br /> 
    <!-- 验证码:<input type="text" name="check_code" /><img
        src="" /><br /> --> 
    <input type="submit" name="submit" value="登录" />
</form>
</body>
</html>
