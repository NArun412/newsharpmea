<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Translation extends MY_Controller{

public function __construct()
{
parent::__construct();
	$this->template->set_template("admin");
	$this->admin_dir = admin_dir();
	//exit($this->admin_dir);
	$this->module = $this->uri->segment(4);
	$this->id = $this->uri->segment(5);
	$this->lang = $this->uri->segment(6);
	$this->load->model($this->admin_dir.'/manage_m');
}

public function section()
{
	if (multi_languages()){
		$section = $this->module;
		$id = $this->id;
		$lang = $this->lang;
		
		check_permission($section,'translate');
		if($lang == '') $lang = default_lang();
		$data["title"]="Translate ".$section;
		$data["content"]=$this->admin_dir."/translation/list";
		
		$data["id"] = $id;
		//$model = $section."_m";
	
		$model_custom = $section.'_m';
		if(file_exists(APPPATH."models/".$model_custom.".php")){
		   $this->load->model($model_custom);
		   $model = $model_custom;
		}
		else{
		  $model = 'manage_m';
		  $this->load->model($model);
		}

		$data["info"] = $this->$model->get($id);
	
		//print_r($data["info"]);exit;
	    $data["languages"] = $this->db->get("languages")->result_array();
		$data["section"] = $section;
		$data['lang'] = $lang;
		$data['record'] = $id;
		$data['fields'] = $this->fct->get_module_fields($section);

		$this->template->write_view('header',$this->admin_dir.'/includes/header',$data);
		$this->template->write_view('leftbar',$this->admin_dir.'/includes/leftbar',$data);
		$this->template->write_view('rightbar',$this->admin_dir.'/includes/rightbar',$data);
		$this->template->write_view('content',$this->admin_dir.'/translation/form',$data);
		$this->template->render();
	}
	else {
		redirect(admin_url('access_denied'));
	}
}

function get_translation()
{
	//$model = $section."_m";
	$section = $this->module;
		$id = $this->id;
		$lang = $this->lang;
	$model_custom = $section.'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}

	$this->load->model($model);
	
	
	$table_ext = '';
	if(enable_review() && !user_can_publish())
		$table_ext = "_review";
		
		if($lang == default_lang()) {
			$data["info"] = $this->db->where(array(
		'id_'.$section=>$id
	))->get($section.$table_ext)->row_array();
		}
		else {
			$data["info"] = $this->db->where(array(
		'translation_id'=>$id,
		'lang'=>$lang
	))->get($section.$table_ext)->row_array();
		}
	
	
	//if(empty($data["info"]))
	//$data["info"] = $this->$model->get($id);
	
	$data['lang'] = $lang;

	$data["section"] = $section;
	$data['record'] = $id;
	$data['fields'] = $this->fct->get_module_fields($section);
	
	$return['result'] = 1;
	$return['html'] = $this->load->view($this->admin_dir."/translation/form-fields",$data,true);
	
	if(seo_enabled($section)) {
		//echo $section.' / '.$id.' / '.$lang;exit;
		$seo = get_seo(0,$section,$id,$lang);
		$return['html_seo'] = $this->load->view($this->admin_dir."/fields/seo",array("info"=>$seo),true);
	}
	
	$this->load->view('json',array("return"=>$return));	
}

function submit_translation()
{
	$postdata = $this->input->post();
	$section = $postdata["section"];
	check_permission($section,'translate');
	
		$table_ext = '';
	if(enable_review() && !user_can_publish())
		$table_ext = "_review";
		
	$id = $postdata["record"];
	$lang = $postdata["lang"];
	$title = $postdata["title"];
	//$model = $section."_m";
	
	$model_custom = $section.'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}

	$this->load->model($model);
	
	$default_record = $this->$model->get($id);
	$translation_id = $id;
	if($lang == default_lang()) $translation_id = 0;
	$check = $this->db->where(array(
		'translation_id'=>$translation_id,
		'lang'=>$lang
	))->get($section.$table_ext)->row_array();
	//echo $this->db->last_query();exit;
	if($table_ext != '') {
		$check1 = $this->db->where(array(
			'translation_id'=>$translation_id,
			'lang'=>$lang
		))->get($section)->row_array();
	}
	$fields = $this->fct->get_module_fields($section);
	$arr = array();
	foreach($fields as $val) {
		$field = str_replace(" ","_",$val['name']);
		if(isset($postdata[$field])) {			
			$content_type_attr_type = $this->db->select("a.type")->from("content_type_attr a")->join("content_type c","c.id_content = a.id_content")->where(array(
		"c.name"=>$section,
		"a.name"=>str_replace("_"," ",$field)
	))->get()->row_array();
			if(isset($content_type_attr_type["type"]) && $content_type_attr_type["type"] == 1)
			$arr[$field] = dateformat($postdata[$field],"Y-m-d");
			elseif(isset($content_type_attr_type["type"]) && $content_type_attr_type["type"] == 7) {
				//if()
			}
			else
			$arr[$field] = $postdata[$field];
		}
		else {
			if(isset($default_record[$field]))
			$arr[$field] = $default_record[$field];
		}
	}
	$arr['title'] = $this->input->post("title");
	$arr['sort_order'] = $default_record['sort_order'];
	$arr['status'] = $default_record['status'];
	if(user_can_publish())
	$arr['status'] = $default_record['status'];
	else
	$arr['status'] = 0;
	if(empty($check)) {
		$arr['translation_id'] = $id;
		$arr['lang'] = $postdata["lang"];
		$this->db->insert($section.$table_ext,$arr);
		if($table_ext != '' && empty($check1)) {
			$this->db->insert($section,$arr);
		}
		$rid = $this->db->insert_id();
		$this->fct->insert_log("create",$section,$rid);		
		/*if(enable_review()) {
			$arr["id_".$section] = $rid;
			$this->db->insert($section."_review",$arr);
		}*/
		if($rid) {
			//if(enable_review() && !user_can_publish())
			$this->fct->record_review($section,$id,$postdata["lang"]);
			$this->fct->insert_log("create",$section,$rid);
		}
	}
	else {
		//print '<pre>'; print_r($arr); exit;
		$rid = $check["id_".$section];
		$this->db->where("id_".$section,$rid)->update($section.$table_ext,$arr);
		$affected_rows = $this->db->affected_rows();
		if(enable_review()) {
			if(user_can_publish()) {
				$this->db->where("id_".$section,$rid)->update($section."_review",$arr);
			}
			else {
				if($affected_rows > 0) {
					$this->fct->record_review($section,$id,$postdata["lang"]);
				}
			}
		}
		if($affected_rows > 0)
		$this->fct->insert_log("update",$section,$rid);
	}
	if(enable_review() && isset($postdata["approval"])) {
		$this->fct->change_approval($section,$rid,$postdata["lang"],$postdata["approval"]);
	}
	// translate seo
	if(seo_enabled($section)) {
		$check = $this->db->where(array(
			"module"=>$section,
			"record"=>$id,
			"lang"=>$postdata["lang"]
		))->get("seo")->row_array();
	
		$fields_seo = $this->fct->get_module_fields('seo');
		$seo_info = array();
		
		foreach($fields_seo as $seo_field) {
			$fld = str_replace(" ","_",$seo_field['name']);
			if(isset($postdata[$fld])) $seo_info[$fld] = $postdata[$fld];
		}
		$affected_rows = 0;
		if(!empty($seo_info)) {
			if(empty($check)) {
				$seo_info['module'] = $section;
				$seo_info['record'] = $id;
				$seo_info['lang'] = $postdata["lang"];
				$get_parent = $this->db->where(array("module"=>$section,"record"=>$id))->get("seo")->row_array();
				$seo_info['translation_id'] = $get_parent['id_seo'];
				$this->db->insert("seo",$seo_info);
				$affected_rows = $this->db->affected_rows();
				$nid = $this->db->insert_id();
				$this->fct->insert_log("create","seo",$nid);
			}
			else {
				$this->db->where("id_seo",$check['id_seo'])->update("seo",$seo_info);
				$affected_rows = $this->db->affected_rows();
				$this->fct->insert_log("update","seo",$check['id_seo']);
			}
		}
		if($affected_rows > 0)
		$this->fct->update_routes();
	}
	// special conditions
	if($section == 'static_words') {
		$this->fct->update_translations();
	}
	$this->session->set_userdata("success_message","Translation updated.");
	//if($this->session->userdata("admin_redirect_link") != "")
	//redirect( $this->session->userdata("admin_redirect_link") );
	//else
	redirect(admin_url('manage/display/'.$section));
}


}