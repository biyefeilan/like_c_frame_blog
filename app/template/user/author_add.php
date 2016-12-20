<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo BASE_URL;?>" />
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>用户注册</title>
</head>

<body>
<form id="user_add" name="user_add" method="post" action="<?php echo $_action;?>">
<input type="hidden" name="submited" value="<?php echo $submited;?>" />
    用户:<input type="text" name="pseudonym" /><br /> 
    密码:<input type="text" name="password" /><br /> 
    邮件:<input type="text" name="email" /><br />
  		<input type="submit" name="submit" value="添加" />
</form>
</body>
</html>
