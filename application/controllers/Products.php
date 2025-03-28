<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Products extends My_Controller{
function _remap($method,$args)
{ 
    if (method_exists($this, $method))
    {
        $this->$method($args);
    }
    else
    {
        //$this->index($method,$args);
		$this->index($args);
    }
}
public function __construct(){
	parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
	$this->load_limit = 12;
}

public function details($args = array()){
if(empty($args) || (isset($args[0]) && !is_numeric($args[0]))) exit("access denied");
$id = $args[0];

$data = array();

//$data["inner_banners"] = $this->fct->getAll_cond('website_pages_gallery','sort_order',array( 'id_website_pages' => $data["seo"]["id_website_pages"] ));
$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
//$data['product'] = array();
$data['product'] = $this->website_fct->get_one_product($id);

$data['switch_links'] = $this->website_fct->switchLink_new('products/details/'.$id);

if(empty($data['product'])) redirect(route_to("error404"),404);
//redirect(route_to("error404"));
//print '<pre>'; print_r($data['product']); exit;
if(!empty($data["product"]))
$data["seo"] = $data["product"];

$similar_cond = array();
//if($data['product']['product_line_id'] != 0)
//$similar_cond = array('p.id_products_lines'=>$data['product']['product_line_id']);
//print '<pre>'; print_r($data['product']);exit;
if($data['product']['category_id'] != 0)
$similar_cond['p.id_categories'] = $data['product']['category_id'];

if($data['product']['division_id'] != 0) {
$similar_cond['p.id_divisions'] = $data['product']['division_id'];
$data['render_theme'] = get_division_theme($data['product']['division_id']);
}

$similar_cond['p.id_products !='] = $data['product']['id'];
$data['similar_products'] = $this->website_fct->get_products($similar_cond,5,0);
$data["seo"] = get_seo(0,'products',$id);
if($data["seo"]['meta_title'] == '') $data["seo"]['meta_title'] = $data["product"]['title'];
//print '<pre>'; print_r($data['seo']);exit;

$breadcrumbs = array();
$breadcrumbs[0]['title'] = $data['product']['division_name'];
$breadcrumbs[0]['link'] = route_to('products/index/'.$data['product']['division_id']);
$breadcrumbs[1]['title'] = $data['product']['category_name'];
$breadcrumbs[1]['link'] = route_to('products/index/'.$data['product']['division_id'].'/'.$data['product']['category_id']);
//$breadcrumbs[2]['title'] = $data['product']['product_line_name'];
$data['breadcrumbs'] = $breadcrumbs;
// get banners
$data['banners'] = $this->website_fct->get_banners(0,'products',$data['product']['id']);
/*if(empty($data['banners']) && $data['product']['category_id'] != 0) {
	$data['banners'] = $this->website_fct->get_banners(6,$data['product']['category_id']);
	$parent = $this->db->where("id_categories",$data['product']['category_id'])->get("categories")->row_array();
	while(empty($data['banners'])) {
		if($parent['id_parent'] != 0) {
			$parent = $this->db->where("id_categories",$parent['id_parent'])->get("categories")->row_array();
			$data['banners'] = $this->website_fct->get_banners(6,$parent['id_categories']);
		}
	}
}*/
if(empty($data['banners']) && $data['product']['division_id'] != 0)
$data['banners'] = $this->website_fct->get_banners(0,'divisions',$data['product']['division_id']);
if(empty($data['banners']))
$data['banners'] = $this->website_fct->get_banners(4);
// end get banners
$data['manage_module'] = 'products';
$data['manage_record'] = $data['product']['id'];
$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/products-details',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}
public function index($args = array()) {
	$data = array();
	$data["lang"] = $this->langue;
	$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
	$data['all_divisions'] = $this->website_fct->get_all_divisions();
	$postdata = $this->input->get();
	//print_r($postdata);exit;
	if(!empty($postdata) && count($args) >= 1) {
		//exit("A");
		$this->listing($args,$data);
	}
	else {
		if(!empty($args)) {
			if(isset($args[0]) && is_numeric($args[0])) {
				if(count($args) > 1) {
					if(!has_sub_levels("categories",$args[count($args) - 1])) {
							$this->listing($args,$data);
					}
					else {//exit("C");
						$this->categories($args,$data);
					}
				}
				else {//exit("D");
					$this->categories($args,$data);
				}
			}
			else {//exit("E");
				$this->divisions($args,$data);
			}
		}
		else {//exit("F");
			$this->divisions($args,$data);
		}
	}
}

function divisions($args = array(),$data = array())
{
	$ques = '?';
	$cond = array();
	if(empty($args))
	$url = 'products';
	$url_paramters = '';
	$pagetitle_1 = lang('products');
	$pagetitle_2 = '';
	$data["seo"] = get_seo(14);
	$data['pagetitle_1'] = $pagetitle_1;
	$data['pagetitle_2'] = $pagetitle_2;
	$data['banners'] = $this->website_fct->get_banners(4);
	$this->template->write_view('header','includes/header',$data);
	$this->template->write_view('content','content/divisions',$data);
	$this->template->write_view('footer','includes/footer',$data);
	$this->template->render();
}

function categories($args = array(),$data = array())

{

	$ques = '?';
	$cond = array();
	$url = 'products/index';
	$url_paramters = '';
	$div_id = $args[0];
	$url .= '/'.$div_id;
	$data['category'] = array();
	$data['division'] = $this->website_fct->get_division($args[0]);
	$data['render_theme'] = get_division_theme($args[0]);
	$data['banners'] = $this->website_fct->get_banners(0,'divisions',$args[0]);
	if(count($args) > 1) {
		$cat_id = $args[count($args) - 1];
		$category = $this->website_fct->get_category($cat_id);
		$cond['t.id_parent'] = $cat_id;
		$data['manage_module'] = 'categories';
		$data['manage_record'] = $cat_id;
		$data["seo"] = get_seo(0,'categories',$cat_id);
		for($k = 1;$k<count($args);$k++) {
			$url .= '/'.$args[$k];
			$newbanners = $this->website_fct->get_banners(0,'categories',$args[$k]);
			if(!empty($newbanners))
			$data['banners'] = $newbanners;
		}
		$pagetitle_1 = $category['title'];
		$data['category'] = $category;
		$pagetitle_2 = '';
	}
	else {
		$cond['t.id_divisions'] = $div_id;
		$cond['t.id_parent'] = 0;
		$data['manage_module'] = 'divisions';
		$data['manage_record'] = $div_id;
		$data["seo"] = get_seo(0,'divisions',$div_id);
		$pagetitle_1 = $data['division']['title'];
		$pagetitle_2 = '';
	}
	$data['switch_links'] = $this->website_fct->switchLink_new($url);
	//print_r($cond);exit;
	$cc = $this->website_fct->get_categories_page($cond);
	$url = route_to($url);
	$data['url'] = $url;
	$config['base_url'] = $url.$url_paramters;
	$config['total_rows'] = $cc;
	$config['num_links'] = '8';
	$config['use_page_numbers'] = TRUE;
	$config['page_query_string'] = TRUE;
	$config['per_page'] = 9;
	$this->pagination->initialize($config);
	if($this->input->get('per_page') != '')
	$page = ($this->input->get('per_page') - 1) * $config['per_page'];
	else $page = 0;
	$data['count'] = $cc;
	$data['categories'] = $this->website_fct->get_categories_page($cond,$config['per_page'],$page);
	//$pagetitle_1 = $category['title'];
	//$pagetitle_2 = '';
	$data['pagetitle_1'] = $pagetitle_1;
	$data['pagetitle_2'] = $pagetitle_2;
	if(empty($data['banners']))
	$data['banners'] = $this->website_fct->get_banners(4);
	$this->template->write_view('header','includes/header',$data);
	$this->template->write_view('content','content/categories',$data);
	$this->template->write_view('footer','includes/footer',$data);
	$this->template->render();
}

function listing($args = array(),$data = array())
{
	$ques = '?';
	$cond = array();
	if(empty($args))
	$url = 'products';
	else
	$url = 'products/index';
	$url_paramters = '';
	$data['categories_dropdowns'] = array();
	$data['selected_categories'] = array();
	$division = array();
	$category = array();
	$div_id = $args[0];
	$division = $this->website_fct->get_division($div_id);
	$url .= '/'.$div_id;
	$cond['p.id_divisions'] = $div_id;
	$data['categories_dropdowns'][0] = $this->website_fct->get_categories_by_division($div_id);
	$data['render_theme'] = get_division_theme($div_id);
	$data['banners'] = array();
	$data['banners'] = $this->website_fct->get_banners(0,'divisions',$div_id);
	$cat_id = 0;
	$pagetitle_1 = $division['title'];
	$pagetitle_2 = '';
	$p_start = '';
	foreach($args as $k => $arg) {
		if($k > 0) {
			array_push($data['selected_categories'],$arg);
			$url .= '/'.$arg;
			$cat_id = $arg;
			$pagetitle_2 .= $p_start.'<a href="'.route_to($url).'">'.get_cell_translate("categories","title","id_categories",$arg).'</a>';
			$p_start = ' | ';
			$data['categories_dropdowns'][$arg] = $this->website_fct->get_categories_by_category($arg);
			$newbanners = $this->website_fct->get_banners(0,'categories',$arg);
			if(!empty($newbanners))
			$data['banners'] = $newbanners;
		}
	}
	if($cat_id != 0) { 
	$cond['p.id_categories'] = $cat_id;
	$category = $this->website_fct->get_category($cat_id);
	}
	if(count($args) > 1) {	
		$data["seo"] = get_seo(0,'categories',$cat_id);
		if($data["seo"]['meta_title'] == '') $data["seo"]['meta_title'] = $category['title'];
	}
	if(count($args) == 1) {
		$data["seo"] = get_seo(0,'divisions',$div_id);
		if($data["seo"]['meta_title'] == '') $data["seo"]['meta_title'] = $division['title'];
	}
	if($this->input->get("tag")) {
		$cond['tags'] = array();

		$filter_tags = $this->input->get("tag");
		foreach($filter_tags as $k => $v) {
			$url_paramters .= $ques.'tag['.$k.']='.$v;
			$ques = '&';
			if($v != '')
			array_push($cond['tags'],$v);
		}
	}

	if($this->input->get("modelno") != '') {
		$modelno = $this->input->get("modelno");
		$url_paramters .= $ques.'modelno='.$modelno;
		$ques = '&';
//echo $modelno;exit;
		$cond['p.modal_number'] = $modelno;
	}
	$data['switch_links'] = $this->website_fct->switchLink_new($url);
	//print '<pre>'; print_r($cond); exit;
	$cc = $this->website_fct->get_products($cond);
	$url = route_to($url);
	$data['url'] = $url;
	$config['base_url'] = $url.$url_paramters;
	$config['total_rows'] = $cc;
	$config['num_links'] = '8';
	$config['use_page_numbers'] = TRUE;
	$config['page_query_string'] = TRUE;
	$config['per_page'] = 9;
	$this->pagination->initialize($config);
	if($this->input->get('per_page') != '')
	$page = ($this->input->get('per_page') - 1) * $config['per_page'];

	else $page = 0;

	$data['count'] = $cc;

	$data['products'] = $this->website_fct->get_products($cond,$config['per_page'],$page);

	$data['left_tags'] = $this->website_fct->get_products_tags($cond);				

				

	$data['division'] = $division;

	$data['category'] = $category;

	

	$data['pagetitle_1'] = $pagetitle_1;

	$data['pagetitle_2'] = $pagetitle_2;

	

	if(empty($data['banners']))

	$data['banners'] = $this->website_fct->get_banners(4);

	//print '<pre>'; print_r($data); exit;

	$this->template->write_view('header','includes/header',$data);

	$this->template->write_view('content','content/products',$data);

	$this->template->write_view('footer','includes/footer',$data);

	$this->template->render();

}



function tag($args = array())

{

	if(empty($args) || (isset($args[0]) && !is_numeric($args[0]))) exit("access denied");



	$id = $args[0];

	$data = array();

	

	$offset = 0;

	if($this->input->get("offset") != "") {

		$offset = $this->input->get("offset");

	}

		

	$cond = array('tags'=>array($id));

	$cc = $this->website_fct->get_products($cond);



	$data['count'] = $cc;

	$data['products'] = $this->website_fct->get_products($cond,$this->load_limit,$offset);

		

	$data["lang"] = $this->langue;

	$url = route_to('products/tag/'.$id).'?pagination=on';

	$new_offset = $offset + $this->load_limit;

	$data['load_url'] = $url.'&offset='.$new_offset;

	if ($this->input->is_ajax_request()) {

		$return['content'] = $this->load->view("content/tag-products",$data,true);

		$return['new_url'] = $data['load_url'];

		$return['disable_load_more'] = 0;

		if( ($offset + $this->load_limit) >= $cc)

		$return['disable_load_more'] = 1;

		$this->load->view("json",array("return"=>$return));

	}

	else {

		

		$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));

		

		$data['tag'] = $this->website_fct->get_tag($id);

		

		$data["seo"] = get_seo(0,'products_tags',$id);

		if($data["seo"]['meta_title'] == '') $data["seo"]['meta_title'] = $data["tag"]['title'];

		

		$data['banners'] = $this->website_fct->get_banners(0,'products_tags',$data['tag']['id']);

		

		if(empty($data['banners']))

		$data['banners'] = $this->website_fct->get_banners(4);

		

		$data['manage_module'] = 'products_tags';

		$data['manage_record'] = $data['tag']['id'];

		

		$this->template->write_view('header','includes/header',$data);

		$this->template->write_view('content','content/tag-details',$data);

		$this->template->write_view('footer','includes/footer',$data);

		$this->template->render();

	}

}





function generate_url()

{

	$post = $this->input->post();

	$division = $post['division'];

	$categories = $this->input->post('category');

	$url = 'products/index';

	if($division != '' && is_numeric($division)) {

	$url .= '/'.$division;

		if(!empty($categories)) {

			foreach($categories as $cat) {

				if($cat != '')

				$url .= '/'.$cat;

			}

		}

	}
	$newurl = route_to($url);
	//echo 'URL: '.$url.'<br />'.route_to($url);exit;
	$return['nct'] = $this->security->get_csrf_hash();
	$return['newurl'] = $newurl;
	//echo $newurl;exit;
	//die($newurl);
	$this->load->view('json',array('return'=>$return));

}



function downloadfile($args)

{

	if(empty($args))exit("access denied");

	$id = $args[0];

  $app = $this->db->where('id_products_tabs',$id)->get('products_tabs')->row_array();

  if(isset($app['file']) && !empty($app['file'])) {

  	$this->load->helper('download');

  	$file=dynamic_img_url().'products_tabs/'.$app['file'];

  	$data = file_get_contents($file);

  	force_download($app['file'],$data); 

  }

}





}