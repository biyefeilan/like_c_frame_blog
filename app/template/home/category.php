<?php include "header.php";?>
<div class="container">
	<div class="main-banner">
		<div id="slides"><?php echo View::artSlides(isset($cat_id) ? $cat_id : 0);?></div>
	</div> 
	<div class="box">
    	<div class="border-top">
        	<div class="border-right">
            	<div class="border-bot">
                	<div class="border-left">
                        <div class="left-top-corner">
                           <div class="right-top-corner">
                              <div class="right-bot-corner">
                                 <div class="left-bot-corner">
                                    <div class="inner">
                                       <h2>Proud to Be a Partner</h2>
                                       <p>Nothing is as good at showing your company¡¯s advantages as a nice list of partners. Below is a perfect way to present this list - with partners¡¯ company names, logos and short desriptions.</p>
                                       <ul class="list2">
<?php foreach ($articles as $article) { ?>
									   <li>
                                           <img alt="<?php echo $article['title'];?>" src="<?php echo $article['img'];?>" />
                                           <h4><strong><?php echo $article['title'];?></strong></h4>
                                           <?php echo $article['description'];?>
                                       </li>
<?php } ?>
                                       </ul>
                                  	</div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
            	</div>
        	</div>
    	</div> 
	</div>
</div>
<?php include "footer.php";?>