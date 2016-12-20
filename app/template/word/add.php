<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title>文章留言</title>
</head>

<body>
<table>
<form action="<?php echo $_action;?>" method="post" target="_self" name="tag">
<input type="hidden" name="submited" value="<?php echo $submited;?>" />
<input type="hidden" name="article_id" value="<?php echo $article_id?>" />
<tr>
<td>内容</td>
<td><textarea name="content" rows="5" cols="25"></textarea></td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="提交" /></td>
</tr>
</form>
</table>
</body>
</html>