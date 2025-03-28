<?php 
if(!isset($info)) $info = array();
$this->load->view($this->admin_dir."/fields/table",array("info"=>$info,"content_type"=>$this->module)); ?>
