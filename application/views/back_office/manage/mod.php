<div class="page page-forms-common">
<div class="pageheader">

                        <h2><?php echo $title; ?> <span></span></h2>

                        <div class="page-bar">

                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo site_url('back_office/home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('back_office/control');?>">Manage Content Type</a>
                                </li>
                                <li>
                                    <?php echo $title; ?>
                                </li>
                            </ul>
                            
                        </div>

</div>

<?php
if(isset($this->id) && $this->id != 0 && $this->id != '') {
$mod_id = get_cell("content_type","id_content","used_name",$this->module); 
$connected_modules = $this->fct->get_module_connections($mod_id);

$render_menu = TRUE;
if(count($connected_modules) > 0)
$render_menu = TRUE;
//if(seo_enabled($this->module))
//$render_menu = TRUE;
if($render_menu) {
?>
<div class="row"><div class="col-md-12">
<ul class="nav nav-tabs mt-20 mb-20">
<?php if($this->users_m->check_user_record_access($this->module,$this->id,$this->user_info)) {?>
    <li role="presentation" <?php if($this->router->method == 'edit') {?>class="active"<?php }?>><a href="<?php echo admin_url("manage/edit/".$this->module."/".$this->id); ?>">Edit</a></li><?php }?>
    <?php if(!empty($connected_modules)) {
		foreach($connected_modules as $con) {
			if(has_permission($con['name'],'view')) {
	?>
    	<li role="presentation" <?php if($this->router->method == 'mod' && $this->conmod == $con['name']) {?>class="active"<?php }?>><a href="<?php echo admin_url("manage/mod/".$this->module."/".$this->id."/".$con['name']); ?>"><?php echo display_field_name(ucfirst($con['name'])); ?></a></li>
    <?php 
		}
		}
	}?>
       <li role="presentation" <?php if($this->router->method == 'translate') {?>class="active"<?php }?>><a href="<?php echo admin_url("manage/translate/".$this->module."/".$this->id); ?>">Translation</a></li>
    
</ul>
</div>
</div>
<?php } }?>
<div class="row">
<div class="col-md-6">
<?php 
if(!isset($info)) $info = array();
$this->load->view($this->admin_dir."/fields/table-connect",array("info"=>$info,"content_type"=>$this->conmod)); ?>
</div>
<div class="col-md-6">
<?php 
$this->load->view($this->admin_dir."/fields/form-connect",array("info"=>$info,"content_type"=>$this->module)); ?>
</div>
</div>
</div>
