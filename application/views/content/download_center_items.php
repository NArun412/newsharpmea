<?php if(!empty($documents)) {?>
<div class="download-center">
	<?php foreach($documents as $itm) {?>	
    <div class="download-item">
    	<h3 class="title"><?php echo $itm['title']; ?>
        	<span class="download-navs">
            	<a onclick="download_file(<?php echo $itm['id']; ?>)"><span class="download-icon"><i class="fa fa-arrow-down" aria-hidden="true"></i></span>
                <span class="download-btn"><?php echo lang("download"); ?></span></a>
            </span>
        </h3>
    </div>
    <?php }?>
</div>
<?php if($this->pagination->create_links()) {?>
 <div class="pagination-box"><?php echo $this->pagination->create_links(); ?></div><?php }?>
 <?php } else {?>
<span class="results-will-show-here"><?php echo lang("no results found.."); ?></span>
 <?php }?>
 