<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner product-info <?php echo $cl_b; ?>" >
  <div class="centered2">
  <?php if(validate_user()) $this->load->view("includes/manage"); ?>
   <?php $this->load->view("includes/breadcrumbs"); ?>
    <div class="span">
      <div class="product-details-right <?php if(empty($product['image'])) {?>full<?php }?>">
        <h1><?php echo $product['title']; ?></h1>
        <h4><?php 
	echo $product['category_name'];
		 ?></h4>
        <div class="description">
          <?php echo $product['description']; ?>
        </div>
      <div class="span">
		<?php if(!empty($product['country_name'])) {?>
        	<div class="country-of-manu">
            	<?php if(file_exists(img_url('absolute').'flags/'.strtolower($product['country_code']).'.png')) {?>
                	<img src="<?php echo img_url(); ?>flags/<?php echo strtolower($product['country_code']); ?>.png" />
                <?php }?>
                <?php echo lang('made in').' '.$product['country_name']; ?>
            </div>
        <?php }?>
        <?php if(isset($product['brochures']) && !empty($product['brochures'])) {?>	
        	<?php foreach($product['brochures'] as $bro) {?>
            	<div class="country-of-manu no-border">
                    <a onclick="download_file(<?php echo $bro['id']; ?>)"><span class="download-awesome"><i class="fa fa-download" aria-hidden="true"></i></span><?php echo lang('download brochure'); ?></a>
                </div>
            <?php }?>
        <?php }?>
        </div>
		<?php if(!empty($product['tags'])) {?>
        <?php foreach($product['tags'] as $tagcat) {?>
		<div class="span product-tags">
			<?php if(!empty($tagcat['tags'])) {foreach($tagcat['tags'] as $tag) {
				/*<a href="<?php echo route_to("products/tag/".$tag['id']); ?>">*/
				?>
				<span class="product-tag inline"><img src="<?php echo dynamic_img_url(); ?>products_tags/icon/80x80/<?php echo $tag['icon']; ?>" alt="<?php echo $tag['title']; ?>" title="<?php echo $tag['title']; ?>" /></span>
			<?php }}?>
		</div><?php }?>
		<?php }?>
      </div>
        <?php if(!empty($product['image']) || !empty($product['gallery'])) {?>
      <div class="product-details-left"> 
      <?php if(!empty($product['gallery'])){?>
      <div id="products-gallery">
      <?php foreach($product['gallery'] as $gal) {?>
      <div class="item">
      <img src="<?php echo dynamic_img_url(); ?>products/gallery/710x450/<?php echo $gal['image'] ?>" alt="<?php echo $gal['title']; ?>" title="<?php echo $gal['title']; ?>" /></div><?php }?></div><?php } else {?>
       <img src="<?php echo dynamic_img_url(); ?>products/image/710x450/<?php echo $product['image'] ?>" alt="<?php echo $product['title']; ?>" title="<?php echo $product['title']; ?>" />
      <?php }?>
     <!--<img src="<?php echo img_url(); ?>test/product-brand.png" alt="" title="" class="product-brand-logo" />-->
       </div>
       <?php }?>
    </div>
  </div>
</div>
<?php if( (isset($product['specifications']) && !empty($product['specifications'])) || (isset($product['technologies']) && !empty($product['technologies'])) || (isset($product['tabs']) && !empty($product['tabs'])) ) {?>
<div class="content_inner">
  <div class="centered2">
  <div class="product-menu" id="ResponsiveProductMenu" style="display:block;">
    <ul>
    <?php if(isset($product['technologies']) && !empty($product['technologies'])) {?>
     <li><a href="#tab-9999" title="<?php echo lang("description"); ?>"><?php echo lang("description"); ?></a></li>
    <?php }?>
    <?php if(isset($product['specifications']) && !empty($product['specifications'])) {?>
     <li><a href="#tab-8888" title="<?php echo lang("specifications"); ?>"><?php echo lang("specifications"); ?></a></li>
    <?php }?>
    <?php foreach($product['tabs'] as $tab) {?>
    <li><a href="#tab-<?php echo $tab['id']; ?>" title="<?php echo $tab['title']; ?>"><?php echo $tab['title']; ?></a></li>
    <?php }?>
    </ul>
        <?php if(isset($product['technologies']) && !empty($product['technologies'])) {?>
     <div class="product-tab-content" id="tab-9999">
 <?php 
 $i=0;
 foreach($product['technologies'] as $tec) {$i++;?>
 	<div class="product-technology">
    	<?php if(empty($tec['image'])) {?>
        <div class="description span"><?php 
			 echo html_entity_decode($tec['description']); 
		 ?></div>
        <?php } elseif(empty($tec['description'])) {?>
        <div class="span"><img src="<?php echo dynamic_img_url(); ?>products_technologies/image/600x500/<?php echo $tec['image']; ?>" alt="<?php echo $tec['title']; ?>" title="<?php echo $tec['title']; ?>" /></div>
        <?php } else {?>
        	<?php if($i%2 == 0) { 
			$img_cl = 'lefts';
			$desc_cl = 'rights';
			} 
			else {
				$img_cl = 'rights';
			$desc_cl = 'lefts';
		 }?>
            <div class="tech-img <?php echo $img_cl; ?>"><img src="<?php echo dynamic_img_url(); ?>products_technologies/image/600x500/<?php echo $tec['image']; ?>" alt="<?php echo $tec['title']; ?>" title="<?php echo $tec['title']; ?>" /></div>
             <div class="tech-desc <?php echo $desc_cl; ?>"><?php 
			echo html_entity_decode($tec['description']); 
			  //echo $tec['description']; 
			 ?></div>
        <?php }?>
    </div>
 <?php }?>
    </div>
    <?php }?>
       <?php if(isset($product['specifications']) && !empty($product['specifications'])) {?>
     <div class="product-tab-content" id="tab-8888"><div class="description"><?php echo $product['specifications']; ?></div></div>
        <?php }?>
<?php foreach($product['tabs'] as $tab) {?>
    <div class="product-tab-content" id="tab-<?php echo $tab['id']; ?>">
    <?php if(!empty($tab['file'])) {?>
    <div class="span">
    <a href="<?php echo route_to("products/downloadfile/".$tab['id']); ?>" class="learn-more"><?php echo lang("Download File"); ?></a></div>
    <?php }?>
   <?php if(!empty($tab['description'])) {?>
    	<div class="span">
		<div class="description"><?php echo $tab['description']; ?></div>
        </div><?php }?>
    </div>
<?php }?>
  </div>  
  </div>
</div>
<?php }?>
<?php if(!empty($similar_products)) {?>
<div class="span may-also-like">
  <div class="block-title"><?php echo lang("You May Also Like"); ?></div>
  <div class="span">
    <div class="may-also-like-slider">
      <?php foreach($similar_products as $product) {
			$link = route_to("products/details/".$product['id']);
		?>
      <div class="item">
        <div class="product-box">
          	<div class="img addImgEffect"><?php if(!empty($product['image'])) {?><a title="<?php echo $product['title']; ?>" href="<?php echo $link; ?>"><img src="<?php echo dynamic_img_url(); ?>products/image/710x450/<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" title="<?php echo $product['title']; ?>" /></a><?php }?></div>
          <h3><a href="<?php echo $link; ?>"><?php echo $product['title']; ?></a></h3>
          <h4><?php
          echo $product['category_name']; ?></h4>
        </div>
      </div>
      <?php }?>
    </div>
  </div>
</div>
<?php }?>