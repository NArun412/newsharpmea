<?php if(isset($breadcrumbs) && !empty($breadcrumbs)) {?>
<ul class="breadcrumbs">
<?php foreach($breadcrumbs as $breadcrumb) {
	if(isset($breadcrumb['link'])) {
	?>
    <li><a title="<?php echo $breadcrumb['title']; ?>" href="<?php echo $breadcrumb['link']; ?>"><?php echo $breadcrumb['title']; ?></a></li>
      <li>|</li>
    <?php } else {?>
    <li><span><?php echo $breadcrumb['title']; ?></span></li>
    <?php }?>
<?php }?>

    </ul>
    <?php }?>