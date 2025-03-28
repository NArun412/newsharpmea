<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$current_url = "http" . (($_SERVER['SERVER_PORT']==443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if(!isset($seo)) { 
	$seo_cond = array('id_static_seo_pages'=>1);
	$seo = $this->fct->getonerow('static_seo_pages',$seo_cond);
}
$dir_t = '';
if($this->lang->lang() == 'ar') $dir_t = 'dir="rtl"';
?>
<!DOCTYPE html>
<html lang="en" <?php echo $dir_t; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="english" />
<link rel="alternate" hreflang="en" href="<?php echo site_url(); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="true">
<title><?=$seo['meta_title']?></title>
<?php if(isset($seo['meta_description']) && !empty($seo['meta_description'])) {?>
<meta name="description" content="<?=$seo['meta_description']?>" />
<?php }?>
<?php if(isset($seo['meta_keywords']) && !empty($seo['meta_keywords'])) {?>
<meta name="keywords" content="<?=$seo['meta_keywords']?>" />
<?php }?>
<?php if(isset($seo['meta_title']) && !empty($seo['meta_title'])) {?>
<meta property="og:title" content="<?=$seo['meta_title']?>" /> 
<?php }?>
<?php if(isset($seo['og_image']) && !empty($seo['og_image'])) {?>
<meta property="og:image" content="<?php echo $seo['og_image']; ?>" />
<?php } else {?>
<meta property="og:image" content="<?php echo assets_url(); ?>images/logo.png" />
<?php }?> 
<?php if(isset($seo['meta_description']) && !empty($seo['meta_description'])) {?>
<meta property="og:description" content="<?=$seo['meta_description']?>" /> 
<?php }?>
<meta property="og:url" content="<?=$current_url?>">
{_meta}
<link rel="shortcut icon" href="<?php echo assets_url(); ?>images/favicon.ico" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400|Roboto+Condensed:300,400,700" />
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/style.css" />
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/transitions.css" />
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/font-awesome-4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/slick/slick.css" />
<link rel="stylesheet" href="<?php echo assets_url(); ?>js/intl-tel-input-master/build/css/intlTelInput.css">
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/slicknav.css" />
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/responsive-tabs.css" />
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/responsive.css" />

<?php 
$theme = 'default';
if(isset($render_theme)) $theme = $render_theme;
?>
<!--<link rel="stylesheet" href="<?php echo assets_url(); ?>css/style-color-default.css" />-->
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/style-color-<?php echo $theme; ?>.css" />
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/fonts.css" />
<?php if($this->lang->lang() == "ar") {?>
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/style-rtl.css" />
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/responsive-rtl.css" />
<?php } ?>
{_styles}
<? 
if( isset($map) )
echo $map["js"]; 
?>
</head>
<body class="c-<?php echo $this->router->class; ?> m-<?php echo $this->router->method; ?>">
<!-- Global site tag (gtag.js) - Google Analytics -->

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-111565290-1"></script>

<script>

  window.dataLayer = window.dataLayer || [];

  function gtag(){dataLayer.push(arguments);}

  gtag('js', new Date());

 

  gtag('config', 'UA-111565290-1');

</script>

<div id="MenuResponsive" class="span"></div>
<div id="responsive-header-right"><?php $this->load->view("includes/header-right"); ?></div>
<div id="responsive-search"><?php $this->load->view("search/search-form"); ?></div>
<div id="popupshadow" onClick="hide_popup()"></div>
<div id="popup">	
<div class="popup-container">
    <div class="popup-loader">
    </div>
</div>
</div>
<section id="main" >
<div class="span" >
{header}
<section id="content" >
{content}
</section>
{footer}
</div>
</section>
<script>
var nct_name = '<?php echo $this->security->get_csrf_token_name(); ?>';

<?php if($this->lang->lang() == "ar") {?>
var slick_arrow_right = 'left';
var slick_arrow_left = 'right';
var rtl = true;
<?php } else {?>
var slick_arrow_right = 'right';
var slick_arrow_left = 'left';
var rtl = false;
<?php }?>
var baseurl = '<?php echo base_url(); ?>';
var siteurl = '<?php echo site_url(); ?>';
var imgurl = '<?php echo img_url(); ?>';
var dynamicimgurl = '<?php echo dynamic_img_url(); ?>';
var assetsurl = '<?php echo assets_url(); ?>';
var select_lang = '<?php echo lang('select'); ?>';
var please_wait_lang = '<?php echo lang('please wait'); ?>';
var loading = '<?php echo lang('loading..'); ?>';
var lang_close = '<?php echo lang('close'); ?>';
<?php if(($this->router->class == 'home' && $this->router->method == 'index') || ($this->router->class == 'about' && $this->router->method == 'philosophy')) {?>
var Default_banner_height = 936;
<?php } else {?>
var Default_banner_height = 724;
<?php }?>
var Default_banner_width = 2000;
var menu_label = "<?php echo strtoupper(lang("menu")); ?>";
</script>
<script src="<?php echo assets_url(); ?>js/jquery-1.11.0.min.js"></script>
<script src="<?php echo assets_url(); ?>js/jquery-cookie-master/src/jquery.cookie.js"></script>
<script type="text/javascript">
function fade_popup()
{
	$("body").addClass('overhidden');
	$("#popupshadow").fadeIn("fast");
	$("#popup").fadeIn("fast",function(){
		$('.popup-loader').append('<a class="popup-close" onclick="hide_popup()">'+lang_close+' X</a>');
	});
}
function hide_popup()
{
	$("#popupshadow").fadeOut("fast");
	$("#popup").fadeOut("fast");
	$('.popup-loader').html('');
	$("body").removeClass('overhidden');
}
</script>
<script src="<?php echo assets_url(); ?>js/search.js"></script>
<script src="<?php echo assets_url(); ?>js/slick/slick.js"></script>
<script src="<?php echo assets_url(); ?>js/jquery.custom.form.validate.js"></script>
<script src="<?php echo assets_url(); ?>js/intl-tel-input-master/build/js/intlTelInput.js"></script>
<script src="<?php echo assets_url(); ?>js/jquery.slicknav.min.js"></script>
<script src="<?php echo assets_url(); ?>js/jquery.responsiveTabs.js"></script>
<?php if( ($this->router->class == 'contact' && ( $this->router->method == 'service_center_location' || $this->router->method == 'index')) || ($this->router->class == 'about' && $this->router->method == 'corporate_profile') ) {?>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyAKH31gYTmojsZPGmam6q414C2l8XSjNGk"></script>
<?php }?>
<script src="<?php echo assets_url(); ?>js/common.js"></script>
{_scripts}
</body>
</html>