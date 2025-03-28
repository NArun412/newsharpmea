<?php 
$cl_b = 'no-banner';
$ico = 'right';
if($this->lang->lang() == "ar") $ico = 'left';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
	<div class="centered1">
    <?php if(validate_user()) $this->load->view("includes/manage"); ?>

    <h1 class="product-page-title"><?php echo $tag['title']; ?></h1>
    <?php if(isset($tag['description']) && !empty($tag['description'])) {?>
    	<div class="span description margin-bottom-25"><?php echo $tag['description']; ?></div>
    <?php }?>
    
    <div class="span load_more_page_append">
<?php $this->load->view("content/tag-products"); ?>
</div>

    <?php if($count > count($products)) {?>
<div class="load-more-page"><a id="load_more_page" onclick="load_more_page()" data-url="<?php echo $load_url; ?>"><?php echo lang("load more"); ?></a></div>
<?php }?>
        
    </div>
</div>