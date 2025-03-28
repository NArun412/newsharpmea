<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Import extends MY_Controller {
	
public function __construct()
{
	parent::__construct();
	$this->module = $this->uri->segment(4);
	$this->id = $this->uri->segment(5);
	$this->conmod = $this->uri->segment(6);
	//$this->load->model("manage_m");
	$this->template->set_template("admin");
	$this->admin_dir = admin_dir();
	$content_type_data = $this->fct->getonerow("content_type",array("name"=>$this->module));
	if(!empty($content_type_data))
	$cond=array('id_content'=>$content_type_data['id_content']);
	$this->fields =$this->fct->get_module_fields($this->module);
}

public function index(){
 exit();
}

public function display(){
check_permission($this->module,'create');
$data["title"]="Import ".$this->module;
$data["import_row"] = $this->fct->getonerow("import", array("tables" => $this->module, "status" => 1));
$this->template->add_js('assets/js/custom/'.$this->module.'.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/fields/import_form',$data);
$this->template->render();
}	

public function get_table_fields(){
 $table = $this->input->post('val');
 $fld = "fields"; 
 echo form_label('<b>Fields</b>'); ?>
<?php echo form_label('<small class="yellow">Hint Goes Here</small>'); ?>
<?php
if ($this->db->table_exists($table))
$fields = $this->db->list_fields($table);
$selected = array();
$items = $fields; 
?>
<select name="<?php echo $fld; ?>[]" multiple="multiple"  class="form-control searchable" <?php //if(!empty($attributes)) echo $attributes; ?>>
<?php
foreach($items as $v){ 
$cl = '';
if(in_array($v,$selected)) $cl = 'selected="selected"';
?>
<option value="<?php echo $v; ?>" <?php echo $cl; ?>><?php echo $v; ?></option>
<?
}
?>
</select>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>

<?php
exit;	
}

public function submit_import(){
$this->load->model("import_m");
$content_type = $this->input->post("content_type");
$this->module = $content_type;
require './classes/simplexlsx.class.php';
if ($_FILES['import_file']['tmp_name'] != "") {
$import_row = $this->fct->getonerow("import", array("tables" => $content_type, "status" => 1));
$fields = explode(',',$import_row["fields"]);
$primary_key = (array_key_exists('id_'.$content_type, $import_row)) ? true : false;

ini_set('default_charset','utf-8');
mysql_set_charset('utf8');
header('Content-type: text/html; charset=utf-8');

$file = $_FILES['import_file']['tmp_name']; // UPLOADED EXCEL FILE

$xlsx = new SimpleXLSX($file);

list($cols, $rows) = $xlsx->dimension();
$i = 0; 
foreach( $xlsx->rows() as $k => $r) { // LOOP THROUGH EXCEL WORKSHEET
$i++;
$id = "";
// if there is a label for each column change the condition below to $i > 1
if($i > 0){
$check_array = array_filter($r);
if(!empty($check_array)){
	$not_existing_cards = array();
	$row = array();
	if($primary_key == true){
	$row = $this->fct->getonerow($content_type ,array('id_'.$content_type => $r[0]));
	$id = $row["id_".$content_type];
	}
	$_data = array();
	for($k = 0; $k < sizeof($fields); $k++){
		$is_foreign = explode('_', $fields[$k], 2);
		if($is_foreign[0] == "id" && $is_foreign[1] != $content_type){
			// Means this is a foreign ID.
			
			$_data[$fields[$k]] = $r[$k];
		} else {
		if(empty($row) || ( !empty($row) && $k > 0 ))
			$_data[$fields[$k]] = $r[$k];
		}
	}
	$new_id = $this->import_m->insert_update($_data,$id);
			}
		}
	}
	$this->session->set_userdata("success_message","File was uploaded successfully");
}
//
redirect(admin_url('manage/display/'.$this->module));
//	
	
}
	
}