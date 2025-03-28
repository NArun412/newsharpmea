<div class="content_inner no-banner product-info">
  <div class="centered2">
   <?php $this->load->view("includes/breadcrumbs"); ?>
    <div class="span">
    <?php if(!empty($product['image'])) {?>
      <div class="product-details-left"> <img src="<?php echo dynamic_img_url(); ?>products/710x450/<?php echo $product['image'] ?>" alt="<?php echo $product['title']; ?>" title="<?php echo $product['title']; ?>" />
     <!--<img src="<?php echo img_url(); ?>test/product-brand.png" alt="" title="" class="product-brand-logo" />-->
       </div>
       <?php }?>
      <div class="product-details-right <?php if(empty($product['image'])) {?>full<?php }?>">
        <h1><?php echo $product['title']; ?></h1>
        <h4><?php echo $product['product_line_name'];
		if(empty($product['product_line_name'])) echo $product['category_name'];
		 ?></h4>
        <div class="description">
          <h3>Key Features</h3>
          <br />
          <br />
          <ul>
            <li>90" FHD (1920 x 1080) Japan made LCD Panel</li>
            <li>"Ultra Brilliant LCD" reproduce bright & crisp image</li>
            <li>"AquoMotion 200/240" reduce motion blur</li>
            <li>2D video/image are convertible for 3D video/image</li>
            <li>Hi-quality sound : "Audio Engine"</li>
            <li>Wireless devices compatible (WiFi, Bluetooth build-in)</li>
            <li>AQUOS.NET - Internet Search, YouTube, Daily Motion, Facebook, Twitter,</li>
            <li>Picasa, News, Weather, Recipe, Casual Games and more..</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if(isset($product['tabs']) && !empty($product['tabs'])) {?>
<div class="content_inner">
  <div class="centered2">
  
  <div class="product-menu" id="ResponsiveProductMenu" style="display:block;">
    <ul>
    <?php foreach($product['tabs'] as $tab) {?>
    <li><a href="#tab-<?php echo $tab['id_products_tabs']; ?>" title="<?php echo $tab['title']; ?>"><?php echo $tab['title']; ?></a></li>
    <?php }?>
    </ul>

    <div class="product-tab-content" id="tab-1">
      <?php for($i=1;$i<=3;$i++) {
			$link = route_to("products/details");
		?>

        <div class="product-box">
          <div class="img"><a href="<?php echo $link; ?>"><img src="<?php echo img_url(); ?>test/product-list.jpg" alt="" title="" /></a></div>
          <h3><a href="<?php echo $link; ?>">Product Name</a></h3>
          <div class="description">Using LEDs as the light source in AQUOS enables precise brightness control and high-speed response. Combined with the next-generation X-Gen panel, AQUOS achieves a higher contrast ratio and delivers vibrant red colours.</div>
        </div>

      <?php }?>
    
    </div>
    <div class="product-tab-content" id="tab-2"><div class="description">Using LEDs as the light source in AQUOS enables precise brightness control and high-speed response. Combined with the next-generation X-Gen panel, AQUOS achieves a higher contrast ratio and delivers vibrant red colours.</div></div>

  </div>
  
  </div>
</div>
<?php }?>
<div class="span may-also-like">
  <div class="block-title"><?php echo lang("You May Also Like"); ?></div>
  <div class="span">
    <div class="may-also-like-slider">
      <?php for($i=1;$i<=9;$i++) {
			$link = route_to("products/details");
		?>
      <div class="item">
        <div class="product-box">
          <div class="img"><a href="<?php echo $link; ?>"><img src="<?php echo img_url(); ?>test/product-list.jpg" alt="" title="" /></a></div>
          <h3><a href="<?php echo $link; ?>">Product Name</a></h3>
          <h4>Product Line</h4>
        </div>
      </div>
      <?php }?>
    </div>
  </div>
</div>
