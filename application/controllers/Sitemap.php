<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sitemap extends My_Controller{

public function __construct(){
	parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
}

public function index(){
$data = array();
$data["seo"] = get_seo(17);
//$data["inner_banners"] = $this->fct->getAll_cond('website_pages_gallery','sort_order',array( 'id_website_pages' => $data["seo"]["id_website_pages"] ));
$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));

$data['banners'] = $this->website_fct->get_banners(16);

$data['divisions'] = $this->website_fct->get_all_divisions();

$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/sitemap',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

}