<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends My_Controller {

public function __construct(){
	parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
}

public function index() {
$data = array();
$data["seo"] = get_seo(1);
$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
$data['banners'] = $this->website_fct->get_banners(1);
//print '<pre>'; print_r($data['banners']); exit;
$data['latest_news'] = $this->website_fct->get_latest_news(array("t.date <"=>today()),3,0);
$data['boxes'] = $this->website_fct->get_home_page_boxes();
$data['intro'] = $this->website_fct->get_dynamic(13);
$data['icon'] = $this->website_fct->get_icon();
$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/home',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

public function subscribe() {
	$allPostedVals = $this->input->post();		
$this->form_validation->set_rules("newslettername", ucfirst(lang("name")), "trim|required");
$this->form_validation->set_rules("newsletteremail", ucfirst(lang("email")), "trim|required|valid_email|unique[newsletter_subscribers.email.]");
if ($this->form_validation->run() == FALSE) {
	$return['nct'] = $this->security->get_csrf_hash();
  $return['result'] = 0;
  $return['errors'] = array();
  $return['message'] = validation_errors();
  $find =array('<p>','</p>');
  $replace =array('','');
  foreach($allPostedVals as $key => $val) {
	  if(form_error($key) != '') {
		  $return['errors'][$key] = str_replace($find,$replace,form_error($key));
	  }
  }
}
else
{		
	$_data["name"]=$this->input->post("newslettername");
	$_data["email"]=$this->input->post("newsletteremail");
	$_data["created_on"]=date("Y-m-d H:i:s");
	$this->db->insert('newsletter_subscribers',$_data); 
	$new_id = $this->db->insert_id();	
	$this->load->model("send_emails");
	$this->send_emails->sendNewsletterSubscribeReplyToUser($_data);
	$return['result'] = 1;
	$return['message'] = lang("You email is added to our newsletter database, thank you.");
}	
$this->load->view("json",array("return"=>$return));
}
function import_timeline()
{
	foreach(history_years() as $yr) {
		$title1 = 'Founder, Tokuji Hayakawa, invents the Tokubijo snap buckle and acquires utility model design patent Founder, Tokuji Hayakawa, invents the Tokubijo snap buckle and acquires utility model design patent';
		$title2 = 'Establishes metalworking shop in Matsui-cho, Honjo-ku, Tokyo (now Shin-ohashi, Koto-ku, Tokyo) on September 15';
		$this->db->insert("history_timeline",array(
			"title"=>$title1,
			'created_on'=>date("Y-m-d H:i:s"),
			"year"=>$yr,
			"lang"=>$this->lang->lang(),
			//"title_url"=>$this->fct->cleanURL("history_timeline",url_title($title1))
		));
		$this->db->insert("history_timeline",array(
			"title"=>$title2,
			'created_on'=>date("Y-m-d H:i:s"),
			"year"=>$yr,
			"lang"=>$this->lang->lang(),
			//"title_url"=>$this->fct->cleanURL("history_timeline",url_title($title2))
		));
	}
die("done");
}


/*function clean_translation_files()
{
	$tables = $this->db->get("content_type")->result_array();
	foreach($tables as $table) {
		if(file_exists('./application/views/back_office/'.$table['used_name'].'/translate.php')) {
			unlink('./application/views/back_office/'.$table['used_name'].'/translate.php');
		}
		if(file_exists('./application/views/back_office/'.$table['used_name'].'/add_translation.php')) {
			unlink('./application/views/back_office/'.$table['used_name'].'/add_translation.php');
		}
	}
	echo "Done";
}


function update_status()
{
	$tables = $this->db->get("content_type")->result_array();
	foreach($tables as $table) {
		if ($this->db->table_exists($table['used_name']) )
		{
			$this->db->query("UPDATE ".$table['used_name']." SET status = 1 WHERE status = 0");
		}
	}
	echo "Done";
}

function move_product_lines()
{
	$lines = $this->db->get("products_lines")->result_array();
	print '<pre>'; print_r($lines); exit;
	if(!empty($lines)) {
		foreach($lines as $line) {
			$this->db->insert("categories",array(
				"created_on"=>$line['created_on'],
				"sort_order"=>$line['sort_order'],
				"status"=>$line['status'],
				"title"=>$line['title'],
				"lang"=>$line['lang'],
				"id_parent"=>$line['id_categories'],
				"plid"=>$line['id_products_lines']
			));
			$this->fct->insert_log("create","products_lines",$line['id_products_lines'],1);
		}
	}
	exit("Done");
}

function insert_into_log()
{
	$tables = $this->db->get("content_type")->result_array();
	foreach($tables as $table) {
		$all = $this->db->get($table['used_name'])->result_array();
		if(!empty($all)) {
			foreach($all as $al) {
				$this->fct->insert_log("create",$table['used_name'],$al['id_'.$table['used_name']],1);
			}
		}
	}
	echo "Done";
}
function update_dates()
{
	$tables = $this->db->get("content_type")->result_array();
	foreach($tables as $table) {
		if ($this->db->table_exists($table['used_name']) )
		{
			$this->db->query("UPDATE ".$table['used_name']." SET created_on = '".date("Y-m-d H:i:s")."' WHERE created_on = '' OR created_on = '0000-00-00 00:00:00'");
		}
	}
	echo "Done";
}

function create_dates()
{
	$tables = $this->db->get("content_type")->result_array();
	foreach($tables as $table) {
		if ($this->db->table_exists($table['used_name']) )
		{
			if (!$this->db->field_exists('created_on', $table['used_name'])) {
				$this->db->query("ALTER TABLE `".$table['used_name']."` ADD `created_on` DATETIME NOT NULL AFTER `id_".$table['used_name']."`");
			}
		}
	}
	echo "Done";
}
function update_products_categories()
{
	$this->load->model("products_m");
	$products = $this->db->get('products')->result_array();
	foreach($products as $prod) {
		$this->products_m->insert_bulk_categories($prod['id_products'],$prod['id_categories']);
	}
	exit("Done");
}
*/
function test_zip_o()
{
	$this->load->library('zip');
	$dir = './captcha';
	$files = scandir($dir.'/');
	ob_end_clean();
	if(!empty($files)) {
	 foreach($files as $file) {
		$path = $dir.'/'.$file;
		$this->zip->read_file($path,false);
	 }
	 ob_end_clean();
	 $this->zip->download('download.zip');
	// exit('Done!');
	}
	else {
	// exit('No files..');
	}
}

function merge_divisions_categories()
{
  $cats = $this->fct->get_tree('categories',0,FALSE);
  foreach($cats as $cat) {
	  $div_id = get_cell('categories','id_divisions','id_categories',$cat['id']);
	  $this->do_merge($cat['id'],$div_id);
  }
  exit('Done!');
}
function do_merge($cid,$div_id)
{
	$levels = $this->fct->get_tree('categories',$cid,FALSE);
	if(!empty($levels)) {
		foreach($levels as $lev) {
			$this->db->where('id_categories',$lev['id'])->update('categories',array('id_divisions'=>$div_id));
			$this->do_merge($lev['id'],$div_id);
		}
	}
	return true;
}
function add_ref_to_reviews()
{
	$get_modules = $this->db->get('content_type')->result_array();
	foreach($get_modules as $gm) {
		$table_name = str_replace(' ','_',$gm['name']);
		if ($this->db->table_exists($table_name."_review") && !$this->db->field_exists('live_id',$table_name."_review"))
		{
		    $this->db->query("ALTER TABLE `".$table_name."_review` ADD `live_id` INT( 11 ) NOT NULL;");
			$this->db->query("ALTER TABLE `".$table_name."_review` ADD  INDEX (`live_id`)");
		}
	}
	exit('Done!');
}
function refresh_reviews()
{
	$this->db->truncate('content_type_reviews');
	$get_modules = $this->db->get('content_type')->result_array();
	foreach($get_modules as $gm) {
		$table_name = str_replace(' ','_',$gm['name']);
		if ($this->db->table_exists($table_name."_review"))
		{
			$fields = $this->db->list_fields($table_name);
			$i = array();
			foreach($fields as $fld) {
				array_push($i,$fld);
			}
			$this->db->truncate($table_name.'_review');
			$q = 'INSERT INTO '.$table_name.'_review (`'.implode('`,`',$i).'`,`live_id`) SELECT `'.implode('`,`',$i).'`,`id_'.$table_name.'` FROM '.$table_name.'';
			//exit($q);
			$this->db->query($q);
			//echo $this->db->last_query();exit;
		}
	}
	exit('Done!');
}
function moveUploads()
	{
		$get_modules = $this->db->get('content_type')->result_array();
		foreach($get_modules as $mod) {
			$delete_default_thumbs = array();
			$modulename = str_replace(' ','_',$mod['name']); 
			$fields = $this->db->where('id_content = '.$mod['id_content'].' AND(type = 4 OR type = 5)')->get('content_type_attr')->result_array();
		   //echo $this->db->last_query();exit;
			if(!empty($fields)) {
				foreach($fields as $fld) {
					$fieldname = str_replace(' ','_',$fld['name']);
					// get thumb vals
					$thumb = $fld['thumb'];
					$tumb_vals = array();
					if($thumb == 1) {
						$thumb_vals = explode(',',$fld['thumb_val']);
					}
					// create directories
					$path_old = './uploads/'.$modulename;
					$path_new = './uploads/'.$modulename.'/'.$fieldname;
					if(!is_dir($path_new))
					mkdir($path_new);
					
					if(!empty($thumb_vals)) {
						foreach($thumb_vals as $v) {
							if(!is_dir($path_new.'/'.$v))
							mkdir($path_new.'/'.$v);
							array_push($delete_default_thumbs,$v);
						}
					}
					// move files
					$records = $this->db->select($fieldname)->get($modulename)->result_array();
					if(!empty($records)) {
						foreach($records as $rec) {
							if($rec[$fieldname] != '' && $rec[$fieldname] != '0') {
								if(file_exists($path_old.'/'.$rec[$fieldname]))
								rename($path_old.'/'.$rec[$fieldname],$path_new.'/'.$rec[$fieldname]);
								if(!empty($thumb_vals)) {
									foreach($thumb_vals as $v) {
										if(file_exists($path_old.'/'.$v.'/'.$rec[$fieldname]))
										rename($path_old.'/'.$v.'/'.$rec[$fieldname],$path_new.'/'.$v.'/'.$rec[$fieldname]);
									}
								}
							}
						}
					}
				}
			}
			// delete old thumbs directories
			if(!empty($delete_default_thumbs)) {
				foreach($delete_default_thumbs as $dth) {
					$this->fct->deleteDirectory('./uploads/'.$modulename.'/'.$dth);
				}
			}
		}
		exit('Done');
	}
	
	
}