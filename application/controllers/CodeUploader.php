<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CodeUploader extends My_Controller {

    public function __construct() {
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
        $url = route_to('codeUploader');
        $p = '?';

        $cond['IsActive'] = 1;

        $cc = $this->website_fct->get_download_files($cond, $like);
        $config['base_url'] = $url;
        $config['total_rows'] = $cc;
	$config['num_links'] = '10';
	$config['use_page_numbers'] = TRUE;
	$config['page_query_string'] = TRUE;
	$config['per_page'] = 10;
        
	$this->pagination->initialize($config);
        if($this->input->get('per_page') != '')
	$page = ($this->input->get('per_page') - 1) * $config['per_page'];
	else $page = 0;
        
        $data['codeList'] = $this->website_fct->get_download_files($cond, $like, $config['per_page'], $page);

        $this->template->write_view('header', 'includes/header', $data);
        $this->template->write_view('content', 'content/codeUploader', $data);
        $this->template->write_view('footer', 'includes/footer', $data);
        $this->template->render();
    }

    public function thankyou() {
        $data = array();
        $data["seo"] = get_seo(6);
        $data['msg'] = $this->website_fct->get_dynamic(16);

        $data["lang"] = $this->langue;


        $this->template->write_view('header', 'includes/header', $data);
        $this->template->write_view('content', 'content/thankyou', $data);
        $this->template->write_view('footer', 'includes/footer', $data);
        $this->template->render();
    }

    public function submit() {

        if ($_FILES['uploadFile']['name'] != "" && $this->input->post('fileName') != "") {
            $this->fct->uploadImage("uploadFile", "upload_files");
            $_data['originalFileName'] = $_FILES['uploadFile']['name'];
            $_data['uploadedFileName'] = date('YmdHis') ."_". $_FILES['uploadFile']['name'];
            $_data['isActive'] = 1;
            $_data['name'] = $this->input->post('fileName');
            $_data['createTime'] = date('Y-m-d H:i:s');

            $this->db->insert('upload_files', $_data);
            $return['result'] = 1;

            $this->session->set_userdata("success_message", "Information was inserted successfully");
        }else{
             $this->session->set_userdata("error_message", "File name and upload file is required");
        }
        redirect(site_url() . 'codeUploader');
    }

    public function delete($id) {

        $cond['fileId'] = $id;
        $like = array();
        $getFile = $this->website_fct->get_download_files($cond, $like, 1, 0);
        if ($getFile != null && count($getFile) > 0) {
            unlink("./uploads/upload_files/" . $getFile[0]["originalFileName"]);
            $this->db->where(array(
                'fileId' => $id
            ))->delete('upload_files');
        }
        $this->session->set_userdata("success_message", "Information was deleted successfully");
        redirect(site_url() . 'codeUploader');
    }

}
