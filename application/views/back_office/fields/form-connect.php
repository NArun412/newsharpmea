<?php 
//print '<pre>'; print_r($info_edit);exit;

$enable_review = enable_review();
$user_can_publish = user_can_publish();
  //exit($content_type);
if(!isset($content_type))
$content_type = $this->module; 
$view_only = view_only($content_type);

?>
<?php
$attributes = array('class' => 'middle-forms');
$ext = '';
	$action = admin_url('manage/submit_connect/'.$this->module.'/'.$this->id.'/'.$this->conmod.$ext);
	//if(in_array($content_type,static_modules()))
	//$action = admin_url($content_type.'/submit');
echo form_open_multipart($action, $attributes); 
if(isset($id))
echo form_hidden('id', $id);
/*if(isset($this->id))
echo form_hidden('foreign_id', $this->id);
if(isset($this->conmod))
echo form_hidden('parent_table', $this->conmod);*/
echo form_hidden('module', $content_type);
echo form_hidden('record', $this->id);
?>
<section class="tile">
<div class="tile-header dvd dvd-btm">
<?php if(!$view_only) { ?>
    <h1 class="custom-font text-cyan"><strong></strong> Please complete the form below. Mandatory fields marked <em>*</em></h1><?php }?>
    <div id="form-connect-errors custom-font text-cyan"></div>
    <ul class="controls">
        <li class="dropdown">
            <a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown">
                <i class="fa fa-cog"></i>
                <i class="fa fa-spinner fa-spin"></i>
            </a>
            <!--<ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
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
            </ul>-->
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
  
if(check_approval($this->conmod,$id,2))
echo '<div class="alert alert-success">Approved By Super Admin</div>';
else
echo '<div class="alert alert-danger">Not Approved by Super Admin</div>';

if(check_approval($this->conmod,$id,4))
echo '<div class="alert alert-success">Approved By Admin</div>';
else
echo '<div class="alert alert-danger">Not Approved by Admin</div>';

}
}
if($view_only) {
	$this->load->view($this->admin_dir.'/fields/view',array("content_type"=>$this->conmod,"info"=>$info_edit));
?>
<a href="<?php echo admin_url('manage/display/'.$content_type.'/'.$this->session->userdata("back_link")); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c">
<i class="fa fa-arrow-left"></i> Back</a>
    <?php
}
else {
//$this->load->view('back_office/'.$content_type.'/add');
//$this->load->view($this->admin_dir.'/fields/form-fields');
//print '<pre>'; print_r($info_edit); exit;
if(file_exists('./application/views/'.$this->admin_dir.'/fields/custom/form-fields-'.$this->conmod.'.php'))
$this->load->view($this->admin_dir.'/fields/custom/form-fields-'.$this->conmod);
else
$this->load->view($this->admin_dir.'/fields/form-fields');
if(!isset($info_edit["status"])) $info_edit["status"] = 1;

//if($this->user_role)
//echo render_status(set_value("status",$info_edit["status"]));

if(!$enable_review) {
	if(!isset($info["status"])) $info["status"] = 1;
	if($this->user_role)
	echo render_status(set_value("status",$info["status"]));
}
if($enable_review && user_can_publish()) {?>
<div class="form-group">
<?php echo form_label('<b>Your Approval&nbsp;<em class="red">*</em>:</b></b>'); ?>
<?php
$options = array(
	1 => "Approved",
	0 => "Not Approved"
);
$selected = 0;
if(!isset($info["id_".$content_type])) $id_1 = 0;
else $id_1 = $info["id_".$content_type];
if($this->user_role == 3 && check_approval($content_type,$id_1,4)) $selected = 1;
if($this->user_role == 2 && check_approval($content_type,$id_1,2)) $selected = 1;
if($this->user_role == 1) $selected = 1;
echo form_dropdown("approval", $options,$selected, 'class="form-control"');
?>
<?php echo form_error("approval","<span class='text-lightred'>","</span>"); ?>
</div>
<?php }?>

<hr class="line-dashed line-full"/>
<div class="form-group">
<?php if ($this->uri->segment(3) != "view" ){ ?>
<button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ><i class="fa fa-arrow-right"></i> Submit</button>
<?php } ?>
<!--<?php //if(in_array($content_type,static_modules())) {?>
<a href="<?php //echo admin_url($content_type); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c">
<i class="fa fa-arrow-left"></i> Cancel</a>
<?php //} else {?>
<a href="<?php //echo admin_url('manage/display/'.$content_type); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c">
<i class="fa fa-arrow-left"></i> Cancel</a>
<?php //}?>-->
<?php }?>
</div>
</div>
</section>
<!-- SEO Details -->
	<?php 
	//if(seo_enabled($content_type)) echo render_seo_fields(); ?>
<?php echo form_close(); ?>
<?php 
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message"); 
?>
