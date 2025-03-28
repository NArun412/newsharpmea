<?php

$cond=array('id_settings'=>1);
$this->db->select('image,website_title');
$title_site=$this->fct->getonerow('settings',$cond); 
$seg=$this->uri->segment(2);
?>
<!doctype html>
<html class="" lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>
<?= $title_site["website_title"]; ?>
</title>
<link rel="icon" type="image/ico" href="<?php echo base_url(); ?><?php echo base_url(); ?>assets/images/favicon.ico" />
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ============================================
        ================= Stylesheets ===================
        ============================================= -->
<!-- vendor css files -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/vendor/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/vendor/animate.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/vendor/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/animsition/css/animsition.min.css">

<!-- project main css files -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/main.css">
<!--/ stylesheets -->

<!-- ==========================================
        ================= Modernizr ===================
        =========================================== -->
<script src="<?php echo base_url(); ?>assets/js/vendor/modernizr/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<!--/ modernizr -->

</head>

<body id="minovate" class="appWrapper">

<!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]--> 

<!-- ====================================================
        ================= Application Content ===================
        ===================================================== -->
<div id="wrap" class="animsition">
  <div class="page page-core page-login">
    <div class="text-center">
      <h3 class="text-light text-white"> <span class="text-lightred"><?php echo $this->settings["website_title"]; ?></span></h3>
    </div>
    <div class="container w-420 p-15 bg-white mt-40 text-center">
      <h2 class="text-light text-greensea">Forgot Password</h2>
      <?php 
if(validation_errors() || $this->session->userdata('login_error')){ ?>
      <div class="alert alert-big alert-lightred alert-dismissable fade in">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><strong>error:</strong></h4>
        <p><?php echo validation_errors();?></p>
        <?php
if($this->session->flashdata('login_error') != ""){
	echo '<p>'.$this->session->flashdata('login_error').'</p>' ;
} ?>
      </div>
      <?php } ?>
      <!--<form name="form" action="<?php //echo admin_url('login/password'); ?>" method="post" class="form-validation mt-20">-->
      
        <?php echo form_open(admin_url('login/password'),array(
	  	"name"=>"form",
		"method"=>"post",
		"class"=>"form-validation mt-20"
	  )); ?>
        <div class="form-group">
          <input type="email" class="form-control underline-input" name="email" value="<?= set_value('email'); ?>" placeholder="Email" />
        </div>
        <div class="form-group text-left mt-20"> 
          <!-- <a href="index.html" class="">Login</a> -->
          <input type="submit" class="btn btn-danger b-0 br-2 mr-5" value="Send Password" >
          <a href="<?php echo admin_url("login"); ?>" class="pull-right mt-10">Back to login</a> </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<!--/ Application Content --> 
<!-- ============================================
        ============== Vendor JavaScripts ===============
        ============================================= --> 
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script> 
<script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>assets/js/vendor/jquery/jquery-1.11.2.min.js"><\/script>')</script> 
<script src="<?php echo base_url(); ?>assets/js/vendor/bootstrap/bootstrap.min.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/vendor/jRespond/jRespond.min.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/vendor/sparkline/jquery.sparkline.min.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/vendor/slimscroll/jquery.slimscroll.min.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/vendor/animsition/js/jquery.animsition.min.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/vendor/screenfull/screenfull.min.js"></script> 
<!--/ vendor javascripts --> 
<!-- ============================================
        ============== Custom JavaScripts ===============
        ============================================= --> 
<script src="<?php echo base_url(); ?>assets/js/main.js"></script> 
</body>
</html>
