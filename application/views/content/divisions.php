<?php 
$cl_b = 'no-banner';
$ico = 'right';
if($this->lang->lang() == "ar") $ico = 'left';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
	<div class="centered1">
    <?php if(validate_user()) $this->load->view("includes/manage"); ?>
    <?php if(empty($pagetitle_2)) {?>
    	<h1 class="product-page-title"><?php echo $pagetitle_1; ?></h1>
   	<?php } else{?>
    	<div class="product-division"><?php echo $pagetitle_1; ?></div>
    	<h1 class="product-page-title"><?php echo $pagetitle_2; ?></h1>
    <?php }?>
    
    <?php if(isset($division['description']) && !empty($division['description'])) {?>
    	<div class="span description margin-bottom-25"><?php echo $division['description']; ?></div>
    <?php }?>
    
        <div class="span">
        	<?php 
			if(!empty($all_divisions)) {
			foreach($all_divisions as $div) {
				$link = route_to("products/index/".$div['id']);
				?>
            	<div class="product-box three">
                	<div class="img addImgEffect"><?php if(!empty($div['image'])) {?><a title="<?php echo $div['title']; ?>" href="<?php echo $link; ?>"><img src="<?php echo dynamic_img_url(); ?>divisions/image/480x290/<?php echo $div['image']; ?>" alt="<?php echo $div['title']; ?>" title="<?php echo $div['title']; ?>" /></a><?php }?></div>
                    <h2><a title="<?php echo $div['title']; ?>" href="<?php echo $link; ?>"><?php echo $div['title']; ?></a></h2>
                   <!-- <h3><?php //echo $product['category_name']; ?></h3-->
                    <a title="<?php echo $div['title']; ?>" class="learn-more" href="<?php echo $link; ?>"><?php echo lang("view more"); ?></a>
                </div>
            <?php }?>
            <?php if($this->pagination->create_links() != '') {?>
        <div class="pagination-box"><?php echo $this->pagination->create_links(); ?></div><?php }?>
            <?php
			} else {?>
            <div class="no-record-found"><?php echo lang("no divisions found..."); ?></div>
            <?php }?>
 
        </div>
    </div>
</div>