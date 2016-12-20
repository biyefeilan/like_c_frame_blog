<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo BASE_URL;?>" />
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title><?php echo $_title;?></title>
</head>

<body>
<table>
<form action="<?php echo $_action;?>" method="post" target="_self" name="pag">
<input type="hidden" name="submited" value="<?php echo $submited;?>" />
<tr>
<td>标题</td>
<td><input type="text" name="name" value="<?php echo isset($name) ? $name : '';?>" /></td>
</tr>
<tr>
<td>地址</td>
<td><input type="text" name="url" value="<?php echo isset($url) ? $url : '';?>" /></td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="提交" /></td>
</tr>
</form>
</table>
</body>
</html>