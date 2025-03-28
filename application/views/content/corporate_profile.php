<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
<div class="centered2" >
<?php if(validate_user()) $this->load->view("includes/manage"); ?>
<h1 class="with-border">
<?php echo lang('SMEF Corporate Profile'); ?>
</h1>
<div class="span" >
<ul class="corporate_profile">
<?php foreach($categories as $cat) {
	$link = route_to("about/corporate_profile/".$cat['id']);
	$target = '';
	if(!empty($cat['external_link'])) {
		$target ='target="_blank"';
		$link = prep_url($cat['external_link']);
	}
	?>
<li>
<a href="<?php echo $link; ?>" <?php echo $target; ?> <?php if(isset($category) && $category['id'] == $cat['id']) {?>class="active"<?php }?> ><?php echo $cat['title']; ?></a>
</li>
<?php }?>
</ul>
</div>
<div class="span" >
<div class="left"><?php if(!empty($category['image'])) {?><figure>
<img src="<?php echo dynamic_img_url(); ?>corporate_profile_categories/image/720x240/<?php echo $category['image']; ?>" title="<?php echo $category['title']; ?>" alt="<?php echo $category['title']; ?>" />
</figure><?php }?></div>
<div class="right">
<?php if($category['google_map_coordinates'] != '') {?>
<div id="map" data-w="720" data-h="240" class="resize-prop" data-lat="<?php echo extract_google_map_coordinates($category['google_map_coordinates'],'lat'); ?>" data-lng="<?php echo extract_google_map_coordinates($category['google_map_coordinates'],'lng'); ?>"></div><?php }?>
</div>
</div>
<div class="clearfix"></div>
<div  class="span">
<?php foreach($corporate_profile_items as $k => $it) {
	$clear = '';
	$cl = 'left';
 	if($k%2 != 0) { $cl = 'right';$clear = '<div class="clearfix"></div>'; }
	?>
    <div class="<?php echo $cl; ?>  gray-border-top" >
<h2><?php echo $it['title']; ?></h2>
<div class="address">
<?php echo $it['description']; ?>
</div>
</div>
<?php echo $clear; ?>
<?php }?>
</div>
</div>
</div>