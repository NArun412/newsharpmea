<script>
$(function(){
$('.open_validate').click(function(){
var id=this.id;
var sel="#validate_"+id;
$('.validate_cont').slideUp('fast');
$(sel).slideDown('fast');
});
$('.min_length').click(function () {
	var id = this.id;
	var sel='#min_num'+id;
	$(sel).toggle();
});
$('.max_length').click(function () {
	var id = this.id;
	var sel='#max_num'+id;
	$(sel).toggle();
});

$('#submit_validate').click(function(){
	$('#form_validate').submit();
});
});
</script>


<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view('back_office/includes/left_box'); ?>
</div>

<div class="span10 cont_h">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><?= $title; ?></li>
</ul>
</div>
<div class="hundred pull-left">
<div id="result"></div>
<div class="box-container">

<?
echo '123';exit;
foreach($info as $val){
if($val["type"] == 7){
$cond=array('id_content'=>$val["foreign_key"]);
$foreign_key=$this->fct->getonecell('content_type','name',$cond);
if(str_replace(" ","_",$foreign_key) ==  str_replace(" ","_",$table)){
	$foreign_key="parent";
$q="ALTER TABLE `".str_replace(" ","_",$table)."` ADD `id_parent"; } else {
$q="ALTER TABLE `".str_replace(" ","_",$table)."` ADD `id_".str_replace(" ","_",$foreign_key);	 }
} else {
$q="ALTER TABLE `".str_replace(" ","_",$table)."` ADD `".str_replace(" ","_",$val["name"]); }
if($val["type"] == 2)
$q.="` VARCHAR( ".$val["length"]." ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
elseif($val["type"] == 8)
$q.="` INT( ".$val["length"]." ) NULL DEFAULT '0';";
elseif($val["type"] == 9)
$q.="` DECIMAL( ".$val["length"]." ) NULL DEFAULT '0';";
elseif($val["type"] == 4)
$q.="` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
elseif($val["type"] == 3)
$q.="` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
elseif($val["type"] == 5)
$q.="` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
elseif($val["type"] == 1)
$q.="` DATE NULL ;";
elseif($val["type"] == 6)
$q.="` INT( 1 ) NOT NULL DEFAULT '0';";
elseif($val["type"] == 7)
$q.="` INT( 11 ) NOT NULL ;";
echo $q;exit;
$this->db->query($q);
if($val["type"] == 7){
$q1="ALTER TABLE `".str_replace(" ","_",$table)."` ADD  INDEX (`id_".str_replace(" ","_",$foreign_key)."`)";	
$this->db->query($q1);	
}

}
?>

<!-- validate attribte -->

<form id="form_validate" action="<?= base_url(); ?>back_office/install/next/<?= $id_content; ?>" method="post" class="middle-forms" enctype="multipart/form-data">
<? if($this->session->userdata('success_message')){ ?>
<div class="alert alert-success">
<?= $this->session->userdata('success_message'); ?>
</div>
<? } ?>
<? if($this->session->userdata('error_message')){ ?>
<div class="alert alert-error">
<?= $this->session->userdata('error_message'); ?>
</div>
<? } ?>
</p>
<fieldset>
<legend></legend>
<div class="accordion" id="accordion3">
<?
$i=0;
foreach($info as $val){ 
$i++;
if($i % 2 !=0 ) $even='even'; else $even='';
?>
<div class="accordion-group">
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapse1_<?=$i;?>">
<?= $val["name"]; ?>
<? $validation_array=explode("|",$val["validation"]); ?>
</a>
</div>
<div id="collapse1_<?=$i;?>" class="accordion-body collapse <? if($i == 1) echo "in";?>">
<div class="accordion-inner">
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
<?	
}
?>
</div>
<p class="align-right">
<input type="hidden" name="id_content" value="<?= $id_content; ?>" />
<!--<a class="button cur" id="submit_validate"  style="cursor:pointer;">
<span>Next&nbsp;&raquo;</span>
</a>-->
    <ul class="pager">
    <li class="previous"><a href="<?=base_url();?>back_office/control/manage/<?=$id_content;?>"  >Prev</a></li>
    <li class="next"><a href="#" id="submit_validate" >Next</a></li>
    </ul>
</p>
<span class="clearFix">&nbsp;</span>
</fieldset>
</form>

</div>
</div>
</div>
<span class="clearFix">&nbsp;</span>     
</div>

