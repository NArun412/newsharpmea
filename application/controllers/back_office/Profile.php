<?php
if ( ! defined("BASEPATH")) exit("No direct script access allowed");
class Profile extends MY_Controller{
public function __construct()
{
parent::__construct();
// Your own constructor code
//$this->headers='back_office/includes/header';
//$this->footer='back_office/includes/footer';
$this->template->set_template("admin");
$this->module = 'users';
$this->admin_dir = admin_dir();
$this->table="users";

$this->load->model($this->admin_dir."/users_m");
$this->load->model($this->admin_dir."/manage_m");

$this->template->set_template("admin");


//exit($this->id);
$content_type_data = $this->fct->getonerow("content_type",array("name"=>$this->module));
$cond=array('id_content'=>$content_type_data['id_content']);
$this->fields = array();
$fields =$this->fct->get_module_fields($this->module);
$allowed_fields = array('title','name','email','phone','address','password','photo');
foreach($fields as $field) {
	$fld = str_replace(' ','_',$field['name']);
	if(in_array($fld,$allowed_fields))
	array_push($this->fields,$field);
}
//print '<pre>'; print_r($this->fields); exit;
}


public function index(){
	$data["title"]="Edit Profile";
	$data["content"]="back_office/profile";
	$data["id"] = user_id();
	$data["info"]=$this->fct->getonerow('users',array('id_users' => user_id()));
	
	$this->template->write_view('header','back_office/includes/header',$data);
	$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
	$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
	$this->template->write_view('content','back_office/profile',$data);
	$this->template->render();

}


public function submit(){
/*if($this->input->post("id")!="")
check_permission($this->module,'edit',$this->input->post("id"));
else
check_permission($this->module,'create');*/
$model_custom = $this->module.'_m';
/*if(file_exists(APPPATH."models/".$this->admin_dir."/".$model_custom.".php")){
   $this->load->model($this->admin_dir.'/'.$model_custom);
   $model = $model_custom;
}
else{*/
  $model = 'manage_m';
//}
$data["title"]="Add / Edit ".$this->module;
$this->form_validation->set_rules("title", "TITLE", "trim|required");
foreach($this->fields as $val) {
	if($val['type'] == 7) {
		$var  = intval('id_content') - intval($val['foreign_key']);
		if($var == 0) {
			$this->form_validation->set_rules("id_parent", $val["name"], $val["validation"]);
		}
		else {
			$content001 = get_cell("content_type","used_name","id_content",$val["foreign_key"]);
			$this->form_validation->set_rules("id_".$content001, $val["name"], $val["validation"]);
		}
	}
	elseif($val['type'] == 4 || $val['type'] == 5) {
		if(!empty($_FILES[str_replace(" ","_",$val["name"])]["name"])) {
			$_POST[str_replace(" ","_",$val["name"])] = $_FILES[str_replace(" ","_",$val["name"])]["name"];
		}
		else {
			//$_POST["'.str_replace(" ","_",$val["name"]).'"] = "";
		}
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $val["validation"]);
	}
	elseif($val['type'] == 10) {
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $val["validation"]);
	}
	elseif($val['type'] == 12) {
		if($this->input->post(str_replace(" ","_",$val["name"])) == "")
		$_POST[str_replace(" ","_",$val["name"])] = '';
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $val["validation"]);
	}
	else {
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $val["validation"]);
	}
}
if ($this->form_validation->run() == FALSE) {

	$this->index();
} 
else {
	$_data["title"]=$this->input->post("title");	
	foreach($this->fields as $val){
		$field = str_replace(" ","_",$val["name"]); 
		// Image 
		if($val["type"]==4){
			if(!empty($_FILES[str_replace(" ","_",$val["name"])]["name"])) {
			
				$_POST[$field] = $this->fct->upload_image_from_admin($this->module,$val["name"]);
			}
		} 
		// File 
		elseif($val["type"] == 5) {
			if(!empty($_FILES[str_replace(" ","_",$val["name"])]["name"])) {
				$_POST[$field] = $this->fct->upload_file_from_admin($this->module,$val["name"]);
			}
		}
	}
	$_data["lang"] = default_lang();	
	if($this->input->post("id")!=""){
		$this->session->set_userdata("success_message","Information was updated successfully");
	} 
	else {
		$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	$new_id = $this->$model->insert_update($this->input->post(),$this->input->post("id"));
	redirect(admin_url('profile'));
}
}

public function submit_DELETED(){	
$data["title"]="Update Profile";
//$this->form_validation->set_rules("title", "TITLE", "trim|required");
$this->form_validation->set_rules("name", "name", "trim|required");
$this->form_validation->set_rules("email", "email", "trim|required|valid_email");
$this->form_validation->set_rules("phone", "phone", "trim");
$this->form_validation->set_rules("address", "address", "trim");
$this->form_validation->set_rules("password", "password", "trim");
$this->form_validation->set_rules("photo", "photo", "trim");

if ($this->form_validation->run() == FALSE){ 
$this->index();
} else {
$_data["title"]=$this->input->post("name");	
if(!empty($_FILES["photo"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_users"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("users","photo",$cond_image);
if(!empty($old_image) && file_exists('./uploads/users/'.$old_image)){
unlink("./uploads/users/".$old_image);
$sumb_val1=explode(",","100x100");
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/users/".$value."/".$old_image)){
unlink("./uploads/users/".$value."/".$old_image);	 }							
} 
 } }
$image1= $this->fct->uploadImage("photo","users");
$this->fct->createthumb($image1,"users","100x100");$_POST["photo"]=$image1;	
}

$_data["lang"] = default_lang();	
if($this->input->post("password") == "")
unset($_POST['password']);
	
	$new_id = $this->users_m->insert_update($this->input->post(),user_id());
$this->session->set_userdata("success_message","Information was updated successfully");
	redirect(admin_url("profile"));
}
}

}