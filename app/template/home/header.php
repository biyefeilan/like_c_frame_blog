<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo isset($_title) ? $_title : 'VIVINICE.COM';?></title>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<meta name="description" content=""/>
<link href="<?php echo CSS_URL;?>vivinice.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo JS_URL;?>jq/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo JS_URL;?>jq/slides/slides.min.jquery.js"></script>
<style type="text/css">
.container {width:950px; margin:0 auto; /*border:1px solid red;*/}

#header {height:194px; /*border:1px solid #000;*/}
#header, #main, #extra, #footer { font-size:0.75em;}

#main .col-1 {width:640px;margin-right:10px;float:left;}
#main .col-2 {width:300px;float:left;}

#footer {background:url(<?php echo IMG_URL;?>footer-bg.gif) left top repeat-x #717171;}
</style>

</head>

<body>
   <!-- header -->
   <div id="header">
      <div class="container">
         <div class="row-1">
            <div class="logo"><a href="index.html"><img alt="" src="<?php echo IMG_URL;?>logo.jpg" /></a></div>
            <ul class="top-links">
               <li><a href="index.html"><img alt="" src="<?php echo IMG_URL;?>top-icon1.jpg" /></a></li>
               <li><a href="#"><img alt="" src="<?php echo IMG_URL;?>top-icon2.jpg" /></a></li>
               <li><a href="contact-us.html"><img alt="" src="<?php echo IMG_URL;?>top-icon3.jpg" /></a></li>
            </ul>
         </div>
         <div class="row-2">
         	<!-- nav box begin -->
            <div class="nav-box">
            	<div class="left">
               	<div class="right">
                  	<ul>
                     	<?php 
                     		$cats = array_merge(array(array('id'=>0, 'name'=>'首页')), DB::findAll(DB::CATEGORY, array('parent_id'=>'0', 'display'=>'1'), array('sort'=>'DESC'), 'id,name,folder', '5'));
                     		$format = '<li><a href="%s"%s><em><b>%s</b></em></a></li>';
                     		$cats_key_end = count($cats) - 1;
                     		$curr_cat_id = isset($cat_id) ? $cat_id : 0;
                  
                     		foreach ($cats as $k=>$cat) {
								$class = '';
								if ($k == 0)
								{
									if ( $curr_cat_id == $cat['id'] )
										$class = 'class="first-current"';
									else
										$class = 'class="first"';
								}
								else if ($k == $cats_key_end)
								{
									if ($curr_cat_id == $cat['id'])
										$class = 'class="last-current"';
									else 
										$class = 'class="last"';
								}
								else if ($curr_cat_id == $cat['id'])
								{
									$class = 'class="current"';
								}
								echo sprintf($format, $cat['id']==0 ? '/' : Url::mkurl('home', 'cat', '', $cat['folder']), $class, $cat['name']);
							}
                     	?>
                        <!-- 
                     	<li><a href="/" class="first"><em><b>首页</b></em></a></li>
                        <li><a href=""><em><b>散文</b></em></a></li>
                        <li><a href="solutions.html"><em><b>小说</b></em></a></li>
                        <li><a href="partners.html"><em><b>故事</b></em></a></li>
                        <li><a href="consulting.html"class="current"><em><b>杂文</b></em></a></li>
                        <li><a href="contact-us.html" class="last"><em><b>诗歌</b></em></a></li>
                     	-->
                     </ul>
                  </div>
               </div>
            </div>
            <!-- nav box end -->
         </div>
      </div>
   </div>
<div id="main">