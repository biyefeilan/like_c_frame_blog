<style type="text/css">
#pages_list{
	font-size: 12px;
	font-family: Verdana, Geneva, sans-serif;
}
#pages_list .pages_intro{
	float:left;
}
#pages_list a{
	text-decoration: none;
	float: left;
	border: 1px solid #999;
	margin: 2px;
	padding: 2px;	
	color: #333;
}
#pages_list a:hover{
	background-color: #333;
	color: #fff;	
}
#pages_list .page_now{
	font-weight: bold;
	color: #000;	
}
</style>
<div id="pages_list">
	<span class="pages_intro">��<?php echo $page_now;?>ҳ, ��<?php echo $pages_count;?>ҳ, <?php echo $records_count;?>����¼</span>
    <?php foreach($links as $k=>$v) { ?>
		<a href="<?php echo $v;?>" target="_self"><span class="<?php echo $k==$page_now ? 'page_now' : '';?>"><?php echo $k;?></span></a>	
	<?php }?>
</div>