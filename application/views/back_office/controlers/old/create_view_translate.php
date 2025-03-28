<?php
$list_view='<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function delete_row(id,id_parent){
window.location="<?= base_url(); ?>back_office/'.$table_name.'/delete/"+id+"/"+id_parent;
return false;
}

$(function(){
$("#table-1").tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$("#result").load("<?= base_url(); ?>back_office/'.$table_name.'/sorted?"+ser);
}
});

$("#match2 input[name=\'search\']").live(\'keyup\', function(e){
e.preventDefault();
var id =this.id;
$(\'#match2 tbody tr\').css(\'display\',\'none\');
var searchtxt = $.trim($(this).val());
var bigsearchtxt = searchtxt.toUpperCase(); 
var smallsearchtxt = searchtxt.toLowerCase();
var fbigsearchtxt = searchtxt.toLowerCase().replace(/\b[a-z]/g, function(letter) {
return letter.toUpperCase();
});
if(searchtxt == ""){
$(\'#match2 tbody tr\').css(\'display\',"");	
} else {
$(\'#match2 tbody tr td.\'+id+\':contains("\'+searchtxt+\'")\').parent().css(\'display\',"");
$(\'#match2 tbody tr td.\'+id+\':contains("\'+bigsearchtxt+\'")\').parent().css(\'display\',"");
$(\'#match2 tbody tr td.\'+id+\':contains("\'+fbigsearchtxt+\'")\').parent().css(\'display\',"");
$(\'#match2 tbody tr td.\'+id+\':contains("\'+smallsearchtxt+\'")\').parent().css(\'display\',"");
}
});

$("#show_items").change(function(){
	var val = $(this).val();
	$("#result").html(val);
	$("#show_items").submit();
});

});
</script>';
$content_name = $table_name;
$cond1["name"]=str_replace("_"," ",$content_name);
$id_content=$this->fct->getonecell("content_type","id_content",$cond1);
$gallery_status=$this->fct->getonecell("content_type","gallery",$cond1);
$list_view.='<?php
$sego =$this->uri->segment(4);
$gallery_status="'.$gallery_status.'";
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view("back_office/includes/left_box"); ?>
</div>

<div class="span10">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?php echo site_url(\'back_office/'.$table_name.'/\'.$this->session->userdata("back_link")); ?>" ><b>List '.str_replace("_"," ",$content_name).'</b></a><span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
<? if ($this->acl->has_permission(\''.$table_name.'\',\'add\')  && count($languages) > 0){ ?>
<li class="pull-right">
<a href="<?= site_url(\'back_office/'.$table_name.'/add_translation/\'.$info["id_'.$table_name.'"]); ?>" id="topLink" class="btn btn-info top_btn" title="">Add Translation</a>
</li><? } ?>
</ul> 
</div>
<div class="hundred pull-left" id="match2">   
<div id="result"></div>   		
<table class="table table-striped" id="table-1">
<thead>
<? if($this->session->userdata("success_message")){ ?>
<tr><td colspan="'.(count($attr)+3).'" align="center" style="text-align:center">
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
</td>
</tr>
<? } ?>
<tr>
<td width="2%"><span class="yellow">LANG</span></td>
<th>
<a href="<?= site_url(\'back_office/'.$table_name.'/index/title\'); ?>" class="<? if($sego == "title") echo \'green\'; else echo \'order_link\'; ?>" >
TITLE
</a>
</th>';
foreach($attr as $val){
if($val["type"] != 4 && $val["type"] != 5 && $val["type"] != 7)
$list_view.='
<th>
<a href="<?= site_url(\'back_office/'.$table_name.'/index/'.str_replace(' ','_',$val["name"]).'\'); ?>" class="<? if($sego == "'.$val["name"].'") echo \'order_active\'; else echo \'order_link\'; ?>" >
'.strtoupper(str_replace("_"," ",$val["name"])).'
</a>
</th>';
else
$list_view.='
<th>'.strtoupper(str_replace("_"," ",$val["name"])).'</th>';
}
$list_view.='<th style="text-align:center;" width="150">ACTION</th></tr>
<tr>
<td></td>
<td><input type="text" name="search" class="search_box" id="title_search" /></td>
';
foreach($attr as $val){ 
$str_replace = str_replace(" ","_",$val["name"]);
$list_view .= '<td><input type="text" name="search" class="search_box" id="'.$str_replace.'_search" /></td>';	
}
$list_view.='
<td></td>
</tr>
</thead><tfoot><tr>
<td class="col-chk"></td>
<td colspan="'.(count($attr)+2).'"></td>
</tr>
</tfoot>
<tbody>
<? 
if(count($translations) > 0){
$i=0;
foreach($translations as $val){
$i++; 
?>
<tr id="<?=$val["id_'.$table_name.'"]; ?>">
<td class="col-chk"><? echo $val["lang"]; ?></td>
<td class="title_search">
<? echo $val["title"]; ?>
</td>';
foreach($attr as $v){
$list_view.='
<td class="'.str_replace(" ","_",$v["name"]).'_search">
';
if($v["type"] == 7){
$cond001=array("id_content" => $v["foreign_key"]);
$content001 = $this->fct->getonecell("content_type","name",$cond001);
if(str_replace(" ","_",$content001) == $table_name)
$frn1 = "parent";
else
$frn1 = str_replace(" ","_",$content001);
$list_view.='<? if($val["id_'.$frn1.'"] != 0){
$cond=array("id_'.str_replace(" ","_",$content001).'" => $val["id_'.$frn1.'"]);
echo $this->fct->getonecell("'.str_replace(" ","_",$content001).'","title",$cond); } else { echo "<small>Not available</small>"; }   ?>';
}
elseif($v["type"] == 6 ){ 
$list_view.='<? if($val["'.str_replace(" ","_",$v["name"]).'"] == 1)
echo "<span class=\' label label-success\' >Active</span>";
else
echo "<span class=\' label label-important \' >InActive</span>";   ?>';
}
elseif($v["type"] == 4){ 
$list_view.='<? if($val["'.str_replace(" ","_",$v["name"]).'"] != ""){ ?>
<a href="<?= base_url(); ?>uploads/'.$table_name.'/<?=$val["'.str_replace(" ","_",$v["name"]).'"];?>" class="blue gallery" title="<?=$val["title"];?>" >
show image</a>&nbsp;&nbsp;&nbsp;';
$list_view.='<a class="cur" onclick="if(confirm(\'Are you sure you want to delete this image ?\')){ window.location=\' <?= base_url(); ?>back_office/'.$table_name.'/delete_image/'.str_replace(" ","_",$v["name"]).'/<?= $val["'.str_replace(" ","_",$v["name"]).'"];?>/<?=$val["id_'.$table_name.'"]; ?>\' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { 
echo "<small class=\'blue\'>No Image Available</small>"; } ?>';
} 
elseif($v["type"] == 5){
$list_view.=' <? if( $val["'.str_replace(" ","_",$v["name"]).'"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/'.$table_name.'/<?=$val["'.str_replace(" ","_",$v["name"]).'"]; ?>" class="blue" title="<?=$val["title"];?>" target="_balnk" >
show file</a>&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm(\'Are you sure you want to delete this file ?\')){ window.location=\' <?= base_url(); ?>back_office/'.$table_name.'/delete_image/'.str_replace(" ","_",$v["name"]).'/<?=$val["'.str_replace(" ","_",$v["name"]).'"];?>/<?=$val["id_'.$table_name.'"];?>\' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<small class=\'blue\'>No File Available</small>"; } ?>';	
}
elseif($v["type"] == 1){
$list_view.='<small><? echo $this->fct->date_out_formate($val["'.str_replace(" ","_",$v["name"]).'"]);?></small>';	
}
else { 
$list_view.='<? echo $val["'.str_replace(" ","_",$v["name"]).'"]; ?>';
}
$list_view.='
</td>';

} 
$list_view.='
<td style="text-align:left">';

$list_view.='
<div class="btn-group" style="width:100px;">
<button class="btn btn-default btn-flat" type="button">Action</button>
<button data-toggle="dropdown" class="btn btn-default btn-flat dropdown-toggle" type="button">
<span class="caret"></span>
<span class="sr-only"></span>
</button>
<ul class="dropdown-menu" role="menu">';
$list_view.='
<? if ($this->acl->has_permission(\''.$table_name.'\',\'index\')){ ?>
<li><a href="<?= site_url(\'back_office/'.$table_name.'/view_translation/\'.$val["id_'.$table_name.'"]);?>" class="table-edit-link">
View</a></li>
<? } ?>
<? if ($this->acl->has_permission(\''.$table_name.'\',\'edit\')){ ?>
<li>
<a href="<?= site_url(\'back_office/'.$table_name.'/edit_translation/\'.$val["id_'.$table_name.'"]);?>" class="table-edit-link" >
Edit</a></li>
<? } ?>
<? if ($this->acl->has_permission(\''.$table_name.'\',\'delete\')){ ?>
<li>
<a onclick="if(confirm(\'Are you sure you want delete this item ?\')){ delete_row(\'<?=$val["id_'.$table_name.'"];?>\',\'<?=$val["id_parent"];?>\'); }" class="table-delete-link cur">
Delete</a></li>
<? } ?>
</ul>
</div>
</td>
</tr>
<? }  } else { ?>
<tr class=\'odd\'><td colspan="'.(count($attr)+3).'" style=\'text-align:center;\'>No records available . </td></tr>
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
?> ';