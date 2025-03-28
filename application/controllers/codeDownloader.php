<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CodeDownloader extends My_Controller{

public function __construct(){
parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
}

public function index() {
    $data = array();
    $data["seo"] = get_seo(1);
    $data["lang"] = $this->langue;
    
        $cond = array();
	$like = array();
	$url = route_to('CodeDownloader');
	$p = '?';
        
        $cond['IsActive'] = 1;
	if($this->input->get("keyword") != '') {
		 if ($this->langue == $this->default_langue)
		 $like['t.title'] = $this->input->get("keyword");
		 else
		$like['t1.title'] = $this->input->get("keyword");
		$url .= $p.'keyword='.$this->input->get("keyword");
		$p = '&';
	}
	$cc = $this->website_fct->get_download_files($cond,$like);

	
	$config['total_rows'] = $cc;
        $config['base_url'] = $url;
	$config['num_links'] = '10';
	$config['use_page_numbers'] = TRUE;
	$config['page_query_string'] = TRUE;
	$config['per_page'] = 10;
	$this->pagination->initialize($config);
	if($this->input->get('per_page') != '')
	$page = ($this->input->get('per_page') - 1) * $config['per_page'];
	else $page = 0;
        
        
	$data['count'] = $cc;
	$data['documents'] = $this->website_fct->get_download_files($cond,$like,$config['per_page'],$page);

    $this->template->write_view('header','includes/header',$data);
    $this->template->write_view('content','content/codeDownloader',$data);
    $this->template->write_view('footer','includes/footer',$data);
    $this->template->render();
}
function downloadCode($token)
{
   
    if(!empty($token)){ 
        $queryForCode = 'select  * from upload_files where isActive=1 and fileId='.$token;
        $query=$this->db->query($queryForCode);
        $get_file= $query->row_array();
        
        if(isset($get_file['uploadedFileName']) && !empty($get_file['uploadedFileName'])) {
		$this->load->helper('download');
		$file='./uploads/upload_files/'.$get_file['originalFileName'];
	//	exit($file);
		$filedata = file_get_contents($file);
		
		force_download($get_file['originalFileName'],$filedata); 
		
	  }
	  else {
		  redirect(route_to("error404"));
	  }
    }else {
	  redirect(route_to("error404"));
    }      
      
  if(!empty($token_access)) {
	  $get_file = $this->db->where('id_product_support',$token_access['id_product_support'])->get('product_support')->row_array();
	  
  }
  else {
	  redirect(route_to("error404"));
  }
 
}



	
}