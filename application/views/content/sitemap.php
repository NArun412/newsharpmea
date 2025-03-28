<div class="content_inner no-banner" >
  <div class="centered4">
  <?php if(validate_user()) $this->load->view("includes/manage"); ?>
    <div class="span">
      <h1 class="with-border"><?php echo lang("sitemap"); ?></h1>
      <!--<div class="no-record-found"><?php //echo lang("under contruction"); ?>...</div>-->
      <div class="sitemap-container">
        <div class="third-side">
          <ul>
            <li><a href="<?php echo route_to('home/index'); ?>"><?php echo lang('Home'); ?></a></li>
            <li><a href="<?php echo route_to('about/index'); ?>"><?php echo lang('About Sharp'); ?></a>
              <ul>
                <li><a href="<?php echo route_to('about/index'); ?>"><?php echo lang("sharp history"); ?></a></li>
                <li><a href="<?php echo route_to('about/corporate_profile'); ?>"><?php echo lang("corporate profile"); ?></a></li>
                <li><a href="<?php echo route_to('about/philosophy'); ?>"><?php echo lang("philosophy"); ?></a></li>
                <li><a href="<?php echo route_to('md/index'); ?>"><?php echo lang("MD message"); ?></a></li>
                <li><a href="<?php echo route_to('about/environmental_csr'); ?>"><?php echo lang("environmental & CSR"); ?></a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="third-side">
          <ul>
            <li><a href="<?php echo route_to('news/index'); ?>"><?php echo lang('News & Events'); ?></a></li>
            <li><a href="<?php echo route_to('support'); ?>" ><?php echo lang('Support'); ?></a>
              <ul>
                <li><a href="<?php echo route_to('support'); ?>" onclick="load_support()"><?php echo lang("product support"); ?></a></li>
                <li><a href="#"><?php echo lang("product registration"); ?></a></li>
              </ul>
            </li>
            <li><a href="<?php echo route_to('sitemap/index'); ?>"><?php echo lang("site map"); ?></a></li>
            <li><a href="<?php echo route_to('site_policy/index'); ?>"><?php echo lang("site policy"); ?></a></li>
            <li><a href="<?php echo route_to('global_basic_policy/index'); ?>"><?php echo lang("Global Basic Policy on Information Security"); ?></a></li>
          </ul>
        </div>
        <div class="third-side">
          <ul>
            <li><a href="<?php echo route_to('contact/index'); ?>"><?php echo lang('Contact Us'); ?></a>
              <ul>
                <li><a href="<?php echo route_to("contact"); ?>"><?php echo lang("regional head office"); ?></a></li>
                <li><a href="<?php echo route_to("careers"); ?>"><?php echo lang("careers"); ?></a></li>
                <li><a href="<?php echo route_to("contact/service_center_location"); ?>"><?php echo lang("service center location"); ?></a></li>
                <li><a href="<?php echo route_to("contact/feedback"); ?>"><?php echo lang("feedback"); ?></a></li>
              </ul>
            </li>
          </ul>
        </div>
        <?php if(!empty($divisions)) {?>
        <div class="span">
          <?php foreach($divisions as $div) {
				$categories = $this->website_fct->get_categories_by_division($div['id']);
			?>
          <div class="third-side">
            <ul>
              <li><a href="<?php echo route_to("products/index/".$div['id']); ?>"><?php echo $div['title']; ?></a>
                <?php if(!empty($categories)) {
					?>
                <ul>
                  <?php foreach($categories as $cat) {
							$categories_sub = $this->website_fct->get_categories_by_category($cat['id']);
							?>
                  <li><a href="<?php echo route_to("products/index/".$div['id'].'/'.$cat['id']); ?>"><?php echo $cat['title']; ?></a>
                    <?php if(!empty($categories_sub)) {?>
                    <ul>
                      <?php foreach($categories_sub as $sub) {
										$categories_sub_2 = $this->website_fct->get_categories_by_category($sub['id']);
										?>
                      <li><a href="<?php echo route_to("products/index/".$div['id'].'/'.$cat['id'].'/'.$sub['id']); ?>"><?php echo $sub['title']; ?></a>
                        <?php if(!empty($categories_sub_2)) {?>
                        <ul>
                          <?php foreach($categories_sub_2 as $sub1) {?>
                          <li><a href="<?php echo route_to("products/index/".$div['id'].'/'.$cat['id'].'/'.$sub['id'].'/'.$sub1['id']); ?>"><?php echo $sub1['title']; ?></a></li>
                          <?php } ?>
                        </ul>
                        <?php }?>
                      </li>
                      <?php }?>
                    </ul>
                    <?php }?>
                  </li>
                  <?php }?>
                </ul>
                <?php }?>
              </li>
            </ul>
          </div>
          <?php }?>
        </div>
        <?php }?>
      </div>
    </div>
  </div>
</div>
