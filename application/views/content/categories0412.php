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
    
    <?php if(isset($category['description']) && !empty($category['description'])) {?>
    	<div class="span description margin-bottom-25"><?php echo $category['description']; ?></div>
    <?php }?>
    
        <div class="span">
        	<?php 
			if(!empty($categories)) {
			foreach($categories as $category) {
				//$link = route_to("products/index/".$division['id']."/".$product['id']);
				$link = "products/index";
				$ids_arr = array($category['id']);
				$parents = array();
				$cat = $this->db->where("id_categories",$category['id'])->get("categories")->row_array();
				$div_id = $division['id'];
				$parent = $this->db->where("id_categories",$cat['id_parent'])->get("categories")->row_array();
				while(!empty($parent)) {
					$div_id = $parent['id_divisions'];
					array_push($ids_arr,$parent['id_categories']);
					$parent = $this->db->where("id_categories",$parent['id_parent'])->get("categories")->row_array();
				}
				$link .= '/'.$div_id;
				for($i = (count($ids_arr) - 1);$i >= 0;$i--) $link .= '/'.$ids_arr[$i];
				$link = route_to($link);
				?>
            	<div class="product-box four">
                	<div class="img addImgEffect"><?php if(!empty($category['image_small'])) {?><a title="<?php echo $category['title']; ?>" href="<?php echo $link; ?>"><img src="<?php echo dynamic_img_url(); ?>categories/image_small/560x500/<?php echo $category['image_small']; ?>" alt="<?php echo $category['title']; ?>" title="<?php echo $category['title']; ?>" /></a><?php }?></div>
                    <h2><a title="<?php echo $category['title']; ?>" href="<?php echo $link; ?>"><?php echo $category['title']; ?></a></h2>
                   <!-- <h3><?php //echo $category['category_name']; ?></h3-->
                    <a title="<?php echo $category['title']; ?>" class="learn-more" href="<?php echo $link; ?>"><?php echo lang("view more"); ?></a>
                </div>
            <?php }?>
            <?php if($this->pagination->create_links() != '') {?>
        <div class="pagination-box"><?php echo $this->pagination->create_links(); ?></div><?php }?>
            <?php
			} else {?>
            <div class="no-record-found"><?php echo lang("no categories found..."); ?></div>
            <?php }?>
 
        </div>
    </div>
</div>