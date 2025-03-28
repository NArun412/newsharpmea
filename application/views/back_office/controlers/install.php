
<?php
$sego =$this->uri->segment(4);
$gallery_status="0";
?>
<div class="page page-tables-datatables">
<div class="pageheader">
                        <h2><?= $title; ?><span></span></h2>
                        <div class="page-bar">

                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo site_url('back_office/home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                <li>
                                    <?= $title; ?>
                                </li>
                            </ul>

                        </div>
                    </div>                  
<div class="row">
<!-- col -->
<div class="col-md-12">	
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font text-cyan"><strong></strong><?php echo $title; ?></h1>
<ul class="controls">
<li class="dropdown">
<a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown">
<i class="fa fa-cog"></i>
<i class="fa fa-spinner fa-spin"></i>
</a>
<ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
<li>
<a role="button" tabindex="0" class="tile-toggle">
<span class="minimize"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;Minimize</span>
<span class="expand"><i class="fa fa-angle-up"></i>&nbsp;&nbsp;&nbsp;Expand</span>
</a>
</li>
<li>
<a role="button" tabindex="0" class="tile-refresh">
<i class="fa fa-refresh"></i> Refresh
</a>
</li>
<li>
<a role="button" tabindex="0" class="tile-fullscreen">
<i class="fa fa-expand"></i> Fullscreen
</a>
</li>
</ul>
</li>
</ul>
</div>
<?php
$attributes = array('name' => 'list_form','id' => 'form_validate');
echo form_open_multipart('back_office/install/next/'.$id_content, $attributes); 
?>     
<div id="result"></div>                            
<div class="tile-body">
<?php if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?php $this->session->userdata("success_message"); ?>
</div>
<?php } ?>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" >
<?php
$i=0;
foreach($info as $val){ 
$i++;
if($i % 2 !=0 ) $even='even'; else $even='';
?>
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading<?php echo $i; ?>">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
<?= $val["name"]; ?>
<? $validation_array=explode("|",$val["validation"]); ?>
</a>
</h4>
</div>
<div id="collapse<?php echo $i; ?>" class="panel-collapse collapse <?php if($i == 1) echo "in"; ?>" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>">
<div class="panel-body">
<ul class="nav nav-list">
<li><a><input type="checkbox" name="required_<?=$i;?>" class="validation" <? if (in_array("required", $validation_array)) { echo 'checked="checked"'; } ?> />&nbsp;required</a></li>
<li><a><input type="checkbox" name="min_length_<?=$i;?>" class="min_length"  id="<?=$i;?>" />&nbsp;min_length 
<span id="min_num<?= $i; ?>" style="display:none">&nbsp;|&nbsp;&nbsp;Numbre Of Characters&nbsp;
<input type="text" name="min_num_<?=$i;?>" class="" /></span>
</a>
</li>
<li><a><input type="checkbox" name="max_length_<?=$i;?>" class="max_length"  id="<?=$i;?>"   />&nbsp;max_length 
<span id="max_num<?=$i;?>" style="display:none">&nbsp;|&nbsp;&nbsp;Numbre Of Characters&nbsp;
<input type="text" name="max_num_<?=$i;?>" class="" /></span>
</a></li>
<li><a><input type="checkbox" name="valid_email_<?=$i;?>" class="validation" <? if (in_array("valid_email", $validation_array)) { echo 'checked="checked"'; } ?>  />&nbsp;valid_email</a></li>
</ul>
</div>
</div>
</div>
<?php } ?>                            
</div>
</div>
<div class="tile-footer">
<input type="hidden" name="id_content" value="<?= $id_content; ?>" />
    <ul class="pager m0">
    <li class="previous"><a href="<?=base_url();?>back_office/control/manage/<?=$id_content;?>" class="btn btn-rounded-10 btn-ef btn-ef-2 btn-ef-2-amethyst btn-ef-2a mb-10" >Prev</a></li>
    <li class="next"><a href="#" id="submit_validate" class="btn btn-rounded-10 btn-ef btn-ef-2 btn-ef-2-amethyst btn-ef-2a mb-10" >Next</a></li>
    </ul>
</div>
<?php echo form_close();  ?>
</section>
</div>
</div>
</div>
<?php
$this->session->unset_userdata("success_message"); 
$this->session->unset_userdata("error_message"); 
?>