<?php
$class = $this->router->class;
$method = $this->router->method;
$products_menu = $this->website_fct->get_products_menu();
$cart_dir = 'right';
if($this->lang->lang() == "ar")
$cart_dir = 'left';
?>
<section id="footer">
  <div class="span">
    <div class="centered1" >
      <div class="footer-wrapper" >
        <ul id="footer_ul">
          <li class="row-block">
            <ul class="footer_ul_sub first">
              <li><a href="<?php echo route_to('home/index'); ?>" class="big <?php if($class == "home" || $class == "") echo 'active'; ?>" ><?php echo lang('Home'); ?></a></li>
              <li><a href="<?php echo route_to('about/index'); ?>" class="big <?php //if($class == "about") echo 'active'; ?>" ><?php echo lang('About Sharp'); ?></a></li>
              <li><a href="<?php echo route_to('about/index'); ?>" class="<?php if($class == "about" && $method == "index") echo 'active'; ?>" ><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo lang("sharp history"); ?></a></li>
              <li><a href="<?php echo route_to('about/corporate_profile'); ?>" class="<?php if($method == "corporate_profile") echo 'active'; ?>" ><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo lang("SMEF corporate profile"); ?></a></li>
              <li><a href="<?php echo route_to('about/philosophy'); ?>" class="<?php if($method == "philosophy") echo 'active'; ?>" ><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo lang("philosophy"); ?></a></li>
              <li><a href="<?php echo route_to('md/index'); ?>" class="<?php if($class == "md") echo 'active'; ?>" ><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo lang("MD message"); ?></a></li>
              <li><a href="<?php echo route_to('about/environmental_csr'); ?>" class="<?php if($method == "environmental_csr") echo 'active'; ?>" ><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo lang("environmental & CSR"); ?></a></li>
              
                      <li><a href="<?php echo route_to('news/index'); ?>" class="big <?php if($class == "news") echo 'active'; ?>" ><?php echo lang('News & Events'); ?></a></li>
              <li><a class="big <?php if($class == "support") echo 'active'; ?>" href="<?php echo route_to('support'); ?>" ><?php echo lang('Support'); ?></a></li>
              <li><a class="<?php if($class == "support") echo 'active'; ?>" href="<?php echo route_to('support'); ?>"><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo lang("download center"); ?></a></li>
              <li><a href="http://customer.warranty.smefworld.com" target="_blank"><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo lang("product registration"); ?></a></li>
              <!--<li><a href="<?php //echo route_to("support/service_center_location"); ?>" <?php //if($this->router->class=='support' && $this->router->method == 'service_center_location'){?>class="active"<?php //}?>><i class="fa fa-caret-<?php //echo $cart_dir; ?>" aria-hidden="true"></i><?php //echo lang("service center location"); ?></a></li>-->
                 <li><a href="<?php echo route_to('site_policy/index'); ?>" class="big <?php if($class == "site_policy") echo 'active'; ?>" ><?php echo lang("site policy"); ?></a></li>
              <li><a href="<?php echo route_to('global_basic_policy/index'); ?>" class="big <?php if($class == "global_basic_policy") echo 'active'; ?>" ><?php echo lang("Global Basic Policy on Information Security"); ?></a></li>
            </ul>
          </li>
          <li class="row-block">
            <ul class="footer_ul_sub second">
              <li><a href="<?php echo route_to('products/index'); ?>" class="big" ><?php echo lang('Products'); ?></a></li>
              <li class="row-block">
                <?php if(!empty($products_menu)) { ?>
          
                    <?php
$c = 0;
foreach($products_menu as $menu_item) {
	if(empty($menu_item['categories'])) {
		$c++;
	?>
       <div class="span3">
                  <ul class="footer_ul_sub">
                    <li><a href="<?php echo route_to('products/index/'.$menu_item['id']); ?>" title="<?php echo $menu_item['title']; ?>"><?php echo $menu_item['title'].'test'; ?></a></li>
                          </ul>
                </div>
                    <?php } else {?>
                          <div class="span3">
                    <?php 
		foreach($menu_item['categories'] as $cat) {
			$c++;
		?>
        <div class="span"><ul class="footer_ul_sub">
                    <li><a href="<?php echo route_to('products/index/'.$menu_item['id'].'/'.$cat['id']); ?>" title="<?php echo $cat['title']; ?>"><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo $cat['title']; ?></a></li>
                    <?php 
					if(!empty($cat['lines'])) {
			foreach($cat['lines'] as $line) {
				$c++;
				?>
                    <li><a class="small" href="<?php echo route_to('products/index/'.$menu_item['id'].'/'.$cat['id'].'/'.$line['id']); ?>" title="<?php echo $line['title']; ?>"><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo $line['title']; ?></a></li>
                    <?php }}?></ul></div>
                    <?php 
		}?>
                </div>
                    <?php }?>
                <?php }?>  <?php }?>
              </li>
            </ul>
          </li>
          <!--<li class="row-block">
            <ul class="footer_ul_sub third">
      
            </ul>
          </li>-->
          <li class="row-block">
            <ul class="footer_ul_sub forth">
              <li><a href="<?php echo route_to('contact/index'); ?>" class="big <?php if($class == "contact") echo 'active'; ?>" ><?php echo lang('Contact Us'); ?></a></li>
              <li><a href="<?php echo route_to("contact"); ?>" <?php if($this->router->class=='contact' && $this->router->method == 'index'){?>class="active"<?php }?>><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo lang("regional head office"); ?></a></li>
              <li><a href="<?php echo route_to("careers"); ?>" <?php if($this->router->class=='careers'){?>class="active"<?php }?>><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo lang("careers"); ?></a></li>
              
              <li><a href="<?php echo route_to("contact/inquiry"); ?>" <?php if($this->router->class=='contact' && $this->router->method == 'inquiry'){?>class="active"<?php }?>><i class="fa fa-caret-<?php echo $cart_dir; ?>" aria-hidden="true"></i><?php echo lang("inquiry"); ?></a></li>
              
                  <li><a href="<?php echo route_to('sitemap/index'); ?>" class="big <?php if($class == "sitemap") echo 'active'; ?>" ><?php echo lang("site map"); ?></a></li>
       
              
              
              <?php //if(isset($settings)) {?>
  <!--            <li><span class="big"><?php //echo lang("keep in touch"); ?></span> <span class="span social-links">
                <?php //if(!empty($settings['facebook'])) {?>
                <a href="<?php //echo prep_url($settings['facebook']); ?>" target="_blank" title="<?php //echo lang("facebook"); ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                <?php //}?>
                <?php //if(!empty($settings['twitter'])) {?>
                <a href="<?php //echo prep_url($settings['twitter']); ?>" target="_blank" title="<?php //echo lang("twitter"); ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                <?php //}?>
                <?php //if(!empty($settings['google_plus'])) {?>
                <a href="<?php //echo prep_url($settings['google_plus']); ?>" target="_blank" title="<?php //echo lang("google_plus"); ?>"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                <?php //}?>
                </span> </li>-->
              <?php //}?>
              
              <?php if(!empty($this->divisions_social)) {?>
              	<?php foreach($this->divisions_social as $div_s) {?>
                	<?php if($div_s['facebook'] != '' || $div_s['twitter'] != '' || $div_s['instagram'] != '' || $div_s['google_plus'] != '' || $div_s['youtube'] != '' || $div_s['linked_in'] != '') {?>
                    
                     <li><span class="big"><?php echo $div_s['title']; ?></span> <span class="span social-links">
                <?php if(!empty($div_s['facebook'])) {?>
                <a href="<?php echo prep_url($div_s['facebook']); ?>" target="_blank" title="<?php echo lang("facebook"); ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                <?php }?>
                <?php if(!empty($div_s['twitter'])) {?>
                <a href="<?php echo prep_url($div_s['twitter']); ?>" target="_blank" title="<?php echo lang("twitter"); ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                <?php }?>
                <?php if(!empty($div_s['google_plus'])) {?>
                <a href="<?php echo prep_url($div_s['google_plus']); ?>" target="_blank" title="<?php echo lang("google_plus"); ?>"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                <?php }?>
                
                <?php if(!empty($div_s['instagram'])) {?>
                <a href="<?php echo prep_url($div_s['instagram']); ?>" target="_blank" title="<?php echo lang("instagram"); ?>"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                <?php }?>
                
                <?php if(!empty($div_s['youtube'])) {?>
                <a href="<?php echo prep_url($div_s['youtube']); ?>" target="_blank" title="<?php echo lang("youtube"); ?>"><i class="fa fa-youtube" aria-hidden="true"></i></a>
                <?php }?>
                
                <?php if(!empty($div_s['linked_in'])) {?>
                <a href="<?php echo prep_url($div_s['linked_in']); ?>" target="_blank" title="<?php echo lang("linked_in"); ?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                <?php }?>
                
                <?php if(!empty($div_s['apple_store'])) {?>
                <a href="<?php echo prep_url($div_s['apple_store']); ?>" target="_blank" title="<?php echo lang("apple_store"); ?>"><i class="fa fa-apple" aria-hidden="true"></i></a>
                <?php }?>
                
                <?php if(!empty($div_s['google_play_store'])) {?>
                <a href="<?php echo prep_url($div_s['google_play_store']); ?>" target="_blank" title="<?php echo lang("google_play"); ?>"><i class="fa fa-android" aria-hidden="true"></i></a>
                <?php }?>
                
                </span> </li>
                    	
                    <?php }?>
                <?php }?>
              <?php }?>
            </ul>
          </li>
        </ul>
      </div>
      <div class="copyright border-box" > <span> <?php echo lang("copyright"); ?> @ <?php echo date('Y'); ?> <?php echo lang("sharp middle east"); ?> </span> </div>
    </div>
  </div>
</section>
