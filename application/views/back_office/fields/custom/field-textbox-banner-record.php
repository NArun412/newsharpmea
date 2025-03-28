<?php $fld = clean_field_name($attr['name']); ?>
<div class="form-group">
<?php echo form_label('<b>'.ucfirst($attr['display_name']).'</b>'); ?>
<?php if(!empty($attr['hint'])) {?>
<?php echo form_label('<small class="yellow">'.$attr['hint'].'</small>'); ?>
<?php }?>
<?php
//echo $field_id; exit;
$selected = 0;
$options = array(''=>'- select -');
$arr1 = array();
$page = 0;
if( (isset($info['page']) && $info['page'] != 0)) $page = $info['page'];
elseif( $this->input->get("page") != '') {$page = $this->input->get("page");}
$arr1 = get_banner_page_records($page);

if(!empty($arr1)) {
	foreach($arr1 as $ar)
	$options[$ar['id']] = $ar['title'];
}
if( ($selected == 0 || $selected == '') && $this->input->get('record') != '') {
	//exit("AA: ".$selected);
	$selected = $this->input->get("record");
}
//print '<pre>';print_r($options);exit;
echo form_dropdown($fld, $options,$selected, 'class="form-control"');
?>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>