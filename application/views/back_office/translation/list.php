<?php
$sego =$this->uri->segment(4);
$cond1["name"]= $section;
$gallery_status=$this->fct->getonecell("content_type","gallery",$cond1);
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?php echo admin_url('manage/display/'.$section); ?>">List of <?php echo $section; ?></a></li><span class="divider">/</span>
<!--<li><a href="<?php echo admin_url("translation/".$section."/".$id); ?>">Translation List</a></li><span class="divider">/</span>-->
<li class="active">Translate <?php echo $record["title"]; ?></li>
</ul> 
</div>
<div class="hundred pull-left" id="match2">   	
<table class="table table-striped" id="table-1">
<thead>
<? if($this->session->userdata("success_message")){ ?>
<tr><td colspan="3" align="center" style="text-align:center">
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
</td>
<tr>
<? } ?>
<tr>
<th>LANGUAGE</th>
<th>TITLE</th>
<th style="text-align:center;" width="250">ACTION</th>
</tr>
</thead>
<tbody>
<? 
if(count($languages) > 0){
$i=0;
foreach($languages as $val){
$i++; 
?>
<tr>
<td><? echo $val["title"]; if($val["index"] == $this->config->item('default_lang')) echo '<b>(default)</b>';?></td>
<td><? 
if(empty($record["title".getFieldLanguage($val['index'])]))
echo 'Not translated';
else
echo $record["title".getFieldLanguage($val['index'])];
 ?></td>
<td style="text-align:center">
<?php if(!empty($record["title".getFieldLanguage($val['index'])])) {?>
<?php echo anchor($this->lang->switch_uri($val['index'],$uri_string),'<i class="icon-search" ></i> Edit',array('class'=>'table-edit-link','title'=>'Edit Translate','html'=>true)); ?>
<?php } else {?>
<?php echo anchor(site_url('back_office/translation/create/'.$section.'/'.$record['id_'.$section].'/'.$val['index']),'<i class="icon-search" ></i> Translate',array('class'=>'table-edit-link','title'=>'Translate','html'=>true)); ?>
<?php }?>
<!--<a href="<? //= site_url('back_office/'.$section.'/edit/'.$record["id_".$section].'/translate');?>" class="table-edit-link" title="Edit Translate" >
<i class="icon-search" ></i> Edit</a>-->
</td>
</tr>
<? }  } else { ?>
<tr class='odd'><td colspan="3" style='text-align:center;'>No records available . </td></tr>
<?  } ?>
</tbody>
</table>  	
</div>
</div>
</div>   
</div>
<? 
$this->session->unset_userdata("success_message"); 
$this->session->unset_userdata("error_message"); 
?> 