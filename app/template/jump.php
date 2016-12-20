<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo BASE_URL;?>" />
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><?php echo $title;?></title>
<script type="text/javascript">
var time = '<?php echo $time;?>';
var time_stay = parseInt(time) || 3;
time_stay++;
function count_time()
{
	$('time_show').innerHTML = --time_stay;
	if (time_stay==0)
	{
		window.location.href = '<?php echo $link;?>';
	}
	else
	{
		setTimeout(count_time, 1000);
	}
}

function $(id)
{
	return document.getElementById(id);
}
window.onload = function(){count_time();}
</script>
</head>

<body>
	<h1><?php echo $title;?></h1>
	<div><?php echo $message;?></div>
	<div>
		<span id="time_show"></span>秒后跳转，或者直接点<a href="<?php echo $link;?>"
			target="_self">这里</a>
	</div>
</body>
</html>

