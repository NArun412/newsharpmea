<div class="page page-forms-common">
  <div class="pageheader">
    <h2><?php echo $title; ?> <span></span></h2>
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li> <a href="<?php echo admin_url('home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a> </li>
        <li> <a href="<?php echo admin_url('control');?>">Manage Content Type</a> </li>
        <li> <?php echo $title; ?> </li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?php
$attributes = array('class' => 'middle-forms');
echo form_open_multipart(admin_url('control/submit_group'), $attributes); 
?>
      <section class="tile">
        <div class="tile-header dvd dvd-btm">
          <h1 class="custom-font text-cyan"><strong></strong> Please complete the form below. Mandatory fields marked <em>*</em></h1>
          <ul class="controls">
            <li class="dropdown"> <a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown"> <i class="fa fa-cog"></i> <i class="fa fa-spinner fa-spin"></i> </a>
              <ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
                <li> <a role="button" tabindex="0" class="tile-toggle"> <span class="minimize"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;Minimize</span> <span class="expand"><i class="fa fa-angle-up"></i>&nbsp;&nbsp;&nbsp;Expand</span> </a> </li>
                <li> <a role="button" tabindex="0" class="tile-refresh"> <i class="fa fa-refresh"></i> Refresh </a> </li>
                <li> <a role="button" tabindex="0" class="tile-fullscreen"> <i class="fa fa-expand"></i> Fullscreen </a> </li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="tile-body">
          <?php
if(isset($id)){
	echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('name'=>'');
}

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
?>
          <div class="form-group">
            <label class="field-title">Group Name<em>*</em>:</label>
      <input type="text" class="form-control" name="name" value="<?= set_value('name',$info["name"]); ?>" />
            <?= form_error('name','<span class="text-lightred">','</span>'); ?>
          </div>
          <hr class="line-dashed line-full"/>
          <div class="form-group">
            <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ><i class="fa fa-arrow-right"></i> Submit</button>
            <a href="<?php echo admin_url('control/menu_groups'); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c"><i class="fa fa-arrow-left"></i> Cancel</a> </div>
        </div>
      </section>
      <?php echo form_close(); ?> </div>
  </div>
</div>
<?php
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message"); 
?>
