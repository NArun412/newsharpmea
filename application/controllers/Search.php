<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Search extends My_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("search_engine_model");
		$this->lang->load('statics');
	$this->langue = $this->lang->lang();
	}

	
	public function index() {
		if ($this->input->get("keywords") != "") {
			$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
			$data['seo']        = get_seo(1);
			$data["lang"] = $this->langue;
			$keywords           = $this->input->get("keywords");
			//echo custom_url_clean($keywords);exit;
			$keywords           = urldecode($keywords);
			//echo $keywords.'<br />'.$this->db->escape_like_str($keywords);exit;
			//$keywords           = $this->db->escape_str($keywords);
			
			
			$data['page_title'] = 'Search results for: <span class="keywordColor">' . $keywords . '</span>';
			$data['keywords']   = $this->db->escape_like_str($keywords);
			
			$url                         = route_to('home/search') . '?pagination=on&keywords=' . $keywords;
			$cc                          = $this->search_engine_model->searchWebsite($keywords,"page");
			//echo $cc;exit;
			$config['base_url']          = $url;
			$config['total_rows']        = $cc;
			$config['num_links']         = '8';
			$config['per_page']          = 15;
			$config['use_page_numbers']  = TRUE;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			if (isset($_GET['per_page'])) {
				if ($_GET['per_page'] != '')
					$page = $_GET['per_page'];
				else
					$page = 0;
			} else
				$page = 0;
			$data['count']   = $cc;
			$data['results'] = $this->search_engine_model->searchWebsite($keywords,"page", $config['per_page'], $page);
			/*
			// breadcrumbs
			$breadcrumbs             = array();
			$breadcrumbs[0]['title'] = lang('home');
			$breadcrumbs[0]['link']  = site_url();
			$breadcrumbs[1]['title'] = $data['page_title'];
			$crumbs['breadcrumbs']   = $breadcrumbs;
			// end breadcrumbs
			*/
			
			$this->template->write_view('header', 'includes/header', $data);
			$this->template->write('content', '<div class="content_inner no-banner" ><div class="centered4">');
			$this->template->write_view('content', 'search/search-results', $data);
			$this->template->write('content', '</div></div>');
			$this->template->write_view('footer', 'includes/footer', $data);
			
			$this->template->render();
			
		} else {
			redirect(site_url());
		}
	}
	
	function AutoComplete() {
		$keywords = $this->input->post("v", TRUE);
		$keywords = urldecode($keywords);
		$keywords = xss_clean($keywords);
		$keywords = str_replace("'", "", $keywords);
		$results = $this->search_engine_model->searchWebsite($keywords,"autocomplete", 300, 0);
		$json = "";
		if(!empty($results)) {
			$json = $this->load->view("search/search-results",array(
				"results"=>$results,
				"keywords"=>$keywords
			),true);
			$json = json_encode($json);
		}
		$return['nct'] = $this->security->get_csrf_hash();
		$return['json'] = $json;
		//$results = escapeJsonString( json_encode($results) );
		//header('Content-Type: application/json');
		//die( $json );
		$this->load->view('json',array('return'=>$return));
	}	
}