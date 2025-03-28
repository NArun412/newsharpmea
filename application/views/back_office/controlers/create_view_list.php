<?php
/*$content_name = $table_name;
$cond1["name"]=str_replace("_"," ",$content_name);
$id_content=$this->fct->getonecell("content_type","id_content",$cond1);
$gallery_status=$this->fct->getonecell("content_type","gallery",$cond1);*/
$list_view ='
<?php 
if(!isset($info)) $info = array();
$this->load->view("back_office/fields/table",array("info"=>$info,"content_type"=>"'.$table_name.'")); ?>
';

