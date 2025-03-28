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

	$action = admin_url('import/submit_import');
	if(in_array($content_type,static_modules()))
	$action = admin_url($content_type.'/submit');

echo form_open_multipart($action, $attributes); 
if(isset($id)){
	echo form_hidden('id', $id);
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





echo '<div class="form-group">';
echo form_label('<b>UPLOAD FILE:*</b>', 'UPLOAD FILE');
echo form_upload(array("name" => "import_file", "class" => "filestyle", "data-buttonText" => "Find file", "data-iconName" => "fa fa-inbox"));
echo form_error("import_file","<span class='text-lightred'>","</span>");
echo "</div>";
echo form_hidden('content_type', $content_type);
echo form_label('<small class="text-orange">&nbsp;Upload Excel file, with the following fields, <u><b>'.$import_row["fields"].'</b></u>.</small>');
?>

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
