<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo BASE_URL;?>" />
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title><?php echo $_title;?></title>
</head>

<body>
<table>
<form action="<?php echo $_action;?>" method="post" target="_self" name="article">
<input type="hidden" name="submited" value="<?php echo $submited;?>" />
<tr>
<td>标题</td>
<td><input type="text" name="title" value="<?php echo isset($title) ? $title : '';?>" /></td>
</tr>
<tr>
<td>作者</td>
<td><input type="text" name="author" value="<?php echo isset($author) ? $author : '';?>" /></td>
</tr>
<tr>
<td>栏目</td>
<td><select name="category_id"><?php echo Tree::groupOpts(DB::CATEGORY, isset($category_id) ? $category_id : 0);?></select></td>
</tr>
<tr>
<td>导读</td>
<td><textarea name="guide" rows="5" cols="25"><?php echo isset($guide) ? $guide : '';?></textarea></td>
</tr>
<tr>
<td>内容</td>
<td><textarea name="content" rows="10" cols="25"><?php echo isset($content) ? $content : '';?></textarea></td>
</tr>
<tr>
<td>图片</td>
<td><input type="text" value="<?php echo isset($img) ? $img : ''; ?>" name="img" readonly="readonly" /><input type="button" value="选择" onclick="" /></td>
</tr>
<tr>
<td>标签</td>
<td><input type="text" value="" name="tags" /></td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="提交" /></td>
</tr>
</form>
</table>
</body>
</html>