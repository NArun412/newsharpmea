<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class News extends My_Controller{

public function __construct(){
	parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
}

public function index(){
	$data = array();
	$data["seo"] = get_seo(13);
	$data["lang"] = $this->langue;
	$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
	
	$data['up_coming_events'] = $this->website_fct->up_coming_events();
	$data['popular_news'] = $this->website_fct->get_latest_news(array("t.date <"=>today(),'t.set_as_popular'=>1),3,0);

	$cond = array("t.date <"=>today());
	$cc = $this->website_fct->get_news($cond);
	$config['base_url'] = route_to('news');
	$config['total_rows'] = $cc;
	$config['num_links'] = '8';
	$config['use_page_numbers'] = TRUE;
	$config['page_query_string'] = TRUE;
	$config['per_page'] = 6;
	$this->pagination->initialize($config);
	if($this->input->get('per_page') != '')
	$page = $this->input->get('per_page');
	else $page = 0;
	$data['count'] = $cc;
	$data['news'] = $this->website_fct->get_news($cond,$config['per_page'],$page);
		
	$data['banners'] = $this->website_fct->get_banners(8);
	
    $this->template->write_view('header','includes/header',$data);
    $this->template->write_view('content','content/news',$data);
    $this->template->write_view('footer','includes/footer',$data);
    $this->template->render();
}

public function details($id){
if(!is_numeric($id)) exit('access denied...');
$data = array();

//$data["inner_banners"] = $this->fct->getAll_cond('website_pages_gallery','sort_order',array( 'id_website_pages' => $data["seo"]["id_website_pages"] ));
$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));

$data['up_coming_events'] = $this->website_fct->up_coming_events();
$data['popular_news'] = $this->website_fct->get_news(array("t.date <"=>today(),'t.set_as_popular'=>1),3,0);

$data['article'] = $this->website_fct->get_new_article($id);
if(empty($data['article'])) redirect(route_to("error404"),404);

$data['related_news'] = $this->website_fct->get_news(array("t.date <"=>today()),3,0);
	
$data['banners'] = $this->website_fct->get_banners(0,'news',$data['article']['id']);
	
$data["seo"] = get_seo(0,'news',$id);
	
$data['manage_module'] = 'news';
$data['manage_record'] = $id;

$data['switch_links'] = $this->website_fct->switchLink_new('news/details/'.$data['article']['id']);

$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/news-details',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

}