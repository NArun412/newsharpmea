<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Compliance extends My_Controller {

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
        $url = route_to('Compliance');
        $p = '?';

        

        $cc = $this->website_fct->get_compliance_files($cond, $like);
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

        $data['codeList'] = $this->website_fct->get_compliance_files($cond, $like, $config['per_page'], $page);
        $data["isEdit"] = 0;
        $this->template->write_view('header', 'includes/header', $data);
        $this->template->write_view('content', 'content/Compliance', $data);
        $this->template->write_view('footer', 'includes/footer', $data);
        $this->template->render();
    }

    public function edit($id) {
        $data = array();
        $data["seo"] = get_seo(1);
        $data["lang"] = $this->langue;

        $cond = array();
        $like = array();
        $cond['id_compliance'] = $id;
//        $cond['IsActive'] = 1;
        $like = array();
        $getFile = $this->website_fct->get_compliance_files($cond, $like, 1, 0);
        if ($getFile != null && count($getFile) > 0) {
            $data["content"] = $getFile[0]["content"];
            $data["title"] = $getFile[0]["title"];
            $data["isEdit"] = $id;
        }


//        $data["title"] = "Edit Files";
        $this->template->write_view('header', 'includes/header', $data);
        $this->template->write_view('content', 'content/Compliance', $data);
        $this->template->write_view('footer', 'includes/footer', $data);
        $this->template->render();
    }

}
