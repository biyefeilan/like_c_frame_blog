<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title>管理员添加</title>
</head>

<body>
<table>
<form action="<?php echo $_action;?>" method="post" target="_self" name="admin">
<input type="hidden" name="submited" value="<?php echo $submited;?>" />
<tr>
<td>用户</td>
<td><input type="text" name="username" value="" /></td>
</tr>
<tr>
<td>密码</td>
<td><input type="text" name="password" value="" /></td>
</tr>
<tr>
<td>邮箱</td>
<td><input type="text" name="email" value="" /></td>
</tr>
<tr>
<td>部门</td>
<td><select name="department_id"><?php echo Tree::options(DB::DEPARTMENT, isset($department_id) ? $department_id : 0);?></select></td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="提交" /></td>
</tr>
</form>
</table>
</body>
</html>