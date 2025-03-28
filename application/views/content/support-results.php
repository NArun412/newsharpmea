<ul>
	<?php foreach($categories as $cat) {
		$c = '';
		if(isset($selected_category) && $selected_category == $cat['id']) $c = 'active';
		?>
    	<li><a class="<?php echo $c; ?>" onclick="get_support_items(this,<?php echo $cat['id']; ?>)"><?php echo $cat['title']; ?></a></li>
    <?php }?>
</ul>