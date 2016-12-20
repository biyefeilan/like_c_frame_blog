<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title><?php echo $_title;?></title>
</head>

<body>
<table>
<form action="<?php echo $_action;?>" method="post" target="_self" name="tag">
<input type="hidden" name="submited" value="<?php echo $submited;?>" />
<tr>
<td>名称</td>
<td><input type="text" name="name" value="<?php echo isset($name) ? $name : '';?>" /></td>
</tr>
<tr>
<td>文件夹</td>
<td><input type="text" name="folder" value="<?php echo isset($folder) ? $folder : '';?>" /></td>
</tr>
<tr>
<td>父栏目</td>
<td><select name="parent_id"><?php echo Tree::options(DB::CATEGORY,  isset($parent_id) ? $parent_id : 0);?></select></td>
</tr>
<tr>
<td>显示</td>
<td><select name="display">
		<option value="1"<?php echo isset($display)&& $display==1 ? ' selected="selected"' : '';?>>是</option>
        <option value="0"<?php echo isset($display)&& $display==0 ? ' selected="selected"' : '';?>>否</option></select></td>
</tr>
<tr>
<td>排序</td>
<td><input type="text" name="sort" value="<?php echo isset($sort) ? $sort : '0';?>" /></td>
</tr>
<?php if (user::_isSuperAdmin()) {?>
<tr>
<td>系统</td>
<td><select name="system"><option value="1"<?php echo isset($system)&& $system==1 ? ' selected="selected"' : '';?>>是</option>
        <option value="0"<?php echo isset($system)&& $system==0 ? ' selected="selected"' : '';?>>否</option></select></td>
</tr>
<?php }?>
<tr>
<td>描述</td>
<td><textarea name="description" rows="5" cols="25"><?php echo isset($description) ? $description : '';?></textarea></td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="提交" /></td>
</tr>
</form>
</table>
</body>
</html>