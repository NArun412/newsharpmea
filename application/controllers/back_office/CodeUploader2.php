<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CodeUploader extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->template->set_template("admin");
    }

    public function index() {
        $data["title"] = "List File";


        $cond = array();
        $like = array();
        $url = route_to('codeUploader');
        $p = '?';

        $cond['IsActive'] = 1;

        $cc = $this->fct->get_download_files($cond, $like);
        $config['base_url'] = $url;
        $config['total_rows'] = $cc;
        $config['num_links'] = '10';
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['per_page'] = 10;

        $this->pagination->initialize($config);
        if ($this->input->get('per_page') != '')
            $page = ($this->input->get('per_page') - 1) * $config['per_page'];
        else
            $page = 0;

        $data['codeList'] = $this->fct->get_download_files($cond, $like, $config['per_page'], $page);

        $this->template->write_view('header', 'back_office/includes/header', $data);
        $this->template->write_view('leftbar', 'back_office/includes/leftbar', $data);
        $this->template->write_view('rightbar', 'back_office/includes/rightbar', $data);
        $this->template->write_view('content', 'back_office/code_uploader/list', $data);
        $this->template->render();
    }

    public function thankyou() {
        $data = array();
        $data["seo"] = get_seo(6);
        $data['msg'] = $this->website_fct->get_dynamic(16);

        $data["lang"] = $this->langue;


        $this->template->write_view('header', 'back_office/includes/header', $data);
        $this->template->write_view('leftbar', 'back_office/includes/leftbar', $data);
        $this->template->write_view('rightbar', 'back_office/includes/rightbar', $data);
        $this->template->write_view('content', 'back_office/code_uploader/list', $data);
        $this->template->render();
    }

    public function submit() {

        if ($_FILES['uploadFile']['name'] != "" && $this->input->post('fileName') != "") {
           $fileName= $this->fct->uploadImage("uploadFile", "upload_files");
            $_data['originalFileName'] = $fileName;
            $_data['uploadedFileName'] = date('YmdHis') . "_" . $_FILES['uploadFile']['name'];
            $_data['isActive'] = 1;
            $_data['name'] = $this->input->post('fileName');
            $_data['createTime'] = date('Y-m-d H:i:s');

            $this->db->insert('upload_files', $_data);
            $return['result'] = 1;

            $this->session->set_userdata("success_message", "Information was inserted successfully");
        } else {
            $this->session->set_userdata("error_message", "File name and upload file is required");
             redirect(admin_url('CodeUploader/add'));
        }
        redirect(admin_url('CodeUploader'));
    }

    public function delete($id) {

        $cond['fileId'] = $id;
        $like = array();
        $getFile = $this->fct->get_download_files($cond, $like, 1, 0);
        if ($getFile != null && count($getFile) > 0) {
            unlink("./uploads/upload_files/" . $getFile[0]["originalFileName"]);
            $this->db->where(array(
                'fileId' => $id
            ))->delete('upload_files');
        }
        $this->session->set_userdata("success_message", "Information was deleted successfully");
        redirect(admin_url('CodeUploader'));
    }

    function downloadCode($token) {

        if (!empty($token)) {
            $queryForCode = 'select  * from upload_files where isActive=1 and fileId=' . $token;
            $query = $this->db->query($queryForCode);
            $get_file = $query->row_array();

            if (isset($get_file['originalFileName']) && !empty($get_file['originalFileName'])) {
                $this->load->helper('download');
                $file = './uploads/upload_files/' . $get_file['originalFileName'];
                
                $ext=pathinfo($get_file['originalFileName'], PATHINFO_EXTENSION);
//                echo $ext;
//                exit();
                
                header('Content-Description: File Transfer');
                header('Cache-Control: public');
                header('Content-Type: '.$ext);
                header("Content-Transfer-Encoding: binary");
                header('Content-Disposition: attachment; filename='. basename($file));
                header('Content-Length: '.filesize($file));
                ob_clean(); #THIS!
                flush();
                readfile($file);
                
//                	exit($file);
//                $filedata = file_get_contents($file);
//
//                force_download($get_file['originalFileName'], $filedata);
            } else {
                redirect(route_to("error404"));
            }
        } else {
            redirect(route_to("error404"));
        }

        if (!empty($token_access)) {
            $get_file = $this->db->where('id_product_support', $token_access['id_product_support'])->get('product_support')->row_array();
        } else {
            redirect(route_to("error404"));
        }
    }

    public function add() {
        $data["title"] = "Add Files";
        $data["content"] = "back_office/CodeUploader/add";
        $this->template->write_view('header', 'back_office/includes/header', $data);
        $this->template->write_view('leftbar', 'back_office/includes/leftbar', $data);
        $this->template->write_view('rightbar', 'back_office/includes/rightbar', $data);
        $this->template->write_view('content', 'back_office/code_uploader/add', $data);
        $this->template->render();
    }


}
