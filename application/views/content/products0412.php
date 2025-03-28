<?php 
$cl_b = 'no-banner';

$ico = 'right';
if($this->lang->lang() == "ar") $ico = 'left';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
	<div class="centered2">
    <?php if(validate_user()) $this->load->view("includes/manage"); ?>
    <?php if(empty($pagetitle_2)) {?>
    	<h1 class="product-page-title"><?php echo $pagetitle_1; ?></h1>
   	<?php } else{?>
    	<div class="product-division"><?php echo $pagetitle_1; ?></div>
    	<h1 class="product-page-title"><?php echo $pagetitle_2; ?></h1>
    <?php }?>
        <div class="span">
        <div class="products-left">
        	<div class="quick-search-title"><?php echo lang("quick search"); ?></div>
            <div class="quick-search-form">
            <?php echo form_open($url,array("method"=>"GET","id"=>"product_search_left")); 
			echo form_hidden($this->security->get_csrf_token_name(),$this->security->get_csrf_hash());
			?>
              <div class="form-item full">
              <!--  	<label><?php echo lang("division"); ?></label>-->
                     <!--<select name="division" onchange="changeDivision(this)">
                    	<option value="">- <?php echo lang("select"); ?> -</option>
                        <?php //foreach($all_divisions as $rec) {
							//$cl = '';
							//if(isset($division['id']) && $rec['id'] == $division['id']) $cl = 'selected="selected"';
							?>
                        <option value="<?php //echo $rec['id']; ?>" <?php //echo $cl; ?>><?php //echo $rec['title']; ?></option>
                        <?php //}?>
                     </select>-->
                     <ul class="left-menu">
                     <?php foreach($all_divisions as $rec) {
						 	$cl = '';
							if(isset($division['id']) && $rec['id'] == $division['id']) $cl = 'checked="checked"';
					 ?>
                     	<li><label><a onchange="changeDivision(this)" <?php if(isset($division['id']) && $rec['id'] == $division['id']) echo 'class="active"'; ?>><input type="radio" name="division" value="<?php echo $rec['id']; ?>" <?php echo $cl; ?> /> <?php echo $rec['title']; ?></a></label></li>
                     <?php }?>
                     </ul>
                     <!--<input type="hidden" name="division" value="<?php //if(isset($division['id'])) echo $division['id']; ?>" />-->
                </div>
                <div class="span" id="categories-filtration">
                    <?php 
					if(isset($categories_dropdowns) && !empty($categories_dropdowns)) {
						foreach($categories_dropdowns as $k => $cat) {
							if(!empty($cat)) {
								$this->load->view("content/categories-drop-down",array(
									'all_categories'=>$cat,
									'cid'=>$k,
									'selected_categories'=>$selected_categories,
								));
							}
						}
						//if(!isset($all_categories)) $all_categories = array();
					}?>
                </div>
                
                <!--<div class="form-item full">
                	<label><?php //echo lang("product line"); ?></label>
                    <select name="line">
                    	<option value="">- <?php //echo lang("select"); ?> -</option>
                        <?php //if(isset($all_products_lines) && !empty($all_products_lines)) {?>
                        <?php //foreach($all_products_lines as $rec) {
							//$cl = '';
							//if(isset($line['id']) && $rec['id'] == $line['id']) $cl = 'selected="selected"';
							?>
                        <option value="<?php //echo $rec['id']; ?>" <?php //echo $cl; ?>><?php //echo $rec['title']; ?></option>
                        <?php //}?>
                        <?php //}?>
                    </select>
                </div>-->
            
            <?php if(!empty($left_tags)) {
				$parm_tags = $this->input->get('tag');
				foreach($left_tags as $tag) {
					if(!empty($tag['options'])) {?>
                <div class="form-item full">
                	<label><?php echo get_cell_translate('products_tags','label','id_products_tags',$tag['id']); ?></label>
                    <select name="tag[<?php echo $tag['id']; ?>]">
                    	<option value="">- <?php echo lang("select"); ?> -</option>
                        <?php foreach($tag['options'] as $opt) {
							$cl = '';
							if(in_array($opt['id'],$parm_tags)) $cl = 'selected="selected"';
							?>
                        <option value="<?php echo $opt['id']; ?>" <?php echo $cl; ?>><?php echo $opt['title']; ?></option>
                        <?php }?>
                    </select>
                </div>
            <?php }
				}
			}?>	
                <div class="form-line"></div>
                <div class="form-item full">
                    <input type="text" name="modelno" placeholder="<?php echo lang("Enter Model Number"); ?>" value="<?php echo $this->input->get('modelno'); ?>" />
                </div>
                <div class="form-item full">
                <input type="submit" value="<?php echo lang("search"); ?>" />
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <div class="products-right">
        <?php if(isset($category['description']) && !empty($category['description'])) {?>
    	<div class="span description margin-bottom-25"><?php echo $category['description']; ?></div>
    <?php } elseif(isset($division['description']) && !empty($division['description'])) {?>
    	<div class="span description margin-bottom-25"><?php echo $division['description']; ?></div>
    <?php }?>
    
        
        	<?php 
			if(!empty($products)) {
			foreach($products as $product) {
				$link = route_to("products/details/".$product['id']);
				?>
            	<div class="product-box listing three">
                	<div class="img addImgEffect"><?php if(!empty($product['image'])) {?><a title="<?php echo $product['title']; ?>" href="<?php echo $link; ?>"><img src="<?php echo dynamic_img_url(); ?>products/image/710x450/<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" title="<?php echo $product['title']; ?>" /></a><?php }?></div>
                    <h2><a title="<?php echo $product['title']; ?>" href="<?php echo $link; ?>"><?php echo $product['title']; ?></a></h2>
                    <h3><?php echo $product['product_type']; ?></h3>
                    <a title="<?php echo $product['title']; ?>" class="learn-more" href="<?php echo $link; ?>"><?php echo lang("more details"); ?></a>
                </div>
            <?php }?>
            <?php if($this->pagination->create_links() != '') {?>
        <div class="pagination-box"><?php echo $this->pagination->create_links(); ?></div><?php }?>
            <?php
			} else {?>
            <div class="no-record-found"><?php echo lang("no products found..."); ?></div>
            <?php }?>
        </div>
        </div>
    </div>
</div>