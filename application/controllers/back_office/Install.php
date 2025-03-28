<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Install extends MY_Controller{

public function __construct()
{
parent::__construct();
$this->table="content_type_attr";
$this->load->helper('file');
$this->load->model("database_g");
$this->template->set_template('admin');
}

public function settings($id_content){
$data["title"]="Set Validation Rules";
// get  conetent type attributes .
$data["id_content"]=$id_content;
$cond["id_content"]=$id_content;
$data["info"]=$this->fct->getAll_cond($this->table,'sort_order',$cond);
// get the content type .
$data["table"]= $this->fct->getonecell('content_type','name',$cond);

$data["content"]="back_office/controlers/install";
//$this->load->view('back_office/template',$data);

$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/controlers/install',$data);
$this->template->render();
}


public function next($id_content){
$data["title"]="Install Content Type";
// get  conetent type attributes
$data["id_content"]=$id_content;
$cond["id_content"]=$id_content;
$info=$this->fct->getAll_cond($this->table,'sort_order',$cond);
$i=0;
foreach($info as $val){
$i++;
$validation='trim';
if($this->input->post('required_'.$i)==true)
$validation.='|required';
if($this->input->post('min_length_'.$i)==true){
if($this->input->post('min_num_'.$i)=='') $min_num_0=5; else $min_num_0= $this->input->post('min_num_'.$i);
$validation.='|min_length['.$min_num_0.']';
}
if($this->input->post('max_length_'.$i)==true){
if($this->input->post('max_num_'.$i)=='') $max_num_0=5; else $max_num_0= $this->input->post('max_num_'.$i);	
$validation.='|max_length['.$max_num_0.']';
}
if($this->input->post('valid_email_'.$i)==true)
$validation.='|valid_email';
//echo $validation;
// update validation 
$_data=array('validation' => $validation);
$this->db->where('id_attr',$val["id_attr"]);
$this->db->update('content_type_attr',$_data);
}


// get the content type 
$data["table"]= $this->fct->getonecell('content_type','name',$cond);
$data["content"]="back_office/controlers/validate";
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/controlers/validate',$data);
$this->template->render();	
}


function finish(){
$_data['position'] = $this->input->post('position');
$id_content = $this->input->post('id_content');
$id_content_focal = $this->input->post('id_content');
$this->db->where('id_content',$id_content);
$this->db->update('content_type',$_data);
$this->session->set_userdata('success_message','conetnt was installed successfully');
$cond=array('id_content' => $id_content);
$content_type=$this->fct->getonecell('content_type','name',$cond);
$table_name=str_replace(" ","_",$content_type);

$this->database_g->install_module($table_name);
$this->database_g->create_files($table_name);
$this->database_g->setup_module_permissions($table_name);
//
//////////////////////////////////////////////////////////////////////////////////////////////////////
redirect(base_url().'back_office/control');	
}


} 
?>
