<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
  <div class="centered4" >
  <?php if(validate_user()) $this->load->view("includes/manage"); ?>
    <div class="span">
      <div>
        <div class="careers-left">
        <?php foreach($all_envcsr as $env) {
			$link = route_to("about/environmental_csr/".$env['id']);
			?>
         <a href="<?php echo $link; ?>" class="left-menu-item <?php if($envcsr['id'] == $env['id']) {?>active<?php }?>" title="<?php echo $env['title']; ?>"><?php echo $env['title']; ?></a> 
        <?php }?>
         </div>
        <div class="careers-right">
          <div class="careers-box">
          <h1><?php echo $envcsr['title']; ?></h1>
            <div class="description"> <?php echo $envcsr['description']; ?> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
