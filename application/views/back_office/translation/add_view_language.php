<?php
//LNGUAGE SECTION.
/*if(isset($id_parent_translate)) {
	echo form_hidden("id_parent_translate", $id_parent_translate);
}
if(isset($lang)) {
	echo form_label('<h3 class="yellow">'.ucfirst($language["title"]).'</h3>', 'language');
	echo form_hidden("lang", $lang);
}

else {
	if(!isset($info["lang"]))
	$info["lang"] = '';*/
	//$lang = $this->config->item('default_lang');
	$lang = $this->lang->lang();
	$language = $this->fct->getonerow('languages',array('index'=>$lang));
	//echo form_label('<h3 class="yellow">'.ucfirst($language["title"]).'</h3>', 'language');
	echo form_hidden("lang", $lang);
/*echo form_label('<b>Language</b>', 'language');
$languages = $this->fct->getAll("languages","sort_order");
$options = array();
foreach($languages as $language) {
	$options[$language["index"]] = $language["title"];
}
echo form_dropdown("lang", $options,set_value("lang",$info["lang"]), 'class="span"');
echo form_error("lang","<span class='text-error'>","</span>");

echo br();*/
//}