<?
$content_name = $table_name;
$cond1["name"]=str_replace("_"," ",$content_name);
$id_content=$this->fct->getonecell("content_type","id_content",$cond1);
$add_view='<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10" >
<div class="span10-fluid" >
<?
$ul = array(
            anchor(\'back_office/'.$table_name.'/\'.$this->session->userdata("back_link"),\'<b>List '.str_replace("_"," ",$content_type).'</b>\').\'<span class="divider">/</span>\',
            anchor(\'back_office/'.$table_name.'/translate/\'.$id_parent,\'<b>Translations</b>\').\'<span class="divider">/</span>\',
			$title => array(\'li_attributes\' => \'class = "active"\', \'contents\' => $title),
            );

$ul_attributes = array(
                    \'class\' => \'breadcrumb\'
                    );
echo ul($ul, $ul_attributes);
?>
</div>
<div class="hundred pull-left">   
<?
$attributes = array(\'class\' => \'middle-forms\');
echo form_open_multipart(\'back_office/'.$table_name.'/submit\', $attributes); 
echo \'<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em></p>\';
if(isset($id_parent)){
echo form_hidden(\'id_parent\', $id_parent); }
if(isset($id)){
echo form_hidden(\'id\', $id);
} else {
';
foreach($attr as $val){
$add_view.='$info["'.str_replace(" ","_",$val["name"]).'"] = "";';
}
$add_view.='
$info["title"] = "";
$info["meta_title"] = "";
$info["meta_description"] = "";
$info["meta_keywords"] = "";
$info["title_url"] = "";
}
if($this->session->userdata("success_message")){ 
echo \'<div class="alert alert-success">\';
echo $this->session->userdata("success_message");
echo \'</div>\';
}
if($this->session->userdata("error_message")){
echo \'<div class="alert alert-error">\';
echo $this->session->userdata("error_message"); 
echo \'</div>\';
}
echo form_fieldset("");
//TITLE SECTION.
echo form_label(\'<b>Title&nbsp;<em class="red">*</em>:</b>\', \'Title\');
echo form_input(array("name" => "title", "value" => set_value("title",$info["title"]),"class" =>"span" ));
echo form_error("title","<span class=\'text-error\'>","</span>");
echo br();
';
$i =0;
foreach($attr as $val){
	if($val["translated"] == 1){
$i++;
$attr_name = str_replace(" ","_",$val["name"]);
$label_title = strtoupper(str_replace("_"," ",$val["name"]));
$label_for = $label_title;
if($val["validation"] != "trim")
$label_title .= '&nbsp;<em class="red">*</em>';
if($val["hint"] != "")
$label_title .= '&nbsp;<small class="yellow" > ('.$val["hint"].') </small>';
$label_title .= ':';
$add_view.='
//'.$label_title.' SECTION.
echo form_label(\'<b>'.$label_title.'</b>\', \''.$label_for.'\');
';
if($val["type"]== 3){
$add_view.='echo form_textarea(array("name" => "'.$attr_name.'", "value" => set_value("'.$attr_name.'",$info["'.$attr_name.'"]),"class" =>"ckeditor","id" => "'.$attr_name.'", "rows" => 15, "cols" =>100 ));';
} 
elseif($val["type"] == 4){ 
$add_view.='echo form_upload(array("name" => "'.$attr_name.'", "class" => "input-large"));
echo "<span >";
if($info["'.$attr_name.'"] != ""){ 
echo anchor(\'uploads/'.$table_name.'/\'.$info["'.$attr_name.'"],\'show image\',array("class" => \'blue gallery\'));
echo nbs(3);
echo anchor("back_office/'.$table_name.'/delete_image/'.$attr_name.'/".$info["'.$attr_name.'"]."/".$info["id_'.$table_name.'"], img(\'images/delete.png\'),array(\'class\' => \'cur\',\'onClick\' => "return confirm(\'Are you sure you want to delete this image ?\')" ));
} else { echo "<span class=\'blue\'>No Image Available</span>"; } 
echo "</span>";';
} 
elseif($val["type"] == 1){
$add_view.='echo \'<div class="input-append date" data-date="" data-date-format="dd/mm/yyyy">\';
echo form_input(array(\'name\' => \''.$attr_name.'\', \'value\' => set_value("'.$attr_name.'",$this->fct->date_out_formate($info["'.$attr_name.'"])),\'class\' =>\'input-small\',\'size\'=>16 ));
echo \'<span class="add-on"><i class="icon-th"></i></span></div>\';';
} 
elseif($val["type"] == 5){
$add_view.='echo form_upload(array("name" => "'.$attr_name.'", "class" => "input-large"));
echo "<span >";
if($info["'.$attr_name.'"] != ""){ 
echo anchor(\'uploads/'.$table_name.'/\'.$info["'.$attr_name.'"],\'show file\',array("class" => \'blue\',\'target\' => \'_blank\'));
echo nbs(3);
echo anchor("back_office/'.$table_name.'/delete_image/'.$attr_name.'/".$info["'.$attr_name.'"]."/".$info["id_'.$table_name.'"], img(\'images/delete.png\'),array(\'class\' => \'cur\',\'onClick\' => "return confirm(\'Are you sure you want to delete this file ?\')" ));
} else { echo "<span class=\'blue\'>No File Available</span>"; } 
echo "</span>";';
}
elseif($val["type"] == 6){
$add_view.='$options = array(
                  "" => " - select option - ",
                  1 => "Active",
                  0 => "InActive",
                );
echo form_dropdown("'.$attr_name.'", $options,set_value("'.$attr_name.'",$info["'.$attr_name.'"]), \'class="span"\');';
}
elseif($val["type"] == 7){
$cond=array("id_content" => $val["foreign_key"]);
$content001 = $this->fct->getonecell("content_type","name",$cond);
if(str_replace(" ","_",$content001) == str_replace(" ","_",$table_name))
$frn2 ="parent";
else
$frn2 =str_replace(" ","_",$content001); 
$add_view.='$items = $this->fct->getAll("'.str_replace(" ","_",$content001).'","sort_order"); 
echo \'<select name="'.$attr_name.'"  class="span">\';
echo \'<option value="" > - select '.$content001.' - </option>\';
foreach($items as $valll){ 
?>
<option value="<?= $valll["id_'.str_replace(" ","_",$content001).'"]; ?>" <? if(isset($id)){  if($info["id_'.$frn2.'"] == $valll["id_'.str_replace(" ","_",$content001).'"]){ echo \'selected="selected"\'; } } ?> ><?= $valll["title"]; ?></option>
<?
}
echo "</select>";';
} 
else { 
$add_view.='echo form_input(array(\'name\' => \''.str_replace(" ","_",$val["name"]).'\', \'value\' => set_value("'.str_replace(" ","_",$val["name"]).'",$info["'.str_replace(" ","_",$val["name"]).'"]),\'class\' =>\'span\' ));';
}
$add_view.='
echo form_error("'.str_replace(" ","_",$val["name"]).'","<span class=\'text-error\'>","</span>");
echo br();';
}
}
$add_view.='
//Languages&nbsp;<em>*</em>: SECTION.
echo form_label(\'<b>Languages&nbsp;<em class="red">*</em>:</b>\', \'Languages&nbsp;<em>*</em>:\');
if(isset($id)){
?>
<input type="text" name="language" class="span" value="<?php echo $info["lang"]; ?>"  readonly="readonly" />
<?php } else { ?>
<select name="language" class="span" >
<option value="" > - select language - </option>
<?php if(isset($id)){ ?>
<option value="<?php echo $info["lang"]; ?>" selected="selected" ><?php echo $info["lang"]; ?></option>
<?php } ?>
<?php foreach($languages as $language){ ?>
<option value="<?php echo $language["symbole"]; ?>"><?php echo $language["title"]." (".$language["symbole"].")"; ?></option>
<?php } ?>
</select>
<?php
}
echo form_error("language","<span class=\'text-error\'>","</span>");
echo br();
echo "<hr />";
echo "<h3>SEO Details:</h3>";
//PAGE TITLE SECTION.
echo form_label(\'<b>Page Title&nbsp;<small class="blue">(Max 65 Characters)</small>:</b>\', \'Page Title\');
echo form_input(array("name" => "meta_title", "value" => set_value("meta_title",$info["meta_title"]),"class" =>"span" ));
echo form_error("meta_title","<span class=\'text-error\'>","</span>");
echo br();
//TITLE URL SECTION.
echo form_label(\'<b>TITLE URL&nbsp;<em></em>:</b>\', \'TITLE URL\');
echo form_input(array("name" => "title_url", "value" => set_value("title_url",$info["title_url"]),"class" =>"span" ));
echo form_error("title_url","<span class=\'text-error\'>","</span>");
echo br();
//META DESCRIPTION SECTION.
echo form_label(\'<b>META DESCRIPTION&nbsp;<small class="blue">(Max 160 Characters)</small>:</b>\', \'META DESCRIPTION\');
echo form_textarea(array("name" => "meta_description", "value" => set_value("meta_description",$info["meta_description"]),"class" =>"span","rows" => 3, "cols" =>100));
echo form_error("meta_description","<span class=\'text-error\'>","</span>");
echo br();
//META KEYWORDS SECTION.
echo form_label(\'<b>META KEYWORDS&nbsp;<small class="blue">(Max 160 Characters)</small>:</b>\', \'META KEYWORDS\');
echo form_textarea(array("name" => "meta_keywords", "value" => set_value("meta_keywords",$info["meta_keywords"]),"class" =>"span","rows" => 3, "cols" =>100 ));
echo form_error("meta_keywords","<span class=\'text-error\'>","</span>");
echo br();
echo br();
if ($this->uri->segment(3) != "view_translation" ){
echo \'<p class="pull-right">\';
echo form_submit(array(\'name\' => \'submit\',\'value\' => \'Save Changes\',\'class\' => \'btn btn-primary\') );
echo \'</p>\';
}
echo form_fieldset_close();
echo form_close();
?>
</div>
</div>     
</div>
</div>
<? 
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message"); 
?>';