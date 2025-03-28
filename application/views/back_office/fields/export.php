<?php 
if(!isset($content_type))
$content_type = $this->module; 
$view_only = view_only($content_type);

$tables = $this->db->list_tables();
?>
<div class="page page-forms-common">
<div class="pageheader">
                        <h2><?php echo $title; ?> <span></span></h2>
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo admin_url('dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                <li>
                                    <?php
                                    	$link = admin_url('manage/display/'.$content_type);
	if(in_array($content_type,static_modules()))
	$link = admin_url($content_type);
	?>
                                    <a href="<?php echo $link; ?>">List <?php echo ucfirst($content_type); ?></a>
                                </li>
                                <li>
                                    <?php echo $title; ?>
                                </li>
                            </ul> 
                        </div>
</div> 

<div class="row">                  
<div class="col-md-12">
<?php
$attributes = array('class' => 'middle-forms');

	$action = admin_url('manage/submit_import/export');
	if(in_array($content_type,static_modules()))
	$action = admin_url($content_type.'/submit');

echo form_open_multipart($action, $attributes); 
if(isset($id)){
	echo form_hidden('id', $id);
} else {
	$info["tables"] = $tables[0];
	$info["fields"] = "";
}
?>
<section class="tile">
<div class="tile-header dvd dvd-btm">
<?php if(!$view_only) { ?>
    <h1 class="custom-font text-cyan"><strong></strong> Please complete the form below. Mandatory fields marked <em>*</em></h1><?php }?>
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
<div class="tile-body">
<?php

if($this->session->userdata("success_message")){ 
echo '<div class="alert alert-success">';
echo $this->session->userdata("success_message");
echo '</div>';
}
if($this->session->userdata("error_message")){
echo '<div class="alert alert-error">';
echo $this->session->userdata("error_message"); 
echo '</div>';
}

if(!$view_only) {
if(isset($id)) {
if(set_value("status",$info["status"]) == 1)
echo '<div class="alert alert-success">Approved</div>';
else
echo '<div class="alert alert-danger">Not Approved</div>';
}
}

if($view_only) {
	$this->load->view($this->admin_dir.'/fields/view',array("content_type"=>$content_type,"info"=>$info));
	?>
    <a href="<?php echo admin_url($content_type.'/'.$this->session->userdata("back_link")); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c">
<i class="fa fa-arrow-left"></i> Back</a>
    <?php
}
else {
//$this->load->view('back_office/'.$content_type.'/add');
$this->load->view($this->admin_dir.'/fields/form-fields');

if(!isset($info["status"])) $info["status"] = 0;

//if($this->user_role <= 1)
//echo render_status(set_value("status",$info["status"]));
?>
<div class="form-group">
<?php echo form_label('<b>Tables&nbsp;<em class="red">*</em>:</b></b>'); ?>
<?php
$options = array();
foreach($tables as $table){
$options[$table] = str_replace("_", " ", ucfirst($table));
}

$selected = set_value("tables",$info["tables"]);
echo form_dropdown("tables", $options, $selected, 'class="form-control chosen-select"');
?>
<?php echo form_error("tables","<span class='text-lightred'>","</span>"); ?>
</div>

<?php $fld = "fields"; ?>
<div class="form-group" id="fields">
<?php echo form_label('<b>Fields:</b>'); ?>
<?php //if(!empty($attr['hint'])) {?>
<?php echo form_label('<small class="text-orange">&nbsp;</small>'); ?>
<?php //}?>
<?php

if ($this->db->table_exists(set_value("tables",$info["tables"])))
$fields = $this->db->list_fields(set_value("tables",$info["tables"]));
$selected = explode(',',set_value("fields",$info["fields"]));
$items = $fields; 
?>
<select name="<?php echo $fld; ?>[]" multiple="multiple"  class="form-control searchable" <?php //if(!empty($attributes)) echo $attributes; ?>>
<?php
foreach($items as $v){ 
$cl = '';
if(in_array($v,$selected)) $cl = 'selected="selected"';
?>
<option value="<?php echo $v; ?>" <?php echo $cl; ?>><?php echo $v; ?></option>
<?
}
?>
</select>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>
<?php
if($this->user_role <= 1)
echo render_status(set_value("status",$info["status"]));

if($this->user_role <= 3 && $this->user_role > 1){ ?>

<?php } ?>

<hr class="line-dashed line-full"/>
<div class="form-group">
<?php if ($this->uri->segment(3) != "view" ){ ?>
<button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ><i class="fa fa-arrow-right"></i> Submit</button>
<?php } ?>
<?php if(in_array($content_type,static_modules())) {?>
<a href="<?php echo admin_url($content_type); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c">
<i class="fa fa-arrow-left"></i> Cancel</a>
<?php } else {?>
<a href="<?php echo admin_url('manage/display/'.$content_type); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c">
<i class="fa fa-arrow-left"></i> Cancel</a>
<?php }?>
<?php }?>
</div>
</div>
</section>
<!-- SEO Details -->
	<?php 
	//if(seo_enabled($content_type)) echo render_seo_fields(); ?>
<?php echo form_close(); ?>
</div>

</div>
</div>
<?php 
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message"); 
?>
