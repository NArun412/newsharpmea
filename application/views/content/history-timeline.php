<?php 
if(!empty($timeline)) {
foreach($timeline as $time) {
	$items = $time['items'];
	if(!empty($items)) {
	?>
<div class="timeline-item">
  <div>
    <div class="left-item-side"><?php echo $time['year']; ?></div>
    <div class="right-item-side">
      <ul>
	  	<?php foreach($items as $item) {?>
      		<li><?php echo $item['title']; ?></li>
      	<?php }?>
      </ul>
    </div>
  </div>
</div>
<?php }}}?>
