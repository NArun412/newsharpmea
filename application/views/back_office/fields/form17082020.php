<?php 

$enable_review = enable_review();
$user_can_publish = user_can_publish();

if(!isset($content_type))
$content_type = $this->module;
$mod_id = get_cell("content_type","id_content","used_name",$this->module); 
$module_connections = $this->fct->get_module_connections($mod_id);
$connected_modules = $this->fct->get_connected_modules($mod_id);
$view_only = view_only($content_type);

?>
<div class="page page-forms-common">
<div class="pageheader">
                        <h2><?php echo $title; ?> <span></span></h2>
                    <?php $link = admin_url('manage/display/'.$content_type);	?>  
                    <?php if(isset($module_connect) && !empty($module_connect)){?>
                    <?php $link=admin_url('manage/display/'.$module_connect['name'].'/'.$id_row_module_connect.'/'.$content_type); ?>
                    <?php } ?>  
    				<?php 
					if(in_array($content_type,static_modules()))
					$link = admin_url($content_type); ?>  
                    
                  
 <a role="button" href="<?php echo $link;?>" class="btn btn-success  pull-right">Back <i style="margin-top:3px;" class="fa fa-hand-o-left pull-left" aria-hidden="true"></i></a>
                        
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo admin_url('dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                
                                
                                <?php if(isset($module_connect) && !empty($module_connect)){?>
                                
                                 <li>
    <?php
    $link = admin_url('manage/display/'.$module_connect['name']);
	if(in_array($module_connect['name'],static_modules()))
	$link = admin_url($module_connect['name']);
	?>
                                    <a href="<?php echo $link; ?>">List <?php echo ucfirst($module_connect['name']); ?></a>
                                </li>
                                
									<?php } ?>
                                
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
<?php 
if(isset($this->id) && $this->id != 0 && $this->id != '') {
$module_connections = $this->fct->get_module_connections($mod_id);
//print_r($connected_modules);exit;
$render_menu = TRUE;
if(count($module_connections) > 0)
$render_menu = TRUE;
//if(seo_enabled($this->module))
//$render_menu = TRUE;
if($render_menu) {
?>
<div class="row"><div class="col-md-12">
<ul class="nav nav-tabs mt-20 mb-20">
    <li role="presentation" <?php if($this->router->method == 'edit') {?>class="active"<?php }?>><a href="<?php echo admin_url("manage/edit/".$this->module."/".$this->id); ?>">Edit</a></li>
    
<!--    <li role="presentation" <?php //if($this->router->method == 'seo') {?>class="active"<?php //}?>><a href="<?php //echo admin_url("manage/seo/".$this->module."/".$this->id); ?>">SEO</a></li>-->
    <?php if(!empty($module_connections)) {
		foreach($module_connections as $con) {
	?>
    	<li role="presentation" <?php if($this->router->method == 'mod' && $this->conmod == $con['name']) {?>class="active"<?php }?>><a href="<?php echo admin_url("manage/mod/".$this->module."/".$this->id."/".$con['name']); ?>"><?php echo display_field_name(ucfirst($con['name'])); ?></a></li>
    <?php 
		}
	}?>
    
    <li role="presentation" <?php if($this->router->method == 'translate') {?>class="active"<?php }?>><a href="<?php echo admin_url("manage/translate/".$this->module."/".$this->id); ?>">Translation</a></li>
    
</ul>
</div>
</div>
<?php } }?>
<div class="row">                  
<div class="col-md-12">
<?php
$attributes = array('class' => 'middle-forms');
	if($this->id != '')
	$action = admin_url('manage/submit/'.$content_type.'/'.$this->id);
	else
	$action = admin_url('manage/submit/'.$content_type);
	if(in_array($content_type,static_modules()))
	$action = admin_url($content_type.'/submit');

if($this->page_class == 'reviews_status') $action .= '?review=1';

echo form_open_multipart($action, $attributes); 
if(isset($id))
echo form_hidden('id', $id);
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

if(!$view_only && $enable_review) {
if(isset($id)) {
if(get_cell($content_type,'status','id_'.$content_type,$id) == 1) {
    echo '<div class="alert alert-success">Published</div>';
}
else {
    echo '<div class="alert alert-danger">Not Published</div>';
}
if(check_approval($content_type,$id,2)) {
echo '<div class="alert alert-success">Approved By Super Admin</div>';
}
else {
    echo '<div class="alert alert-danger">Not Approved By Super Admin</div>';
}
if(check_approval($content_type,$id,1)) {
    echo '<div class="alert alert-success">Approved By Admin</div>';
}
else {
    echo '<div class="alert alert-danger">Not Approved By Admin</div>';
}
}
}

if($view_only) {
	$this->load->view($this->admin_dir.'/fields/view',array("content_type"=>$content_type,"info"=>$info));
	?>
    <a href="<?php echo admin_url('manage/display/'.$content_type.'/'.$this->session->userdata("back_link")); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c">
<i class="fa fa-arrow-left"></i> Back</a>
    <?php
}
else {
//$this->load->view('back_office/'.$content_type.'/add');

if(file_exists('./application/views/'.$this->admin_dir.'/fields/custom/form-fields-'.$content_type.'.php')) {
	//exit('A1');
	$this->load->view($this->admin_dir.'/fields/custom/form-fields-'.$content_type);
}
else {
	//exit('b1');
	$this->load->view($this->admin_dir.'/fields/form-fields');
}
//print '<pre>'; print_r($info); exit;
if(!empty($connected_modules)) {
	if(!isset($info['module'])) $info['module'] = '';
	if(!isset($info['record'])) $info['record'] = '';
	
	$mod_val = set_value('module',$info['module']);
	//exit($mod_val);
	echo render_modules($connected_modules,$mod_val);
	$mod_records = array();
	//exit($mod_val);
	if($mod_val != '')
	$mod_records = $this->fct->getAll_cond($mod_val,'title',array('translation_id'=>0));
	//exit('test: '.$info['record']);
	echo '<div id="mod-record-wrapper">'.render_module_records($mod_records,$mod_val,set_value('record',$info['record'])).'</div>';
}

if(!$enable_review) {
	if(!isset($info["status"])) $info["status"] = 1;
	if($this->user_role)
	echo render_status(set_value("status",$info["status"]));
}
if($enable_review && user_can_approve()) {
	?>
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
if($this->user_role == 1 && check_approval($content_type,$id_1,2)) $selected = 1;
echo form_dropdown("status", $options,$selected, 'class="form-control"');
?>
<?php echo form_error("status","<span class='text-lightred'>","</span>"); ?>
</div>
<?php }?>

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
<!--<div class="col-md-6">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<?php if(!$view_only) { ?>
    <h2 class="custom-font text-cyan"><strong></strong>Share on Social Media</h2><?php }?>
</div>
<div class="tile-body">
</div>
</section>
</div>-->
</div>
</div>
<?php 
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message"); 
?>
