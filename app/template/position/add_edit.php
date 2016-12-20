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
<td>推荐位</td>
<td><input type="text" name="name" value="<?php echo isset($name) ? $name : '';?>" /></td>
</tr>
<tr>
<td>页面</td>
<td><select name="page_id" onchange="document.getElementById('page_name').value=this.options[this.selectedIndex].text;">
<?php foreach(DB::findAll(DB::PAG, null, null, 'id, name') as $v){?>
<option value="<?php echo $v['id'];?>"<?php echo isset($page_id) && $page_id==$v['id'] ? ' selected="selected"' : '';?>><?php echo $v['name'];?></option>
<?php }?>
<input type="hidden" name="page_name" id="page_name" value="<?php echo isset($page_name) ? $page_name : '网站首页';?>" />
</select></td>
</tr>
<tr>
<td>描述</td>
<td><textarea name="description" rows="10" cols="25"><?php echo isset($description) ? $description : '';?></textarea></td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="提交" /></td>
</tr>
</form>
</table>
</body>
</html>