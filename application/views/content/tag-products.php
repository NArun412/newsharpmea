	<?php 
			if(!empty($products)) {
			foreach($products as $product) {
				$link = route_to("products/details/".$product['id']);
				?>
            	<div class="product-box three">
                	<div class="img addImgEffect"><?php if(!empty($product['image'])) {?><a title="<?php echo $product['title']; ?>" href="<?php echo $link; ?>"><img src="<?php echo dynamic_img_url(); ?>products/image/710x450/<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" title="<?php echo $product['title']; ?>" /></a><?php }?></div>
                    <h2><a title="<?php echo $product['title']; ?>" href="<?php echo $link; ?>"><?php echo $product['title']; ?></a></h2>
                    <h3><?php echo $product['category_name']; ?></h3>
                    <a title="<?php echo $product['title']; ?>" class="learn-more" href="<?php echo $link; ?>"><?php echo lang("more details"); ?></a>
                </div>
            <?php }?>         <?php }?>