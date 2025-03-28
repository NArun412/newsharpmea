<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
  <div class="centered2">
  <?php if(validate_user()) $this->load->view("includes/manage"); ?>
    <h1 class="with-border"><?php echo lang("News & Events"); ?></h1>
    <div class="span">
      <div class="third-quarter">
        <?php foreach($news as $article) {
				$link = route_to("news/details/".$article['id']);
				?>
        <div class="news-item">
          <div>
            <?php if(!empty($article['image'])) {?>
            <div class="img addImgEffect"> <a title="<?php echo $article['title']; ?>" href="<?php echo $link; ?>"><img src="<?php echo dynamic_img_url(); ?>news/gallery/770x485/<?php echo $article['image']; ?>" alt="<?php echo $article['title']; ?>" title="<?php echo $article['title']; ?>" /></a> </div>
            <?php }?>
            <div class="info <?php if(empty($article['image'])) {?>full<?php }?>">
              <div class="date"><?php 
			    if($this->lang->lang() != 'ar')
		  echo dateformat($article['date']); 
		  else
		  echo replace_month_to_ar(dateformat($article['date'],'M d,Y'));
			  
			  ?>
               
              </div>
              <h2><a title="<?php echo $article['title']; ?>" href="<?php echo $link; ?>"><?php echo $article['title']; ?></a></h2>
              <div class="description span"><?php echo character_limiter($article['brief'],300); ?></div>
              <a title="<?php echo $article['title']; ?>" href="<?php echo $link; ?>" class="read-more"><?php echo lang("Read more"); ?></a> </div>
          </div>
        </div>
        <?php }?>
        <?php if($this->pagination->create_links() != '') {?>
        <div class="pagination-box"><?php echo $this->pagination->create_links(); ?></div><?php }?>
      </div>
      <div class="third">
        <?php $this->load->view("content/news-rightsidebar"); ?>
      </div>
    </div>
  </div>
</div>
