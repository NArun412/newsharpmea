<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
  <div class="centered2">
  <?php if(validate_user()) $this->load->view("includes/manage"); ?>
    <div class="head-title with-border"><?php echo lang("Latest News"); ?></div>
    <div class="span">
      <div class="third-quarter">
        <div class="news-article">
          <div class="span relative">
            <?php if(!empty($article['gallery'])) {?>
            <div class="news-gallerys-slider">
              <?php foreach($article['gallery'] as $gal){
								if(!empty($gal['image'])) {
								?>
              <div class="img"><img src="<?php echo dynamic_img_url(); ?>news/gallery/770x485/<?php echo $gal['image']; ?>" alt="<?php echo $gal['title']; ?>" title="<?php echo $gal['title']; ?>" /></div>
              <?php }?><?php }?>
            </div>
            
            <!--<div class="overlay trigger-news-slider-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>-->
            <?php }?>
          </div>
          <div class="date"><?php 
		  if($this->lang->lang() != 'ar')
		  echo dateformat($article['date']); 
		  else
		  echo replace_month_to_ar(dateformat($article['date']));
		  ?>
           
          </div>
          <h1><?php echo $article['title']; ?></h1>
          <div class="description span"><?php echo $article['description']; ?></div>
        </div>
        <a class="back with-border" onclick="GoBackWithRefresh()"><span><?php echo lang("back"); ?></span></a>
        <?php if(!empty($related_news)) {?>
        <div class="span">
          <div class="related-news">
            <div class="related-news-slider">
              <?php foreach($related_news as $article) {
				  $link = route_to("news/details/".$article['id']);
				  ?>
              <div class="item">
                <div class="news-box">
                  <?php if(!empty($article['image'])) {?>
                  <div class="img addImgEffect"><a href="<?php echo $link; ?>" title="<?php echo $article['title']; ?>"><img src="<?php echo dynamic_img_url(); ?>news/gallery/770x485/<?php echo $article['image']; ?>" alt="<?php echo $article['title']; ?>" title="<?php echo $article['title']; ?>" /></a></div>
                  <?php }?>
                  <div class="date"><?php   if($this->lang->lang() != 'ar')
		  echo dateformat($article['date']); 
		  else
		  echo replace_month_to_ar(dateformat($article['date'])); ?></div>
                  <div class="title"><a href="<?php echo $link; ?>" title="<?php echo $article['title']; ?>"><?php echo character_limiter($article['title'],100,'...'); ?></a></div>
                  <a href="<?php echo $link; ?>" title="<?php echo $article['title']; ?>" class="read-more-1"><?php echo lang("Read more"); ?></a> </div>
              </div>
              <?php }?>
            </div>
          </div>
        </div>
        <?php }?>
      </div>
      <div class="third">
        <?php $this->load->view("content/news-rightsidebar"); ?>
      </div>
    </div>
  </div>
</div>
