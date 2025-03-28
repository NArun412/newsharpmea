<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Database_g extends CI_Model {

function install_module($module)
{
	$module = strtolower(str_replace(" ","_",$module));
	//echo $module;exit;
	$content_type = $this->db->where('name',$module)->get("content_type")->row_array();
	if(!empty($content_type)) {
		$table_name= $module;
		$q = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
		`id_".$table_name."` INT( 11 ) NOT NULL AUTO_INCREMENT ,
		`created_on` DATETIME NULL,
		`sort_order` INT( 1 ) NOT NULL DEFAULT '0' ,
		`status` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
		`title` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
		`lang` varchar(5) DEFAULT NULL,
		`translation_id` int(11) DEFAULT '0',
		 KEY `translation_id` (`id_".$table_name."`),
		 PRIMARY KEY ( `id_".$table_name."` )
		) ENGINE = InnoDB 
		";
		$this->db->query($q);
		if(enable_review()) {
			$q = "CREATE TABLE IF NOT EXISTS `".$table_name."_review` (
			`id_".$table_name."` INT( 11 ) NOT NULL AUTO_INCREMENT ,
			`live_id` INT( 11 ) NOT NULL  ADD  INDEX (`translation_id`) ,
			`created_on` DATETIME NULL,
			`sort_order` INT( 1 ) NOT NULL DEFAULT '0' ,
			`status` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
			`title` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
			`lang` varchar(5) DEFAULT NULL,
			`translation_id` int(11) DEFAULT '0',
			 KEY `translation_id` (`id_".$table_name."`),
			 PRIMARY KEY ( `id_".$table_name."` )
			) ENGINE = InnoDB 
			";
			$this->db->query($q);
		}
		if (!$this->db->field_exists('translation_id', $table_name)) {
			$this->db->query("ALTER TABLE `".$table_name."` ADD `translation_id` INT( 11 ) NOT NULL ;");
			$this->db->query("ALTER TABLE `".$table_name."` ADD  INDEX (`translation_id`)");
			if(enable_review()) {
				$this->db->query("ALTER TABLE `".$table_name."_review` ADD `translation_id` INT( 11 ) NOT NULL ;");
				$this->db->query("ALTER TABLE `".$table_name."_review` ADD  INDEX (`translation_id`)");
			}
		}
		/*if($this->db->field_exists('id_parent', $table_name)) {
			$this->db->query(" ALTER TABLE `".$table_name."` DROP COLUMN `id_parent`");
		}*/
		if($this->db->field_exists('deleted', $table_name)) {
			$this->db->query(" ALTER TABLE `".$table_name."` DROP COLUMN `deleted`");
			if(enable_review())
			$this->db->query(" ALTER TABLE `".$table_name."_review` DROP COLUMN `deleted`");
		}
		if($this->db->field_exists('created_date', $table_name)) {
			$this->db->query(" ALTER TABLE `".$table_name."` DROP COLUMN `created_date`");
			if(enable_review())
			$this->db->query(" ALTER TABLE `".$table_name."_review` DROP COLUMN `created_date`");
		}
		if($this->db->field_exists('updated_date', $table_name)) {
			$this->db->query(" ALTER TABLE `".$table_name."` DROP COLUMN `updated_date`");
			if(enable_review())
			$this->db->query(" ALTER TABLE `".$table_name."_review` DROP COLUMN `updated_date`");
		}
		if($this->db->field_exists('deleted_date', $table_name)) {
			$this->db->query(" ALTER TABLE `".$table_name."` DROP COLUMN `deleted_date`");
			if(enable_review())
			$this->db->query(" ALTER TABLE `".$table_name."_review` DROP COLUMN `deleted_date`");
		}
		if($this->db->field_exists('meta_title', $table_name)) {
			$this->db->query(" ALTER TABLE `".$table_name."` DROP COLUMN `meta_title`");
			if(enable_review())
			$this->db->query(" ALTER TABLE `".$table_name."_review` DROP COLUMN `meta_title`");
		}
		if($this->db->field_exists('meta_description', $table_name)) {
			$this->db->query(" ALTER TABLE `".$table_name."` DROP COLUMN `meta_description`");
			if(enable_review())
			$this->db->query(" ALTER TABLE `".$table_name."_review` DROP COLUMN `meta_description`");
		}
		if($this->db->field_exists('meta_keywords', $table_name)) {
			$this->db->query(" ALTER TABLE `".$table_name."` DROP COLUMN `meta_keywords`");
			if(enable_review())
			$this->db->query(" ALTER TABLE `".$table_name."_review` DROP COLUMN `meta_keywords`");
		}
		if($this->db->field_exists('title_url', $table_name)) {
			$this->db->query(" ALTER TABLE `".$table_name."` DROP COLUMN `title_url`");
			if(enable_review())
			$this->db->query(" ALTER TABLE `".$table_name."_review` DROP COLUMN `title_url`");
		}
		$fields = $this->db->where('id_content',$content_type['id_content'])->get("content_type_attr")->result_array();
		foreach($fields as $val1) {
			$fieldname = str_replace(" ","_",$val1["name"]);
			$proceed = FALSE;
			if($val1["type"] == 7) {
				$foreign_key_f=get_cell('content_type','used_name','id_content',$val1["foreign_key"]);
				$var = intval($val1["foreign_key"]) - intval($val1['id_content']);
				if( $var == 0 ) {
					 if ($this->db->field_exists('id_parent', $table_name)) continue;
					 else $proceed = TRUE;
				    
				} else {
					if ($this->db->field_exists("id_".$foreign_key_f, $table_name)) continue;
					else $proceed = TRUE;
				}
			}
		
			else {
				if (!$this->db->field_exists($fieldname, $table_name)) $proceed = TRUE;
			}
			if ($proceed)
			{
			  if($val1["type"] == 7) {
				$foreign_key_f=get_cell('content_type','used_name','id_content',$val1["foreign_key"]);
				$var = intval($val1["foreign_key"]) - intval($val1['id_content']);
				if($var == 0) { $q = "ALTER TABLE `".$table_name."` ADD `id_parent"; 
				 $q_review = "ALTER TABLE `".$table_name."_review` ADD `id_parent"; 
				}
				else { $q = "ALTER TABLE `".$table_name."` ADD `id_".$foreign_key_f;
				$q_review = "ALTER TABLE `".$table_name."_review` ADD `id_".$foreign_key_f; }
			  } 
			  	elseif($val1["type"] == 12) {
						$foreign_key_f=get_cell('content_type','used_name','id_content',$val1["foreign_key"]);
				if (!$this->db->table_exists($table_name.'_'.$foreign_key_f) )
				  {
					$this->db->query("CREATE TABLE IF NOT EXISTS `".$table_name.'_'.$foreign_key_f."` (
  `id_".$table_name."` int(11) NOT NULL,
  `id_".$foreign_key_f."` int(11) NOT NULL,
  KEY `id_".$table_name."` (`id_".$table_name."`,`id_".$foreign_key_f."`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
				  }
				}
			  else {
				$q="ALTER TABLE `".$table_name."` ADD `".$fieldname; 
				$q_review="ALTER TABLE `".$table_name."_review` ADD `".$fieldname; 
			  }
			  if($val1["type"] == 2)
			  $q_add="` VARCHAR( ".$val1["length"]." ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			  elseif($val1["type"] == 8)
			  $q_add="` INT( ".$val1["length"]." ) NULL DEFAULT '0';";
			  elseif($val1["type"] == 9)
			  $q_add="` DECIMAL( ".$val1["length"]." ) NULL DEFAULT '0';";
			  elseif($val1["type"] == 4)
			  $q_add="` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			  elseif($val1["type"] == 3)
			  $q_add="` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			  elseif($val1["type"] == 5)
			  $q_add="` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			  elseif($val1["type"] == 1)
			  $q_add="` DATE NULL ;";
			  elseif($val1["type"] == 6)
			  $q_add="` TINYINT( 1 ) NOT NULL DEFAULT '0';";
			  elseif($val1["type"] == 7)
			  $q_add="` INT( 11 ) NOT NULL ;";
			  $q .= $q_add;
			  $q_review .= $q_add;
			  if($val1["type"] != 12)
			  $this->db->query($q);
			  if(enable_review())
			  $this->db->query($q_review);
			  if($val1["type"] == 7){
				  $var = intval($val1["foreign_key"]) - intval($val1['id_content']);
				  if($var == 0) {
				  	$q1="ALTER TABLE `".$table_name."` ADD  INDEX (`id_parent`)";
					$q1_review="ALTER TABLE `".$table_name."_review` ADD  INDEX (`id_parent`)";
				  }
				  else {
				  	$q1="ALTER TABLE `".$table_name."` ADD  INDEX (`id_".$foreign_key_f."`)";
					$q1_review="ALTER TABLE `".$table_name."_review` ADD  INDEX (`id_".$foreign_key_f."`)";
				  }
				  $this->db->query($q1);	
				  if(enable_review())
				  $this->db->query($q1_review);
			  }
			}
		}
		//exit();
		if($content_type['gallery'] == 1)
		$this->create_module_gallery($table_name);
		/*if(multi_languages()) {
			$this->create_module_translation($table_name);
		}*/
		$this->setup_module_permissions($table_name);
		return true;
	}
	return false;
}
function create_connect_fields($module)
{
	if(!$this->db->field_exists('module', $module)) {
		$q = 'ALTER TABLE  `'.$module.'` ADD  `module` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `id_'.$module.'`';
		$this->db->query($q);
	}
	if(!$this->db->field_exists('record', $module)) {
		$q1 = 'ALTER TABLE  `'.$module.'` ADD  `record` INT( 11 ) NOT NULL AFTER  `module` ,
ADD INDEX (  `record` )';
		$this->db->query($q1);
	}
	
	if(enable_review()) {
		if(!$this->db->field_exists('module', $module.'_review')) {
			$q = 'ALTER TABLE  `'.$module.'_review` ADD  `module` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `id_'.$module.'`';
		$this->db->query($q);
		}
		if(!$this->db->field_exists('record', $module.'_review')) {
			$q1 = 'ALTER TABLE  `'.$module.'_review` ADD  `record` INT( 11 ) NOT NULL AFTER  `module` ,
	ADD INDEX (  `record` )';
			$this->db->query($q1);
		}
	}
	return true;
}
function create_module($module,$with_gallery = '')
{
	$table_name = $module;
	$q = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
		`id_".$table_name."` INT( 11 ) NOT NULL AUTO_INCREMENT ,
		`created_on` DATETIME NULL,
		`sort_order` INT( 1 ) NOT NULL DEFAULT '0' ,
		`status` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
		`title` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
		`lang` varchar(5) DEFAULT NULL,
		`translation_id` int(11) DEFAULT '0',
		 KEY `translation_id` (`id_".$table_name."`),
		 PRIMARY KEY ( `id_".$table_name."` )
		) ENGINE = InnoDB 
		";
		$this->db->query($q);
		if(enable_review()) {
			$q = "CREATE TABLE IF NOT EXISTS `".$table_name."_review` (
			`id_".$table_name."` INT( 11 ) NOT NULL AUTO_INCREMENT ,
			`created_on` DATETIME NULL,
			`sort_order` INT( 1 ) NOT NULL DEFAULT '0' ,
			`status` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
			`title` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
			`lang` varchar(5) DEFAULT NULL,
			`translation_id` int(11) DEFAULT '0',
			 KEY `translation_id` (`id_".$table_name."`),
			 PRIMARY KEY ( `id_".$table_name."` )
			) ENGINE = InnoDB 
			";
			$this->db->query($q);
		}
		if(!is_dir('./uploads/'.$module))
		mkdir('./uploads/'.$module);
		if($with_gallery == 1) {
			$this->create_module_gallery($module);
		}
		return true;
}
function create_module_gallery($module)
{
	$module = strtolower(str_replace(" ","_",$module));
	$content_type = $this->db->where('name',$module)->get("content_type")->row_array();
	$fields = $this->db->where('id_content',$content_type['id_content'])->get("content_type_attr")->result_array();
	if($content_type['gallery'] == 1){
		$table_name= $module;
		 $q2="CREATE TABLE IF NOT EXISTS `".$table_name."_gallery` (
		`id_gallery` INT( 11 ) NOT NULL AUTO_INCREMENT ,
		`sort_order` INT( 1 ) NOT NULL DEFAULT '0' ,
		`status` INT( 1 ) NOT NULL DEFAULT '0',
		`title` VARCHAR( 250 ) NULL ,
		`image` VARCHAR( 150 ) NULL ,
		`id_".$table_name."` INT( 11 ) NOT NULL,
		PRIMARY KEY ( `id_gallery` ),
		INDEX ( `id_".$table_name."` )
		) ENGINE = InnoDB 
		";
		$this->db->query($q2);
		
		if(!is_dir('./uploads/'.$table_name.'/')) mkdir('./uploads/'.$table_name.'/');
		if(!is_dir('./uploads/'.$table_name.'/gallery/')) mkdir('./uploads/'.$table_name.'/gallery/');
		if(!is_dir('./uploads/'.$table_name.'/gallery/120x120/')) mkdir('./uploads/'.$table_name.'/gallery/120x120/');
		$sumb_val1=explode(",",$content_type['thumb_val_gal']);
		foreach($sumb_val1 as $key => $value){
			if(!is_dir("./uploads/".$table_name."/gallery/".$value."/")) mkdir("./uploads/".$table_name."/gallery/".$value."/");									
		}
		
		if(enable_review()) {
			$q2="CREATE TABLE IF NOT EXISTS `".$table_name."_review_gallery` (
		`id_gallery` INT( 11 ) NOT NULL AUTO_INCREMENT ,
		`sort_order` INT( 1 ) NOT NULL DEFAULT '0' ,
		`status` INT( 1 ) NOT NULL DEFAULT '0',
		`title` VARCHAR( 250 ) NULL ,
		`image` VARCHAR( 150 ) NULL ,
		`id_".$table_name."` INT( 11 ) NOT NULL,
		PRIMARY KEY ( `id_gallery` ),
		INDEX ( `id_".$table_name."` )
		) ENGINE = InnoDB 
		";
		$this->db->query($q2);
		
		if(!is_dir('./uploads_review/'.$table_name.'/')) mkdir('./uploads_review/'.$table_name.'/');
		if(!is_dir('./uploads_review/'.$table_name.'/gallery/')) mkdir('./uploads_review/'.$table_name.'/gallery/');
		if(!is_dir('./uploads_review/'.$table_name.'/gallery/120x120/')) mkdir('./uploads_review/'.$table_name.'/gallery/120x120/');
		$sumb_val1=explode(",",$content_type['thumb_val_gal']);
		foreach($sumb_val1 as $key => $value){
			if(!is_dir("./uploads_review/".$table_name."/gallery/".$value."/")) mkdir("./uploads_review/".$table_name."/gallery/".$value."/");									
		}
		}
		
		
		
		return true;
	}
	return false;
}

function create_attribute($field_id)
{
	$attr = $this->db->where("id_attr",$field_id)->get("content_type_attr")->row_array();
	$content_type = $this->db->where("id_content",$attr['id_content'])->get("content_type")->row_array();
	$table_name = str_replace(" ","_",$content_type['name']);
	$fieldname = str_replace(" ","_",$attr['name']);
	
	if($attr["type"] == 4 || $attr["type"] == 5) {
		if(!is_dir('./uploads/'.$table_name.'/'.$fieldname))
		mkdir("./uploads/".$table_name."/".$fieldname);	
		if($attr["type"] == 4) {
			if($attr["thumb"] ==1){
		      $sumb_val =array();	
		      $sumb_val=explode(",", $attr["thumb_val"]);	
			 // print_r($sumb_val);exit;
		  	  foreach($sumb_val as $key => $value){
			  	  if(!is_dir('./uploads/'.$table_name."/".$fieldname."/".$value))
				  mkdir('./uploads/'.$table_name."/".$fieldname."/".$value);	
			  } 
		  	} 
		}
	}
			$proceed = FALSE;
			if($attr["type"] == 7) {
				$foreign_key_f=get_cell('content_type','used_name','id_content',$attr["foreign_key"]);
				$var = intval($attr["foreign_key"]) - intval($attr['id_content']);
				if( $var == 0 ) {
					 if (!$this->db->field_exists('id_parent', $table_name))
					 $proceed = TRUE;
				    
				} else {
					if (!$this->db->field_exists("id_".$foreign_key_f, $table_name))
					 $proceed = TRUE;
				}
			}
			else {
				if (!$this->db->field_exists($fieldname, $table_name)) $proceed = TRUE;
			}
			if ($proceed)
			{
			  if($attr["type"] == 7) {
				$foreign_key_f=get_cell('content_type','used_name','id_content',$attr["foreign_key"]);
				$var = intval($attr["foreign_key"]) - intval($attr['id_content']);
				if($var == 0) { $q = "ALTER TABLE `".$table_name."` ADD `id_parent"; 
				 $q_review = "ALTER TABLE `".$table_name."_review` ADD `id_parent"; 
				}
				else { $q = "ALTER TABLE `".$table_name."` ADD `id_".$foreign_key_f;
				$q_review = "ALTER TABLE `".$table_name."_review` ADD `id_".$foreign_key_f; }
			  } 
			  	elseif($attr["type"] == 12) {
						$foreign_key_f=get_cell('content_type','used_name','id_content',$attr["foreign_key"]);
				if (!$this->db->table_exists($table_name.'_'.$foreign_key_f) )
				  {
					$this->db->query("CREATE TABLE IF NOT EXISTS `".$table_name.'_'.$foreign_key_f."` (
  `id_".$table_name."` int(11) NOT NULL,
  `id_".$foreign_key_f."` int(11) NOT NULL,
  KEY `id_".$table_name."` (`id_".$table_name."`,`id_".$foreign_key_f."`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
				  }
				}
			  else {
				$q="ALTER TABLE `".$table_name."` ADD `".$fieldname; 
				$q_review="ALTER TABLE `".$table_name."_review` ADD `".$fieldname; 
			
			  $q_add = '';
			  if($attr["type"] == 2 || $attr["type"] == 10 || $attr["type"] == 11)
			  $q_add="` VARCHAR( ".$attr["length"]." ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			  elseif($attr["type"] == 8)
			  $q_add="` INT( ".$attr["length"]." ) NULL DEFAULT '0';";
			  elseif($attr["type"] == 9)
			  $q_add="` DECIMAL( ".$attr["length"]." ) NULL DEFAULT '0';";
			  elseif($attr["type"] == 4)
			  $q_add="` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			  elseif($attr["type"] == 3)
			  $q_add="` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			  elseif($attr["type"] == 5)
			  $q_add="` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			  elseif($attr["type"] == 1)
			  $q_add="` DATE NULL ;";
			  elseif($attr["type"] == 6)
			  $q_add="` TINYINT( 1 ) NOT NULL DEFAULT '0';";
			  elseif($attr["type"] == 7)
			  $q_add="` INT( 11 ) NOT NULL ;";
			  $q .= $q_add;
			  $q_review .= $q_add;
			  if($attr["type"] != 12)
			  $this->db->query($q);
			  if(enable_review())
			  $this->db->query($q_review);
			  if($attr["type"] == 7){
				  $var = intval($attr["foreign_key"]) - intval($attr['id_content']);
				  if($var == 0) {
				  	$q1="ALTER TABLE `".$table_name."` ADD  INDEX (`id_parent`)";
					$q1_review="ALTER TABLE `".$table_name."_review` ADD  INDEX (`id_parent`)";
				  }
				  else {
				  	$q1="ALTER TABLE `".$table_name."` ADD  INDEX (`id_".$foreign_key_f."`)";
					$q1_review="ALTER TABLE `".$table_name."_review` ADD  INDEX (`id_".$foreign_key_f."`)";
				  }
				  $this->db->query($q1);	
				  if(enable_review())
				  $this->db->query($q1_review);
			  }
			   }
			}
			return true;
}
function create_files($module)
{
	$module = strtolower(str_replace(" ","_",$module));
	$content_type = $this->db->where('name',$module)->get("content_type")->row_array();
	$id_content_focal = $content_type['id_content'];
	$table_name = $module;
	
	// create uploads folders
	$this->create_folders($table_name,$id_content_focal);
	
	// create Js file
	//$this->create_js_file($table_name,$id_content_focal);
	
	// create controller 
	$this->create_controller($table_name,$id_content_focal);
	
	// create Model 
	$this->create_model($table_name,$id_content_focal);
	
	//create views
	$this->create_views($table_name,$id_content_focal);
	
	return true;
}

function create_model($table_name,$id)
{
	$cond=array('id_content'=>$id);
	$attrrr=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);
	include('./application/views/back_office/controlers/create_model.php');
	$this->fct->write_file('./application/models/'.ucfirst($table_name).'_m.php',$models);
	return true;
}
function create_controller($table_name,$id) {
	$cond=array('id_content'=>$id);
	$attrrr=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);
	include('./application/views/back_office/controlers/create_controller.php');
	$this->fct->write_file('./application/controllers/back_office/'.ucfirst($table_name).'.php',$controllers);
	return true;
}
function create_views($table_name,$id)
{
	$this->create_add_view($table_name,$id);
	$this->create_list_view($table_name,$id);
	return true;
}
function create_add_view($table_name,$id)
{
	if(!is_dir('./application/views/back_office/'.$table_name))
	mkdir('./application/views/back_office/'.$table_name);
	
	$cond=array('id_content'=>$id);
	$attr=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);
	include('./application/views/back_office/controlers/create_view_add.php');
	$this->fct->write_file('./application/views/back_office/'.$table_name.'/add.php',$add_view);
	return true;
}
function create_list_view($table_name,$id)
{
	if(!is_dir('./application/views/back_office/'.$table_name))
	mkdir('./application/views/back_office/'.$table_name);
	
	//create list.php
	$cond=array('display'=>1,'id_content' => $id);
	$attr=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);
	include('./application/views/back_office/controlers/create_view_list.php');
	if(!file_exists('./application/views/back_office/'.$table_name.'/list.php'))
	$this->fct->write_file('./application/views/back_office/'.$table_name.'/list.php',$list_view);
	return true;
}


function create_js_file($table_name,$id)
{
	$cond=array('id_content'=>$id);
	$attr=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);
	include('./application/views/back_office/controlers/create_js.php');
	if(!file_exists('./assets/js/custom/'.$table_name.'.js'))
	$this->fct->write_file('./assets/js/custom/'.$table_name.'.js',$scripts_string);
	return true;
}
function create_folders($table_name,$id)
{
	$fields = $this->db->where('id_content',$id)->get("content_type_attr")->result_array();
	// create folcer inside uploads for images  and thumbnails for images .
	if(!is_dir('./uploads/'.$table_name))
	mkdir('./uploads/'.$table_name);
	foreach($fields as $val){
	  if($val["type"] ==  4){
	      if($val["thumb"] ==1){
		      $sumb_val =array();	
		      $sumb_val=explode(",", $val["thumb_val"]);	
		  	  foreach($sumb_val as $key => $value){
			  	  if(!is_dir('./uploads/'.$table_name."/".$value))
				  mkdir('./uploads/'.$table_name."/".$value);	
			  } 
		  } 
	  } 
	}
	return true;
}
function setup_module_permissions($module)
{
/*	$module = strtolower(str_replace(" ","_",$module));
	$content_type = $this->db->where('name',$module)->get("content_type")->row_array();
	$check = $this->db->where('id_content',$content_type['id_content'])->get('roles_resource')->num_rows();
	if($check == 0) {
		$roles_data = array(
			'title' => $module,
			'view' => 'admin,master',
			'add' => 'admin,master',
			'edit' => 'admin,master',
			'delete' => 'admin,master',
			'delete_all' => 'admin,master',
			'manage' => 'admin,master',
			'export' => 'admin,master',
			'print' => 'admin,master',
			'activate' => 'admin,master',
			'id_content' => $content_type['id_content']
		);
		$this->db->insert('roles_resource', $roles_data);
		return true;
	}*/
	return true;
}

function delete_module($id)
{
	$cond=array('id_content' => $id);
	$content_type=$this->fct->getonecell('content_type','name',$cond);
	$with_gallery=$this->fct->getonecell('content_type','gallery',$cond);
	$table_name =str_replace(" ","_",$content_type);

	if($content_type != '') {
		// Remove From Roles Resources
		//$this->db->delete('roles_resource', array('id_content' => $id));
		//exit($content_type);
		$this->db->query('DROP TABLE IF EXISTS `'.$table_name.'`');
		//echo $this->db->last_query();exit;
		$this->db->query('DROP TABLE IF EXISTS `'.$table_name.'_review`');
		$this->db->where('id_content',$id);
		$this->db->delete('content_type');
		$this->db->where('id_content',$id);
		$this->db->delete('content_type_attr');
		if($with_gallery == 1) {
			$this->db->query('DROP TABLE IF EXISTS `'.$table_name.'_gallery`');	
		}
		if(file_exists('./application/controllers/back_office/'.$table_name.'.php'))
		unlink('./application/controllers/back_office/'.$table_name.'.php');
		
		if(file_exists('./application/models/'.$table_name.'_m.php'))
		unlink('./application/models/'.$table_name.'_m.php');
		
		$this->fct->deleteDirectory('./application/views/back_office/'.$table_name);
		$this->fct->deleteDirectory('./uploads/'.$table_name);
	}
	//
	return true;
}

public function delete_attribute($id){
$cond=array('id_attr'=>$id);
$attribute = $this->fct->getonerow("content_type_attr",$cond);
//print '<pre>'; print_r($attribute);exit;
$proceed = true;
if(!empty($attribute)) {
	$id1=$attribute['id_content'];
//$field = str_replace(" ","_",$field);
$cond1=array('id_content'=>$id1);
$table=$this->fct->getonecell('content_type','name',$cond1);
$table_name = str_replace(" ","_",$table);
	$fld = str_replace(" ","_",$attribute['name']);
	//echo $fld;exit;
	if($attribute['type'] == 7) {
		if(intval($attribute['foreign_key']) == intval($attribute['id_content']))
		$fld = 'id_parent';
		else {
			//echo 'test: '.$attribute['foreign_key'];exit;
			$forc = $this->fct->getonerow("content_type",array("id_content"=>$attribute['foreign_key']));
			//print '<pre>'; print_r($forc);exit;
			if(!empty($forc))
			$fld = 'id_'.$forc['name'];
			else
			$proceed = false;
		}
	}
	elseif($attribute['type'] == 12) {
		$foreign_key_f=get_cell('content_type','used_name','id_content',$attribute["foreign_key"]);
		$this->db->query("DROP TABLE IF EXISTS ".$table_name.'_'.$foreign_key_f."");
		$proceed = false;
	}

if($proceed){
		if ($this->db->field_exists($fld, $table_name)) {
$q=" ALTER TABLE `".$table_name."` DROP `".$fld."` ";
//echo $q;exit;
$this->db->query($q);
		}
if($attribute['type'] == 4 || $attribute['type'] == 5) {
if(is_dir('./uploads/'.$table_name.'/'.str_replace(" ","_",$attribute['name'])))
$this->fct->deleteDirectory("./uploads/".$table_name."/".$fld);
//unlink("./uploads/".$table_name."/".str_replace(" ","_",$attribute['name']));	

}
}
//if($attribute['type'] != 12) {
	$this->db->where('id_attr',$id);
	$this->db->delete('content_type_attr');
//}
return true;
}
else {
//$this->db->where('id_attr',$id);
//$this->db->delete('content_type_attr');
return false;
}
//redirect(base_url().'back_office/control/manage/'.$id1);
}

/*
function create_module_translation($module)
{
	$module = strtolower(str_replace(" ","_",$module));
	$content_type = $this->db->where('name',$module)->get("content_type")->row_array();
	$fields = $this->db->where('id_content',$content_type['id_content'])->get("content_type_attr")->result_array();
	$table_name= $module;
	$q = "CREATE TABLE IF NOT EXISTS `".$table_name."_translation` (
	`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
	`title` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`lang` varchar(5) DEFAULT NULL,
	`id_".$table_name."` INT(11) NOT NULL, 
	PRIMARY KEY (`id`),
  	KEY `id_rel` (`id_".$table_name."`)
	) ENGINE = InnoDB";
	$this->db->query($q);
	foreach($fields as $val) {
		$fieldname = str_replace(" ","_",$val["name"]);
		if (!$this->db->field_exists($fieldname, $table_name))
		{
			$q="ALTER TABLE `".$table_name."` ADD `".$fieldname; 
			if($val["type"] == 2 && $val["translated"] == 1)
			$q.="` VARCHAR( ".$val["length"]." ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			elseif($val["type"] == 4 && $val["translated"] == 1)
			$q.="` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			elseif($val["type"] == 3 && $val["translated"] == 1)
			$q.="` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			elseif($val["type"] == 5 && $val["translated"] == 1)
			$q.="` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";
			elseif($val["type"] == 1 && $val["translated"] == 1)
			$q.="` DATE NULL ;";
		}
		//echo $q;exit;
		$this->db->query($q);
	}
	return true;
}
*/
}