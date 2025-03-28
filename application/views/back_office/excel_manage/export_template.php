<div class="page page-forms-common">
<div class="pageheader">
                        <h2><?php echo $title; ?> <span></span></h2>
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo admin_url('dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
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
$attributes = array('class' => 'middle-forms','id'=>'exportForm');
echo form_open_multipart(admin_url('excel_manage/do_export'), $attributes); 
echo form_hidden('module',$module);
echo form_hidden('offset',0);
$get_all = $this->input->get();
if(!empty($get_all)) {
	foreach($get_all as $k => $v) {
		//if(!empty($v))
		echo form_hidden($k,$v);
	}
}
?>
<section class="tile">
<div class="tile-header dvd dvd-btm">
    <h1 class="custom-font text-cyan"><strong></strong> Export Module</h1>
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
?>
  <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <?php
				  foreach($export_fields as $v1){
				  $field_key = str_replace(" ","_",$v1["name"]);
				  $field_name = ucfirst(str_replace("_"," ",$v1["name"]));
				  ?>
                  <th><?php echo $field_name; ?></th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
              <tr><td colspan="<?php echo count($export_fields); ?>" align="center">Click on start button</td></tr>
              </tbody>
              </table>
<hr class="line-dashed line-full"/>
<div class="form-group">

<div id="export-loader">
</div>
<button class="btn btn-danger btn-ef btn-ef-3 btn-ef-3c" id="cancel_export" style="display:none;" ><i class="fa fa-arrow-right"></i> Cancel</button>
<button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" id="start_export" ><i class="fa fa-arrow-right"></i> Start</button>
</div>
</div>
</section>
<?php echo form_close(); ?>
</div>
</div>
</div>
<?php 
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message"); 
?>
