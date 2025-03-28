<?php if(!empty($up_coming_events)) {?>
<div class="span upcoming-event">
  <div class="head-title with-border"><?php echo lang("Upcoming Event"); ?></div>
  <div class="news-box">
    <div class="date"><?php
		  if($this->lang->lang() != 'ar')
		  echo dateformat($up_coming_events['date']); 
		  else
		  echo replace_month_to_ar(dateformat($up_coming_events['date']));
	
	 ?></div>
     <?php if(!empty($up_coming_events['image'])) {?>
                    	<div class="img addImgEffect">
                        	<a title="<?php echo $up_coming_events['title']; ?>" href="<?php echo route_to("news/details/".$up_coming_events['id']); ?>"><img src="<?php echo dynamic_img_url(); ?>news/gallery/770x485/<?php echo $up_coming_events['image']; ?>" alt="<?php echo $up_coming_events['title']; ?>" title="<?php echo $up_coming_events['title']; ?>" /></a>
                        </div>
                        <?php }?>
    <div class="title"><a title="<?php echo $up_coming_events['title']; ?>" href="<?php echo route_to("news/details/".$up_coming_events['id']); ?>"><?php echo $up_coming_events['title']; ?></a></div>
  </div>
</div>
<?php }?>
<?php if(!empty($popular_news)) {?>
<div class="span latest-events">
  <div class="head-title with-border"><?php echo lang("Popular"); ?></div>
  <div class="span latest-events-slider">
    <?php foreach($popular_news as $article) {
	  $link = route_to("news/details/".$article['id']);	
	?>
    <div class="news-box">
         <?php if(!empty($article['image'])) {?>
                  <div class="img addImgEffect"><a href="<?php echo $link; ?>" title="<?php echo $article['title']; ?>"><img src="<?php echo dynamic_img_url(); ?>news/gallery/770x485/<?php echo $article['image']; ?>" alt="<?php echo $article['title']; ?>" title="<?php echo $article['title']; ?>" /></a></div>
                  <?php }?>
      <div class="date"><?php 
	  	  if($this->lang->lang() != 'ar')
		  echo dateformat($article['date']); 
		  else
		  echo replace_month_to_ar(dateformat($article['date']));
	  
	  ?></div>
      <div class="title"><a href="<?php echo $link; ?>" title="<?php echo $article['title']; ?>"><?php echo $article['title']; ?></a></div>
    </div>
    <?php }?>
  </div>
  <a class="view-all" title="<?php echo lang("View All Sharp News"); ?>" href="<?php echo route_to("news"); ?>"><?php echo lang("view all"); ?></a> </div>
  <?php }?>