<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
  <div class="centered2" >
  <?php if(validate_user()) $this->load->view("includes/manage"); ?>
    <h1 class="with-border"><?php echo $msg['title']; ?></h1>
    <?php //$this->load->view("content/contact-menu"); ?>
    <div class="span" > 
      <!--<div class="no-record-found"><?php //echo lang('under construction'); ?>...</div>-->
      <span class="thankyou-message"><?php echo $msg['description']; ?></span>
    </div>
  </div>
</div>
