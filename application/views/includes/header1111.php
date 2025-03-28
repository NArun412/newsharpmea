<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
    function redirectBannerDetail(linkUrl) {

        var url = '<?= site_url(); ?>' + linkUrl;
        console.log(url);
        if (linkUrl != '') {
            window.location = url;
        }
        return false;
    }
</script>
<?php
error_reporting(0);
$class = $this->router->class;
$method = $this->router->method;
$products_menu = $this->website_fct->get_products_menu();
//print '<pre>'; print_r($products_menu); exit;
$cart_dir = 'right';
$logo_dir = '';

if ($this->lang->lang() == "ar") {
    $cart_dir = 'left';
    $logo_dir = '';
}
?>
<?php

if (!empty($icon)) {
    ?>
<div class="notifi_bottom">
         <?php
            $link_open = '';
            $link_close = '';
            $target = '';
           
            foreach ($icon as $popups){
                if (!empty($popups['link'])) {
                    $target = 'target="_blank"';
                    $link_open = '<a href="' . prep_url($popups['link']) . '" ' . $target . '>';
                    $link_close = '</a>';
                    ?>
                    <div class="ad-icon ad-icon_<?php echo $popups['id_popup__icons']?>">
                        <a class="close-ad-icon" onclick="hideAdIcon('ad-icon_<?php echo $popups['id_popup__icons']?>')"><!--<i class="fa fa-times-circle-o" aria-hidden="true"></i>--><?php echo lang("close"); ?></a>
                        <?php echo $link_open; ?><img src="<?php echo dynamic_img_url(); ?>popup__icons/icon/500x220/<?php echo $popups['icon']; ?>" alt="<?php echo $popups['icon']; ?>" title="<?php echo $popups['icon']; ?>" /><?php echo $link_close; ?>
                    </div>
                <?php
                } 
            }
        ?> 
    </div>
<?php } ?>

<section id="header">
    <div id="top" > <a href="<?php echo site_url(); ?>" > <img src="<?php echo assets_url(); ?>images/sharp-logo<?php echo $logo_dir; ?>.png" class="logo"  /> </a>
        <div class="fr web-view-only" >
            <?php $this->load->view("includes/header-right"); ?>
            <ul id="menu">
                <li><a href="<?php echo site_url(); ?>" <?php if ($this->router->class == 'home' && $this->router->method == 'index') { ?>class="active"<?php } ?>><?php echo lang('Home'); ?></a></li>
                <li class="expanded products-menu-hover"> <a href="<?php echo route_to('products'); ?>" <?php if ($this->router->class == 'products') { ?>class="active"<?php } ?>><?php echo lang('Products'); ?><i class="fa fa-caret-down" aria-hidden="true"></i></a> </li>
                <li class="nested"><a href="<?php echo route_to('about/index'); ?>" class="<?php if (($class == "about" && ($method == "index" || $method == "corporate_profile" || $method == "philosophy" || $method == "environmental_csr")) || ($class == 'md' && $method == 'index')) { ?>active<?php } ?>"
                                      ><?php echo lang('About Sharp'); ?><i class="fa fa-caret-down" aria-hidden="true"></i></a>
                    <ul>
                        <li><a href="<?php echo route_to('about/index'); ?>" class="<?php if ($class == "about" && $method == "index") echo 'active'; ?>" ><?php echo lang("sharp history"); ?></a></li>
                        <li><a href="<?php echo route_to('about/corporate_profile'); ?>" class="<?php if ($method == "corporate_profile") echo 'active'; ?>" ><?php echo lang("SMEF corporate profile"); ?></a></li>
                        <li><a href="<?php echo route_to('about/philosophy'); ?>" class="<?php if ($method == "philosophy") echo 'active'; ?>" ><?php echo lang("philosophy"); ?></a></li>
                        <li><a href="<?php echo route_to('md/index'); ?>" class="<?php if ($class == "md") echo 'active'; ?>" ><?php echo lang("MD message"); ?></a></li>
                        <li><a href="<?php echo route_to('about/environmental_csr'); ?>" class="<?php if ($method == "environmental_csr") echo 'active'; ?>" ><?php echo lang("environmental & CSR"); ?></a></li>
                        <li><a class="<?php if ($this->router->class == "Compliance" && $this->router->method == 'index') echo 'active'; ?>" href="<?php echo route_to('Compliance'); ?>"><?php echo lang("COMPLIANCEMENU"); ?></a></li>
                    </ul>
                </li>
                <li><a href="<?php echo route_to('news'); ?>" <?php if ($this->router->class == 'news') { ?>class="active"<?php } ?>><?php echo lang('News & Events'); ?></a></li>
                <li class="nested left-side"><a href="<?php echo route_to('support'); ?>" <?php if ($this->router->class == 'support') { ?>class="active"<?php } ?>><?php echo lang('Support'); ?><i class="fa fa-caret-down" aria-hidden="true"></i></a>
                    <ul>
                        <li><a class="<?php if ($this->router->class == "support" && $this->router->method == 'index') echo 'active'; ?>" href="<?php echo route_to('support'); ?>"><?php echo lang("download center"); ?></a></li>
                        <li><a class="<?php if ($this->router->class == "SourceDownload" && $this->router->method == 'index') echo 'active'; ?>" href="<?php echo route_to('SourceDownload'); ?>"><?php echo lang("CODEDOWNLOADERMENU"); ?></a></li>
                        <li><a href="http://customer.warranty.smefworld.com" target="_blank"><?php echo lang("product registration"); ?></a></li>

<!--  <li><a href="<?php //echo route_to("support/service_center_location");   ?>" <?php //if($this->router->class=='support' && $this->router->method == 'service_center_location'){  ?>class="active"<?php //}  ?>><?php //echo lang("service center location");   ?></a></li>-->
                    </ul>

                </li>
                <li class="nested"><a href="<?php echo route_to('contact'); ?>" <?php if ($this->router->class == 'contact') { ?>class="active"<?php } ?>><?php echo lang('Contact Us'); ?><i class="fa fa-caret-down" aria-hidden="true"></i></a>
                    <ul>
                        <li><a href="<?php echo route_to("contact"); ?>" <?php if ($this->router->class == 'contact' && $this->router->method == 'index') { ?>class="active"<?php } ?>><?php echo lang("Regional head office"); ?></a></li>
                        <li><a href="<?php echo route_to("careers"); ?>" <?php if ($this->router->class == 'careers') { ?>class="active"<?php } ?>><?php echo lang("careers"); ?></a></li>

                        <li><a href="<?php echo route_to("contact/inquiry"); ?>" <?php if ($this->router->class == 'contact' && $this->router->method == 'inquiry') { ?>class="active"<?php } ?>><?php echo lang("inquiry"); ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="products-drop-down">
            <div class="drop-down-tabs">
                <?php
                foreach ($products_menu as $k => $mn) {
                    $cl = '';
                    if ($k == 0)
                        $cl = 'active';
                    ?>
                    <a title="<?php echo $mn['title']; ?>" class="<?php echo $cl; ?>" href="<?php echo route_to("products/index/" . $mn['id']); ?>"><?php echo $mn['title']; ?></a>
                <?php } ?>
            </div>
            <div class="menus-container">
                <?php
                foreach ($products_menu as $k => $mn) {
                    $cl = 'none';
                    if ($k == 0)
                        $cl = 'block';
                    ?>
                    <div class="menus" style="display:<?php echo $cl; ?>">
                        <?php if (!empty($mn['categories'])) { ?>
                            <?php foreach ($mn['categories'] as $k1 => $cat) { ?>
                                <ul>
                                    <!--<li><a title="<?php echo $cat['title']; ?>" href="<?php echo route_For_Product_Title("products/index/" . $cat['title']); ?>"><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo $cat['title']; ?></a>-->
                                    <li><a title="<?php echo $cat['title']; ?>" href="<?php echo route_For_Product_Index("products/index/" . $mn['id'] . '/' . $cat['id']); ?>"><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo $cat['title']; ?></a>
                                        <?php if (!empty($cat['lines'])) { ?>
                                            <ul>
                                                <?php foreach ($cat['lines'] as $k2 => $line) { ?>
                                                <!--<li><a title="<?php echo $line['title']; ?>" href="<?php echo route_For_Product_Title("products/index/" . $line['title']); ?>"><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo $line['title']; ?></a></li>-->
                                                <li><a title="<?php echo $line['title']; ?>" href="<?php echo route_For_Product_Index("products/index/" . $mn['id'] . '/' . $cat['id'] . '/' . $line['id']); ?>"><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo $line['title']; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                    </li>
                                </ul>
                            <?php } ?>
                        <?php } ?>
                        <?php if (!empty($mn['image'])) { ?>
                            <div class="imgs"> <a href="<?php echo route_to("products/index/" . $mn['id']); ?>" title="<?php echo $mn['title']; ?>"> <img src="<?php echo dynamic_img_url(); ?>divisions/image/480x290/<?php echo $mn['image']; ?>" alt="<?php echo $mn['title']; ?>" title="<?php echo $mn['title']; ?>" /></a> </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
    if (!isset($banners))
        $banners = array();
    if (count($banners) > 0) {
        if (($this->router->class == 'home' && $this->router->method == 'index') || ($this->router->class == 'about' && $this->router->method == 'philosophy')) {
            $path = 'image/2000x936/';
        } else {
            $path = 'image/2000x724/';
        }
        ?>
        <div id="banner" >

            <div id="banner_layer" >
                <?php
                $lng11 = $this->lang->lang();
                // print '<pre>'; print_r($banners); exit;
                foreach ($banners as $banner) {
                    if ($lng11 != default_lang()) {

                        if (isset($banner["image_" . $lng11]) && !empty($banner["image_" . $lng11]) && file_exists("./uploads/banner/" . $path . "/" . $banner["image_" . $lng11])) {
                            $banner_img = $banner["image_" . $lng11];
                        } else {
                            $banner_img = $banner["image"];
                        }
                        if (isset($banner["logo_" . $lng11]) && !empty($banner["logo_" . $lng11]) && file_exists("./uploads/banner/" . $path . "/" . $banner["logo_" . $lng11])) {
                            $banner_logo = $banner["logo_" . $lng11];
                        } else {
                            $banner_logo = $banner["logo"];
                        }
                    } else {
                        $banner_img = $banner["image"];
                        $banner_logo = $banner["logo"];
                    }

                    if ((!empty($banner_img) && file_exists("./uploads/banner/" . $path . "/" . $banner_img))) {
                        ?>
                        <div>
                            <div class="relative span <?php echo $banner['link'] != '' ? 'curPointer' : '' ?>" >
                                <div class="banner-img"  style="background-image:url(<?php echo dynamic_img_url(); ?>banner/<?php echo $path; ?><?php echo $banner_img; ?>)"></div>
                                <div class="centered2" onclick="redirectBannerDetail('<?php echo $banner['link']; ?>')">
                                    <div class="banner-text-wrapper <?php echo $banner['brief_color']; ?>" >
                                        <?php if (!empty($banner['title']) && $banner['hide_title'] == 0) { ?>
                                            <h2><?php echo $banner['title']; ?></h2>
                                        <?php } ?>
                                        <?php if (!empty($banner_logo)) { ?>
                                            <div class="banner-logo"><img src="<?php echo dynamic_img_url(); ?>banner/logo/250x90/<?php echo $banner_logo; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></div>
                                        <?php } ?>
                                        <?php if (!empty($banner['brief'])) { ?>
                                            <p><?php echo $banner['brief']; ?></p>
                                        <?php } ?>
                                        <?php if (!empty($banner['link'])) { ?>
                                            <!--<a class="read-more" href="<?php echo site_url() . $banner['link'] . '/'; ?>" target="<?php if ($banner['link_target'] == 1) { ?>_blank<?php } else { ?>_self<?php } ?>">-->
                                            <?php
//                if(!empty($banner['link_label'])) echo $banner['link_label']; else echo lang("read more"); 
                                            ?>
                                            <!--</a>-->
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    <?php } ?><div class="menu-shadow"></div>
</section>

