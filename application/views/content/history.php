<?php 
$ico_dir = 'right';
if($this->lang->lang() == 'ar') $ico_dir = 'left';
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
<div class="centered2">
<?php if(validate_user()) $this->load->view("includes/manage"); ?>
<h1 class="with-border">
<?php echo lang('Sharp History'); ?>
</h1>
<div class="span">
<div class="history-slider ">
	<?php foreach($products_history as $his) {?>
    	<div class="history-item">
        	<span class="year"><?php echo $his['year']; ?></span>
            <?php if(!empty($his['image'])) {?>
            <div class="img"><img src="<?php echo dynamic_img_url(); ?>history_of_products/image/200x200/<?php echo $his['image']; ?>" alt="<?php echo $his['title']; ?>" title="<?php echo $his['title']; ?>" /></div>
            <?php }?>
            <span class="title"><?php echo $his['title']; ?></span>
        </div>
    <?php }?>
</div>
</div>
</div>

<div class="span history-timeline" >
	<div class="centered2">
    	<div>
    	<div class="history-timeline-left">
        	<div class="timeline-circle-switch">
            	<a class="angle up" onclick="load_timeline_prev()"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
                <div class="years">
                	<span id="years-loader"></span>
                </div>
                <a class="angle down" onclick="load_timeline_next()"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
            </div>
            
            <div class="timeline-left-filter">
            	<ul>
                	<?php 
					$inc = $this->config->item('timeline_inc');
					for ($i=$get_history_min_year; $i<=$get_history_max_year; $i+=$inc) {
						$next_year = $i+$inc;
						
						$next_year_text = $next_year;
						if($next_year > $get_history_max_year) $next_year_text = lang("now");
?>
                    	<li id="timeline-year-<?php echo $i; ?>-<?php echo $next_year; ?>" data-minyear="<?php echo $i; ?>" data-maxyear="<?php echo $next_year_text; ?>"><a onclick="load_timeline(<?php echo $i; ?>,<?php echo $next_year; ?>)"><i class="fa fa-angle-<?php echo $ico_dir; ?>" aria-hidden="true"></i> <?php echo $i; ?> - <?php
						echo $next_year_text; 
						?></a></li>
                    <?php }?>
                </ul>
            </div>
        </div>
        <div class="history-timeline-right" id="timeline-loader">
       
        </div>
        </div>
    </div>
</div>


</div>

