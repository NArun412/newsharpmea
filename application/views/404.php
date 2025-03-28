<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
  <div class="centered2">
    <div class="span">
      <div class="description"><?php echo $intro['description']; ?></div>
    </div>
  </div>
</div>
