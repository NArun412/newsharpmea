<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends MY_Controller {
	
public function __construct()
{
	parent::__construct();
	$this->template->set_template("admin");
}

public function index(){
	/*if(!$this->session->userdata('user_id')){
		$data["settings"] = $this->fct->getonerow('settings',array('id_settings' => 1));	
		$this->load->view('back_office/login',$data);
	}else{*/
		redirect(admin_url('home/dashboard'));
	//}
}

public function dashboard(){
	redirect(admin_url('dashboard'));
	$data["title"] = "Dashboard";
	$data["content"]="back_office/dashboard";
	$data["content_type"]=$this->fct->getAll('content_type','sort_order'); 
	$data["messages"]=$this->fct->getAll_orderdate('contactform');
	$data["new_message"]=$this->fct->get_unreaded_emails();
	$data["newsletter_emails"]=$this->fct->getAll_orderdate('newsletter');
	$data["users"]=$this->fct->getAll_orderdate('user');
	$this->load->view('back_office/template', $data);	
}

public function complete_todo($id){
	$cond=array('id_do'=>$id);
	$do_it=$this->fct->getonerow('do_it',$cond);
	echo '<a class="cur" onclick="completed('.$id.');">';
	if($do_it["completed"] == 1){
	$action = 0;
	echo "<span class='label label-important'>Pending</span>";
	}
	else{
	echo "<span class='label label-success'>Completed</span>";
	$action = 1;
	}
	echo '</a>';
	$_data["completed"] = $action ; 
	$this->db->where('id_do',$id);
	$this->db->update('do_it',$_data);
}

public function do_it_pop_up($id){
	$cond=array('id_do'=>$id);
	$do_it=$this->fct->getonerow('do_it',$cond);
	?>
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h3 id="myModalLabel"><?= $do_it["title"]; ?>&nbsp;&nbsp;<small><?= $do_it["Deadline"]; ?></small></h3>
	</div>
	<div class="modal-body" id="modal-body">
	<p><?= $do_it["description"]; ?></p>
	</div>
	<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
	<?
	//echo '<div style="height:auto;"><h2 style="font-size:160%; font-weight:bold; margin:10px 0;">'.$do_it["title"].'&nbsp;&nbsp;<span style="color:#AE9A62">Deadline:'.$do_it["Deadline"].'</span> </h2>
	//<p>'.$do_it["description"].'</p></div>';	
}

public function message_popup($id){
	$cond=array('id'=>$id);
	$do_it=$this->fct->getonerow('contactform',$cond);
	echo '<div style="height:auto;"><h2 style="font-size:160%; font-weight:bold; margin:10px 0;">'.$do_it["subject"].'&nbsp;&nbsp;<span style="color:#AE9A62">Date:'.$do_it["created_date"].'</span> </h2>
	<p>'.$do_it["message"].'</p></div>';	
}


	
	
}