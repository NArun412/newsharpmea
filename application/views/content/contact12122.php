<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
<div class="centered2" >
<?php if(validate_user()) $this->load->view("includes/manage"); ?>
<h1 class="with-border">
<?php echo lang('Contact Us'); ?> - <?php echo lang("regional head office"); ?>
</h1>
<?php $this->load->view("content/contact-menu"); ?>
<div class="span" >
<div class="left" >
<?php if(!empty($contact_info['image'])) {?><figure>
<img src="<?php echo dynamic_img_url(); ?>branches/image/720x240/<?php echo $contact_info['image']; ?>" title="<?php echo $contact_info['title']; ?>" alt="<?php echo $contact_info['title']; ?>" />
</figure><?php }?>
<?php if(!empty($contact_info['sub_title'])) {?> 
<h2><?php echo $contact_info['sub_title']; ?></h2><?php }?>
<div class="address">
<?php if(!empty($contact_info['address'])) {?> 
<?php echo $contact_info['address']; ?><br /><?php }?>
<?php if(!empty($contact_info['phone'])) {?> 
<?php echo lang('Phone'); ?>: <div class="rtl-inline"><?php echo $contact_info['phone']; ?></div><br /><?php }?>
<?php if(!empty($contact_info['fax'])) {?> 
<?php echo lang('Fax'); ?>: <div class="rtl-inline"><?php echo $contact_info['fax']; ?></div><br /><?php }?>
<?php if(!empty($contact_info['email'])) {?> 
<?php echo lang('Email ID'); ?> : <a href="mailto:<?php echo $contact_info['email']; ?>"><?php echo $contact_info['email']; ?></a><?php }?>
</div>
<!--<div class="gray_line" ></div>-->
</div>
<div class="right" >
<?php if($contact_info['google_map_coordinates'] != '') {?>
	<div id="map" data-lat="<?php echo extract_google_map_coordinates($contact_info['google_map_coordinates'],'lat'); ?>" data-lng="<?php echo extract_google_map_coordinates($contact_info['google_map_coordinates'],'lng'); ?>"></div><?php }?>
<h2><?php echo lang('Google Location Map'); ?></h2>
<!--<div class="gray_line" ></div>-->
</div>
</div>
</div>
</div>
