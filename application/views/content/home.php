<?php if(!empty($boxes)) {
	//print '<pre>'; print_r($boxes); exit;
	?>
<div class="span">
<div class="home-categories">
<div class="home-categories-container">
<?php $i=0;
foreach($boxes as $box) {
	$categories = $box['categories'];
	if(!empty($categories)) { 
	$link = route_to("products/index/".$box['id']); 
	$i++;
	?>
<div class="half-side">
    	<div class="head-title with-border center">
        	<a title="<?php echo $box['title']; ?>" href="<?php echo $link; ?>"><?php echo $box['title']; ?></a>
        </div>
        	<?php 
			if(!empty($categories)) {?>
               <div class="span">
            <?php
				$j = 0;
				$c = count($categories);
			foreach($categories as $cat) {$j++;
				$link_s = route_to("products/index/".$box['id'].'/'.$cat['id']);
				$path = '560x500';
				$class = 'half';
				$img_field = '_small';
				if($cat['promote_to_home_page'] == 2) {
					$path = '980x435';
					$class = '';
					$img_field = '_wide';
					$j++;
				}
				$path = 'image'.$img_field.'/'.$path;
				?>
        		<div class="home-category-item <?php echo $class; ?> trans top-trans delay-<?php echo $i; ?>">
           	 <?php if(!empty($cat['image'.$img_field])) {?>
            <div class="img addImgEffect"> <a title="<?php echo $cat['title']; ?>" href="<?php echo $link_s; ?>"><img src="<?php echo dynamic_img_url(); ?>categories/<?php echo $path; ?>/<?php echo $cat['image'.$img_field]; ?>" alt="<?php echo $cat['title']; ?>" title="<?php echo $cat['title']; ?>" /></a> </div>
            <?php }?>
                <div class="info">
                	<h2><a title="<?php echo $cat['title']; ?>" href="<?php echo $link_s; ?>"><?php echo $cat['title']; ?></a></h2>
                    <a class="learn-more" title="<?php echo $cat['title']; ?>" href="<?php echo $link_s; ?>"><?php echo lang("view more"); ?></a>
                </div>
            </div>
            <?php if($j%2 == 0) { echo  '</div><div class="span">'; }}?>
        </div>
            <?php }?>
    </div>
<?php }}?>
</div>
</div>
</div>
<?php }?>

<div class="span home-intro">
	<div class="centered2">
	    
	    	<div class="head-title with-border center">
        	<a title="<?php echo lang("healthcare solutions"); ?>" href="<?php echo route_to("products/index/3"); ?>"><?php echo lang("healthcare solutions"); ?></a>
        </div>
        
        
    	<div class="span">
    		<div class="half-side trans left-trans">
            	<h1 class="h1-slogan"><?php echo $intro['title']; ?></h1>
                <div class="description">
                	<?php echo $intro['description']; ?>
                    
                </div><a title="<?php echo $intro['title']; ?>" href="<?php echo route_to("products/index/3"); ?>" class="read-more"><?php echo lang("learn more"); ?></a>
            </div>
        	<div class="half-side trans right-trans delay-1">
            	 <?php if(!empty($intro['image'])) {?>
            <div class="home-right-img"> <a title="<?php echo $intro['title']; ?>" href="<?php echo route_to("products/index/3"); ?>"><img src="<?php echo dynamic_img_url(); ?>website_pages/image/730x410/<?php echo $intro['image']; ?>" alt="<?php echo $intro['title']; ?>" title="<?php echo $intro['title']; ?>" /></a> </div>
            <?php }?>
            </div>
        </div>
    </div>
</div>

<?php if(!empty($latest_news)) {?>
<div class="span latest-news">
	<div class="centered1">
    	<div class="block-title case-red"><?php echo lang("latest news and events"); ?></div>
    	<div class="span" id="home-news-slider">
    	<?php $i=0;foreach($latest_news as $article) {
				$link = route_to("news/details/".$article['id']);
				?>
                <div class="item">
        <div class="latest-news-item  trans top-trans delay-<?php echo $i; ?>">
                	<div>
                         <?php if(!empty($article['image'])) {?>
                   <div class="img addImgEffect"> <a title="<?php echo $article['title']; ?>" href="<?php echo $link; ?>"><img src="<?php echo dynamic_img_url(); ?>news/gallery/770x485/<?php echo $article['image']; ?>" alt="<?php echo $article['title']; ?>" style="height: 200px" title="<?php echo $article['title']; ?>" /></a> </div> 	
                   <?php } ?>
             <?php if(!empty($article['image'])) {?>
            
            <?php }?>
                        <div class="info">
                        	<div class="date"><?php 
								    if($this->lang->lang() != 'ar')
		  echo dateformat($article['date']); 
		  else
		  echo replace_month_to_ar(dateformat($article['date'],'M d,Y'));
							
							?>
                <?php if($article['date_to'] != '0000-00-00' && $article['date_to'] != ''){?>
                - <?php //echo dateformat($article['date_to'],'M d,Y'); 
				}?></div>
                            <h3><a title="<?php echo $article['title']; ?>" href="<?php echo $link; ?>"><?php echo character_limiter($article['title'],100); ?></a></h3>
                            <div class="brief span"><?php echo character_limiter(strip_tags($article['brief']),100); ?></div>
							<a title="<?php echo $article['title']; ?>" href="<?php echo $link; ?>" class="read-more"><?php echo lang("Read more"); ?></a>
                        </div>
                    </div>
                </div>
                </div>
           <?php $i++;
		   }?>     
         </div><div class="newsletter-box trans left-trans">
         		<span class="subscribe-text">
                	<span><?php echo lang('Subscribe to our mailing list'); ?></span><br /><small><?php echo lang('Stay Sharp to receive our latest news and updates'); ?></small>
                </span>
                <?php echo form_open(route_to("home/subscribe"),array("id"=>"newsletterForm")); ?>
                <div class="newsletter-form-item"><input type="text" name="newslettername" placeholder="<?php echo ucfirst(lang("name")); ?>" value="" /></div>
                <div class="newsletter-form-item"><input type="text" name="newsletteremail" placeholder="<?php echo ucfirst(lang("email")); ?>" value="" /></div>
                <div class="newsletter-form-item button"><input type="submit" value="<?php echo lang("subscribe"); ?>" /></div>
                <?php echo form_close(); ?>
                <div class="newsletter-errors"></div>
                <div class="newsletter-success">success</div>
         </div>
                
    </div>
</div>
<?php }?>