<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Db_actions extends My_Controller {
	public function __construct(){
		parent::__construct();
		$admin_dir = admin_dir();
		exit($admin_dir);
	//	if(!validate_user() || (validate_user() && user_role() != 1)) exit();
		$this->load->model($admin_dir."/database_g");
		$this->load->model($admin_dir."/fct");
		//exit('BB');
	}
	
	
	function update_translation_with_main1()
	{exit('AA');
		$tables = $this->fct->get_content_types_generator();
		exit('A');
		foreach($tables as $table) {
			
			$table_name = str_replace(' ','_',$table['name']);
			$fields = $this->fct->get_module_fields($table_name);
			
			$get_records = $this->db->where('translation_id',0)->get($table_name)->result_array();
			exit('A');
			foreach($get_records as $rec) {
				$arr = array();
				foreach($fields as $field) {
					$fld = str_replace(' ','_',$field['name']);
					if($field['translated'] == 0) {
						if($field['type'] == 7) {
							$ref_id = get_cell($field['foreign_table'],'id_'.$field['foreign_table'],'translation_id',$rec['id_'.$field['foreign_table']]);
							//$foreign_table = get_cell("content_type","used_name","id_content",$field['foreign_key']);
							$arr['id_'.$field['foreign_table']] = $ref_id;
						}
						else {
							$arr[$fld] = $rec[];
						}
					}
				}
				if(!empty($arr)) {
					$this->db->where(array(
						'translation_id'=>$rec['id_'.$table_name],
						'lang'=>'ar'
					))->update($table_name,$arr);
					exit($this->db->last_query());
				}
			}
			//print '<pre>'; print_r($fields); exit;
			echo $table_name.'<br>';
		}
		
		exit('Done');
	}
	
	function merge_divisions_categories()
	{
		$cats = $this->fct->get_tree('categories',0,FALSE);
		print '<pre>'; print_r($cats); exit;
		/*foreach($cats as $cat) {
			$div_id = $cat['id_divisions'];
			$sub_cats = $this->db->where("id_parent",$cat['id_categories'])->get("categories")->result_array();
			while(!empty($sub_cats)) {
				$sub_cats = $this->db->where("id_parent",$cat['id_categories'])->get("categories")->result_array();
			}
		}*/
	}
	function update_seo_ar()
	{
		$res = $this->db->where(array(
			'lang'=>'en',
			'module !='=>''
		))->get('seo')->result_array();
		foreach($res as $r) {
			$mod = $r['module'];
			$rec = $r['record'];
			$check_seo_ar = $this->db->where(array(
				'translation_id'=>$r['id_seo'],
				'lang'=>'ar'
			))->get($mod)->row_array();
			$get_ar = $this->db->where(array(
				'lang'=>'ar',
				'translation_id'=>$rec
			))->get($mod)->row_array();
			if(!empty($get_ar)) {
				$u = array(
					'title'=>$get_ar['title'],
					'meta_title'=>$get_ar['title'],
					'meta_keywords'=>$get_ar['title'],
					'meta_description'=>$get_ar['title'],
					'url_route'=>$get_ar['title'],
				);
				if(empty($check_seo_ar)) {
					$u['created_on'] = date('Y-m-d H:i:s');
					$u['status'] = 1;
					$u['lang'] = 'ar';
					$u['translation_id'] = $get_ar['id_seo'];
					$u['module'] = $mod;
					$u['record'] = $rec;
					$this->db->insert('seo',$u);
				}
				else {
					$this->db->where('id_seo',$get_ar['id_seo'])->update('seo',$u);
				}
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
	function reset_routes()
	{
		$seo = $this->db->where("translation_id",0)->where("module !=","")->where("lang","en")->get("seo")->result_array();
		$row = '<table><tr><th>Module</th><th>Title</th><th>Meta Title</th><th>URL</th></tr>';
		foreach($seo as $s) {
			$title = get_cell($s['module'],'title','id_'.$s['module'],$s['record']);
			if($title != $s['meta_title'])
			$this->db->where("id_seo",$s['id_seo'])->update("seo",array(
				"meta_title"=>$title,
				"url_route"=>$title
			));
			$row .= '<tr><td>'.$s['module'].'</td><td>'.get_cell($s['module'],'title','id_'.$s['module'],$s['record']).'</td><td>'.$s['meta_title'].'</td><td>'.$s['url_route'].'</td></tr>';
		}
		$row .= '</table>';
		$this->fct->update_routes();
		echo $row;
	}
	function reset_categories()
	{
	    $products = $this->db->where("translation_id",0)->get("products")->result_array();
	    if(!empty($products)) {
	        $this->load->model("products_m");
	        foreach($products as $pro) {
	            $this->products_m->insert_bulk_categories($pro['id_products'],$pro['id_categories']);
	        }
	    }
	    exit("Done");
	}
	function fill_reviews()
	{
		$tables = $this->fct->get_content_types_generator();
		foreach($tables as $tble) {
			$this->db->query("TRUNCATE TABLE ".$tble['name']."_review");
			$this->db->query("INSERT INTO ".$tble['name']."_review SELECT * FROM ".$tble['name']."");
		}
		exit("Done");
	}
	
	function fill_seo()
	{
		$modules = array("products","categories","divisions","corporate_profile_categories","environmental_and_csr","news");
		foreach($modules as $mod) {
			$records = $this->db->get($mod)->result_array();
			foreach($records as $rec) {
				$check = $this->db->where(array(
					"module"=>$mod,
					"record"=>$rec['id_'.$mod]
				))->get('seo')->num_rows();
				if($check == 0) {
					$this->fct->insert_update_seo($mod,$rec['id_'.$mod],array(
						"meta_title"=>$rec['title'],
						"url_route"=>$rec['title']
					));
				}
			}
		}
		exit("Done");
	}
	function generate_reviews()
	{
		$tables = $this->fct->get_content_types_generator();
		foreach($tables as $table) {
				$new_table = $table['used_name'].'_review';
				$old_table = $table['used_name'];
				//$q = 'CREATE TABLE '.$new_table.' LIKE '.$old_table.';';
				//$this->db->query($q);
				if ($this->db->table_exists($new_table) )
{
				$fields = $this->db->list_fields($old_table);
				$i = array();
				$f = '';
				$c = '';
				foreach($fields as $field) {
					$f .= $c.'`'.$field.'`';
					$c = ',';
				}
				$q = 'INSERT INTO `'.$new_table.'` ('.$f.') SELECT '.$f.' FROM `'.$old_table.'`';
				$this->db->query($q);
}
				if($table['gallery'] == 1) {
					//$q_gal = 'CREATE TABLE '.$new_table.'_gallery LIKE '.$old_table.'_gallery;';
					//$this->db->query($q_gal);
									if ($this->db->table_exists($new_table.'_gallery') )
{
					$fields_gallery = $this->db->list_fields($old_table.'_gallery');
					$f1 = '';
					$c1 = '';
					foreach($fields_gallery as $field_gal) {
						$f1 .= $c1.'`'.$field_gal.'`';
						$c1 = ',';
					}
					$q_gal1 = 'INSERT INTO `'.$new_table.'_gallery` ('.$f1.') SELECT '.$f1.' FROM `'.$old_table.'_gallery`';
					$this->db->query($q_gal1);
}
				}
		}
		exit("Done");
	}
	
	function update_controllers()
	{
		$tables = $this->fct->get_content_types_generator();
		foreach($tables as $table) {
			$this->database_g->create_controller($table['used_name'],$table['id_content']);
		}
		exit("Done");
	}
	
	function update_models()
	{
		$tables = $this->fct->get_content_types_generator();
		//print '<pre>'; print_r($tables); exit;
		foreach($tables as $table) {
			$this->database_g->create_model($table['used_name'],$table['id_content']);
		}
		exit("Done");
	}
	
	
	function update_views_add()
	{
		$tables = $this->fct->get_content_types_generator();
		foreach($tables as $table) {
			$this->database_g->create_add_view($table['used_name'],$table['id_content']);
		}
		exit("Done");
	}
	
	
	function get_cats()
	{exit();
		$products = $this->db->get("products")->result_array();
		$document_types = $this->db->get("document_types")->result_array();
		$products_languages = $this->db->get("products_languages")->result_array();
		$emulations = $this->db->get("emulations")->result_array();
		$operating_systems = $this->db->get("operating_systems")->result_array();
		foreach($products as $product) {
			foreach($document_types as $type) {
				foreach($products_languages as $lang) {
					$i = array(
						"file"=>"pdf.pdf",
						"id_products"=>$product['id_products'],
						"id_products_languages"=>$lang['id_products_languages'],
						"id_document_types"=>$type['id_document_types'],
					);
					if($type == 7) {
						foreach($operating_systems as $op) {
							foreach($emulations as $em) {
								$i['id_emulations'] = $em['id_emulations'];
								$i['id_operating_systems'] = $op['id_operating_systems'];
								$this->db->insert("product_support",$i);
							}
						}
					}
					elseif($type == 4) {
						foreach($operating_systems as $op) {
							$i['id_operating_systems'] = $op['id_operating_systems'];
							$this->db->insert("product_support",$i);
						}
					}
					else {
						$this->db->insert("product_support",$i);
					}
				}
			}
		}
		die("DONE!");
	}
}

