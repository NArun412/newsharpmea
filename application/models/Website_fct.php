<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Website_fct extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->loggedIn       = validate_user();
        $this->langue         = $this->lang->lang();
        $this->default_langue = default_lang();
        //echo $this->default_langue.'-'.$this->langue;exit;
        $this->table_ext      = '';
        if($this->loggedIn)
        $this->table_ext = '_review';
		
		$this->status_table = 't';
		if($this->langue != $this->default_langue)
		$this->status_table = 't1';

        $this->pstatus_table = 'p';
        if($this->langue != $this->default_langue)
        $this->pstatus_table = 'p1';


        $this->psstatus_table = 'ps';
        if($this->langue != $this->default_langue)
        $this->psstatus_table = 'ps1';


    }
	
	public function switchLink_new($url_string)
{
	$links = array();
	$langs = $this->db->get("languages")->result_array();
	foreach($langs as $lng) {
		$links[$lng['symbole']] = route_to($url_string,$lng['symbole']);
	}
	return $links;
}

function get_div_id_by_category($cat_id)
{
	$div_id = 0;
	$cat = $this->db->where("id_categories",$cat_id)->get("categories")->row_array();
	$div_id = $cat['id_divisions'];
	$parent = $this->db->where("id_categories",$cat['id_parent'])->get("categories")->row_array();
	while(!empty($parent)) {
		$div_id = $parent['id_divisions'];
		$parent = $this->db->where("id_categories",$parent['id_parent'])->get("categories")->row_array();
	}
	return $div_id;
}
	 ////////////////////////////////////////////////////////////////////////////////////////////////
	function get_email_template($id)
    {
        if ($this->langue == $this->default_langue) {
            $select = "t.title, t.message, t.email, t.email_subject";
        } else {
            $select = "t1.title, t1.message, t.email, t1.email_subject";
        }
        $this->db->select($select);
        $this->db->from("emails_templates t");
        if ($this->langue != $this->default_langue)
        $this->db->join("emails_templates t1", "t.id_emails_templates = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
		 $this->db->where('t.id_emails_templates', $id);
       // $this->db->where($cond1 . ' !=', '');
        $query   = $this->db->get();
        $results = $query->row_array();
        return $results;
    }
	
    ////////////////////////////////////////////////////////////////////////////////////////////////
    function get_icon()
    {
        if ($this->langue == $this->default_langue) {
            $select = "t.id_popup__icons,t.icon, t.title, t.link, t.link_target";
            $cond1  = 't.icon';
        } else {
            $select = "t1.id_popup__icons,t1.icon, t1.title, t1.link, t1.link_target";
            $cond1  = 't1.icon';
        }
        $this->db->select($select);
        $this->db->from("popup__icons" . $this->table_ext . " t");
        if ($this->langue != $this->default_langue)
            $this->db->join("popup__icons t1", "t.id_popup__icons = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        if (!$this->loggedIn) {
            $this->db->where(''.$this->status_table.'.status', 1);
            //$this->db->where('t.record',$id);
            //$this->db->where('t.translation_id',0);
        }
        if ($this->langue == $this->default_langue)
            $this->db->where('t.translation_id', 0);
			else
			$this->db->where('t1.translation_id !=', 0);
        $this->db->where($cond1 . ' !=', '');
        $this->db->group_by('t.id_popup__icons');
        $this->db->order_by('t.sort_order');
        $query   = $this->db->get();
        //echo $this->db->last_query();exit;
        $results = $query->result_array();
        return $results;
    }
	
    ////////////////////////////////////////////////////////////////////////////////////////////////
	function get_banners($page = '',$module = '', $id = 0)
    {
        //$this->db->reset_query();
        if ($this->langue == $this->default_langue)
            $select = "t.id_banner AS id, t.brief, t.image, t.logo, t.title, t.link, t.link_label, t.link_target, t.brief_color, t.hide_title";
        else
            $select = "t.id_banner AS id, t1.brief, t.image, t.logo, t1.image AS image_ar, t1.logo AS logo_ar, t1.title, t.link, t.link_label, t.link_target, t.brief_color, t.hide_title";
        $this->db->select($select);
        $this->db->from("banner" . $this->table_ext . " t");
        if ($this->langue != $this->default_langue)
            $this->db->join("banner t1", "t.id_banner = t1.translation_id", "LEFT");
        if (!$this->loggedIn)
            $this->db->where(''.$this->status_table.'.status', 1);
        
        if ($this->langue == $this->default_langue)
            $this->db->where('t.translation_id', 0);
			else {
			$this->db->where('t1.lang', $this->langue);
			}
        if ($page != 0) {
			$this->db->where('t.page', $page);
		}
		elseif($module != '') {
            $this->db->where('t.module', $module);
			$this->db->where('t.record', $id);
			//$this->db->where('t.page', $page);
			//$this->db->where('t.record', $id);
		}
        $this->db->group_by('t.id_banner');
        $this->db->order_by('t.sort_order');
        $query   = $this->db->get();
	   // echo $this->db->last_query();exit;
        $results = $query->result_array();
        return $results;
    }
	
    function get_banners_11($module,$page = 0, $id = 0)
    {
        //$this->db->reset_query();
        if ($this->langue == $this->default_langue)
            $select = "t.id_banner AS id, t.brief, t.image, t.logo, t.title, t.link, t.link_label, t.link_target, t.brief_color, t.hide_title";
        else
            $select = "t.id_banner AS id, t1.brief, t.image, t.logo, t1.image AS image_ar, t1.logo AS logo_ar, t1.title, t.link, t.link_label, t.link_target, t.brief_color, t.hide_title";
        $this->db->select($select);
        $this->db->from("banner" . $this->table_ext . " t");
        if ($this->langue != $this->default_langue)
            $this->db->join("banner t1", "t.id_banner = t1.translation_id", "LEFT");
        if (!$this->loggedIn)
            $this->db->where(''.$this->status_table.'.status', 1);
        
        if ($this->langue == $this->default_langue)
            $this->db->where('t.translation_id', 0);
			else {
			$this->db->where('t1.lang', $this->langue);
			}
        if ($page != 0) {
			$this->db->where('t.page', $page);
		}
		elseif($module != '') {
            $this->db->where('t.module', $module);
			$this->db->where('t.record', $id);
		}
        $this->db->group_by('t.id_banner');
        $this->db->order_by('t.sort_order');
        $query   = $this->db->get();
		//echo $this->db->last_query();exit;
        $results = $query->result_array();
        return $results;
    }
	
    function get_dynamic($id)
    {
        if ($this->langue == $this->default_langue)
            $select = 't.id_website_pages AS id, t.title, t.description, t.image';
        else
            $select = 't.id_website_pages AS id, t1.title, t1.description, t.image';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("website_pages t1", "t.id_website_pages = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        if (!$this->loggedIn)
            $this->db->where(''.$this->status_table.'.status', 1);
        if ($this->langue == $this->default_langue)
            $this->db->where('t.translation_id', 0);
				else
				 $this->db->where('t1.translation_id !=', 0);
        $this->db->where('t.id_website_pages', $id);
        $this->db->group_by('t.id_website_pages');
        $query = $this->db->get('website_pages' . $this->table_ext . ' t');
        $res   = $query->row_array();
        return $res;
    }
	
    ////////////////////////////////////////////////////////////////////////////////////////////////
    function get_news($cond = array(), $limit = '', $offset = 0)
    {
		if($offset > 1) $offset = ($offset - 1) * $limit;
		else $offset = $offset * $limit;
        if ($this->langue == $this->default_langue)
            $select = 't.id_news AS id, t.title, t.date, t.date_to, t.brief';
        else
            $select = 't.id_news AS id, t1.title, t.date, t.date_to, t1.brief';
        $this->db->select($select);
        $this->db->from("news" . $this->table_ext . " t");
        if ($this->langue != $this->default_langue)
            $this->db->join("news t1", "t.id_news = t1.translation_id AND t1.lang = '" . $this->langue . "'");
        if (!$this->loggedIn)
            $this->db->where(''.$this->status_table.'.status', 1);
        if ($this->langue == $this->default_langue)
            $this->db->where('t.translation_id', 0);
				else
				 $this->db->where('t1.translation_id !=', 0);
			
			if(!empty($cond))
			$this->db->where($cond);
        $this->db->group_by('t.id_news');
        $this->db->order_by('t.date DESC');
        if ($limit == '') {
            $query = $this->db->get();
            return $query->num_rows();
        } else {
            $this->db->limit($limit, $offset);
            $query   = $this->db->get();
//			echo $this->db->last_query();exit;
            $results = $query->result_array();
            if (!empty($results)) {
                foreach ($results as $k => $res) {
                    $results[$k]['image'] = '';
                    $img                  = $this->db->where('id_news', $res['id'])->order_by('sort_order')->get('news_gallery')->row_array();
                    if (!empty($img))
                        $results[$k]['image'] = $img['image'];
                }
            }
            return $results;
        }
    }
	
    function get_latest_news($cond = array(), $limit = "", $offset = 0)
    {
        $res = $this->get_news($cond, 3, 0);
        return $res;
    }
	
    function up_coming_events()
    {
        if ($this->langue == $this->default_langue)
            $select = 't.id_news AS id, t.title, t.date, t.date_to, t.brief';
        else
            $select = 't.id_news AS id, t1.title, t.date, t.date_to, t1.brief';
        $this->db->select($select);
        $this->db->from("news" . $this->table_ext . " t");
        if ($this->langue != $this->default_langue)
            $this->db->join("news t1", "t.id_news = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        if (!$this->loggedIn)
            $this->db->where(''.$this->status_table.'.status', 1);
        if ($this->langue == $this->default_langue)
            $this->db->where('t.translation_id', 0);
				else
				 $this->db->where('t1.translation_id !=', 0);
        $this->db->where('t.date >=', today());
        $this->db->group_by('t.id_news');
        $this->db->order_by('t.date ASC');
        $query   = $this->db->get();
        $results = $query->row_array();
        if (!empty($results)) {
            $results['image'] = '';
            $img              = $this->db->where('id_news', $results['id'])->order_by('sort_order')->get('news_gallery')->row_array();
            if (!empty($img))
                $results['image'] = $img['image'];
        }
        return $results;
    }
	
    function get_new_article($id)
    {
        if (is_numeric($id)) {
            if ($this->langue == $this->default_langue)
                $select = 't.id_news AS id, t.title, t.date, t.date_to, t.description';
            else
                $select = 't.id_news AS id, t1.title, t.date, t.date_to, t1.description';
            $this->db->select($select);
            $this->db->from("news" . $this->table_ext . " t");
            if ($this->langue != $this->default_langue)
                $this->db->join("news t1", "t.id_news = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
            if (!$this->loggedIn)
                $this->db->where(''.$this->status_table.'.status', 1);
            if ($this->langue == $this->default_langue)
                $this->db->where('t.translation_id', 0);
				else
				 $this->db->where('t1.translation_id !=', 0);
            $this->db->where('t.id_news', $id);
            $query   = $this->db->get();
            $results = $query->row_array();
            if (!empty($results)) {
                $results['image']   = '';
                $gallery            = $this->db->where('id_news', $results['id'])->order_by('sort_order')->get('news_gallery')->result_array();
                $results['gallery'] = $gallery;
                if (!empty($gallery))
                    $results['image'] = $gallery[0]['image'];
            }
            return $results;
        }
        return false;
    }
	
    ////////////////////////////////////////////////////////////////////////////////////////////////
    function get_products($cond = array(), $limit = '', $offset = 0)
    {
        $for_ids = array();
        if(isset($cond['p.id_categories'])) {
            $for_ids = get_all_sub_ids('categories',$cond['p.id_categories'],$fld = 'id_categories');
        }
        if ($this->langue == $this->default_langue)
            $select = 'p.id_products AS id, p.title, p.image, p.product_type, p.specifications, c.title AS category_name, d.title AS division_name';
        else
            $select = 'p.id_products AS id, p1.title, p.image, p1.product_type, p1.specifications, c1.title AS category_name, d1.title AS division_name';
        $this->db->select($select);
        $this->db->from("products" . $this->table_ext . " p");
       /* if (isset($cond['p.id_categories'])) {
            $this->db->join("products_categories pc", "p.id_products = pc.id_products");
            $this->db->join('categories c', 'c.id_categories = pc.id_categories', 'LEFT');
        } else {*/
            $this->db->join('categories c', 'c.id_categories = p.id_categories', 'LEFT');
       // }
        $this->db->join('divisions d', 'd.id_divisions = p.id_divisions', 'LEFT');
        if (isset($cond['tags']) && !empty($cond['tags'])) {
            $this->db->join('products_products_tags ptr', 'ptr.id_products = p.id_products');
        }
        if ($this->langue != $this->default_langue) {
            $this->db->join('products p1', 'p.id_products = p1.translation_id AND p1.lang = "' . $this->langue . '"', 'LEFT');
            $this->db->join('categories c1', 'c.id_categories = c1.translation_id AND c1.lang = "' . $this->langue . '"', 'LEFT');
            $this->db->join('divisions d1', 'd.id_divisions = d1.translation_id AND d1.lang = "' . $this->langue . '"', 'LEFT');
        }
        if (isset($cond['tags']) && !empty($cond['tags'])) {
            $this->db->where_in('ptr.id_products_tags', $cond['tags']);
            unset($cond['tags']);
        }
        if (isset($cond['p.id_categories']) && !empty($for_ids)) {
            $this->db->where_in('p.id_categories', $for_ids);
            //	$this->db->where('pc.id_categories',$cond['p.id_categories']);
            unset($cond['p.id_categories']);
        }
        if (!$this->loggedIn) {
            $this->db->where($this->pstatus_table.'.status', 1);
			
        }
        if ($this->langue == $this->default_langue)
            $this->db->where('p.translation_id', 0);
			else
			$this->db->where('p1.translation_id !=', 0);
		if (isset($cond['p.modal_number'])) {
            $this->db->like('p.title', $cond['p.modal_number']);
            unset($cond['p.modal_number']);
        }
		
        if (!empty($cond))
            $this->db->where($cond);
        $this->db->group_by('p.id_products');
        $this->db->order_by('p.created_on desc');
        if ($limit == '') {
            $query = $this->db->get();
            //echo $this->db->last_query();exit;
            return $query->num_rows();
        } else {
            $this->db->limit($limit, $offset);
            $query   = $this->db->get();
            //echo $this->db->last_query();exit;
            $results = $query->result_array();
            return $results;
        }
    }
	
    function get_one_product($id)
    {
        if (is_numeric($id)) {
            if ($this->langue == $this->default_langue)
                $select = 'p.id_products AS id, p.title, p.image, p.product_type, p.specifications, p.description, c.title AS category_name, d.title AS division_name, c.id_categories AS category_id, d.id_divisions AS division_id, ct.iso_code AS country_code, ct.title AS country_name, p.product_link as product_link';
            else
                $select = 'p.id_products AS id, p1.title, p.image, p1.product_type, p1.specifications, p1.description, c1.title AS category_name, d1.title AS division_name, c.id_categories AS category_id, d.id_divisions AS division_id, ct.iso_code AS country_code, ct1.title AS country_name, p.product_link as product_link';
            $this->db->select($select);
            $this->db->from("products" . $this->table_ext . " p");
            //$this->db->join('products_lines pl','pl.id_products_lines = p.id_products_lines');
            $this->db->join('categories c', 'c.id_categories = p.id_categories');
            $this->db->join('divisions d', 'd.id_divisions = p.id_divisions');
			$this->db->join('countries ct', 'ct.id_countries = p.id_countries','LEFT');
            if ($this->langue != $this->default_langue) {
                $this->db->join('products p1', 'p.id_products = p1.translation_id AND p1.lang = "' . $this->langue . '"', 'LEFT');
                $this->db->join('categories c1', 'c.id_categories = c1.translation_id AND c1.lang = "' . $this->langue . '"', 'LEFT');
                $this->db->join('divisions d1', 'd.id_divisions = d1.id_divisions AND d1.lang = "' . $this->langue . '"', 'LEFT');
				$this->db->join('countries ct1', 'ct.id_countries = ct1.id_countries AND ct1.lang = "' . $this->langue . '"', 'LEFT');
            }
            if (!$this->loggedIn)
                $this->db->where($this->pstatus_table.'.status', 1);
			// $this->db->where($c);
            $this->db->where('p.id_products', $id);
            $query = $this->db->get();
		//	echo $this->db->last_query();exit;
            $res   = $query->row_array();
            if (!empty($res)) {
				 $res['brochures'] = $this->filter_support_products(array(
				 	'product'=>$id,
					'd'=>array(1)
				 ),5000,0);
				 $res['tags'] = $this->get_product_all_tags_categories($id);
				  $res['technologies'] = $this->get_product_technologies($id);
				 $res['gallery'] = $this->db->select('image, title')->where('id_products', $id)->order_by('sort_order')->get('products_gallery')->result_array();
                $cc_cond        = array(
                    't.module' => 'products',
					't.record'=>$id
                );
				 if ($this->langue == $this->default_langue)
				 $cc_cond['t.translation_id'] = 0;
				 else
				 $cc_cond['t1.translation_id !='] = 0;
                if (!$this->loggedIn)
                    $cc_cond[''.$this->status_table.'.status'] = 1;
                if ($this->langue != $this->default_langue)
                    $select1 = 't.id_products_tabs AS id, t1.title, t1.description, t.file';
                else
                    $select1 = 't.id_products_tabs AS id, t.title, t.description, t.file';
                $this->db->select($select1);
                if ($this->langue != $this->default_langue)
                    $this->db->join("products_tabs" . $this->table_ext . " t1", "t.id_products_tabs = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
                $this->db->where($cc_cond);
                $this->db->order_by('t.sort_order');
                $qq          = $this->db->get('products_tabs' . $this->table_ext . ' t');
                  //echo $this->db->last_query();exit;
                $res['tabs'] = $qq->result_array();
            }
			//print '<pre>'; print_r($res); exit;
            return $res;
        }
        return false;
    }
	
	function get_product_technologies($id)
	{
		$c = array();
		if ($this->langue == $this->default_langue) {
            $select1 = 't.id_products_technologies AS id, t.title, t.image, t.description';
        } else {
            $select1 = 't.id_products_technologies AS id, t1.title, t.image, t1.description';
        }
		$this->db->select($select1);
		if ($this->langue != $this->default_langue)
		$this->db->join("products_technologies" . $this->table_ext . " t1", "t.id_products_technologies = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
		$this->db->where(array(
			't.module'=>'products',
			't.record'=>$id
		));
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
			 $this->db->where($c);
		$this->db->group_by("t.id_products_technologies")->order_by('t.sort_order');
		$query = $this->db->get('products_technologies' . $this->table_ext . ' t');
		$res = $query->result_array();
		return $res;
	}
	
	function get_product_all_tags_categories($id)
	{
		$c = array();
		if ($this->langue == $this->default_langue) {
            $select1 = 't.id_products_tags AS id, t.title, t.icon';
        } else {
            $select1 = 't.id_products_tags AS id, t1.title, t.icon';
        }
		$this->db->select($select1);
		$this->db->join("products_tags t2","t2.id_parent = t.id_products_tags");
		$this->db->join("products_products_tags ptr","ptr.id_products_tags = t2.id_products_tags");
		if ($this->langue != $this->default_langue)
		$this->db->join("products_tags" . $this->table_ext . " t1", "t.id_products_tags = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
			 $this->db->where($c);
		$this->db->where(array(
		'ptr.id_products'=>$id
		))->group_by("t.id_products_tags")->order_by('t.sort_order');
		$query = $this->db->get('products_tags' . $this->table_ext . ' t');
		$res = $query->result_array();
		if(!empty($res)) {
			foreach($res as $k => $r) {
				$res[$k]['tags'] = $this->get_product_all_tags($id,$r['id']);
			}
		}
		return $res;
	}
	
	function get_product_all_tags($id,$cid)
	{
		$c = array();
		if ($this->langue == $this->default_langue) {
            $select1 = 't.id_products_tags AS id, t.title, t.icon';
        } else {
            $select1 = 't.id_products_tags AS id, t1.title, t.icon';
        }
		$this->db->select($select1);
		$this->db->join("products_products_tags ptr","ptr.id_products_tags = t.id_products_tags");
		if ($this->langue != $this->default_langue)
			$this->db->join("products_tags" . $this->table_ext . " t1", "t.id_products_tags = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
		$this->db->where(array(
		'ptr.id_products'=>$id,
		't.id_parent'=>$cid
		));
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
			 $this->db->where($c);
		$this->db->group_by("t.id_products_tags")->order_by('t.sort_order');
		$query = $this->db->get('products_tags' . $this->table_ext . ' t');
		$res = $query->result_array();
		return $res;
	}
	
    function get_products_tags($cond)
    {
        if ($this->langue == $this->default_langue) {
            $select  = 'pt1.id_products_tags AS id, pt1.title';
            $select1 = 't.id_products_tags AS id, t.title';
        } else {
            $select  = 'pt1.id_products_tags AS id, pt2.title';
            $select1 = 't.id_products_tags AS id, t1.title';
        }
        //$q = "SELECT pt.id_products_tags AS id, pt.title FROM products_tags {sub_join}";
        $sql_start_1 = "SELECT " . $select . " FROM products_tags" . $this->table_ext . " pt1 JOIN products_tags" . $this->table_ext . " pt ON pt1.id_products_tags = pt.id_parent";
        //$sql_start_2 = "SELECT pt.id_products_tags AS id, pt.title FROM products_tags pt";
        $q           = " JOIN products_products_tags ptr ON ptr.id_products_tags = pt.id_products_tags ";
        $q .= " JOIN products p ON p.id_products = ptr.id_products";
        if ($this->langue != $this->default_langue) {
            $q .= " LEFT JOIN products_tags pt2 ON pt1.id_products_tags = pt2.translation_id AND pt2.lang = '" . $this->langue . "'";
        //    $q .= " LEFT JOIN products p1 ON p1.id_products = p1.translation_id AND p1.lang = '" . $this->langue . "'";
        }
        $q .= " WHERE 1 {sql_query}";
        if ($this->langue == $this->default_langue)
            $q .= " AND pt1.translation_id = 0";
			else
			$q .= " AND pt2.translation_id != 0";
        if (isset($cond['p.id_divisions']))
            $q .= " AND p.id_divisions = " . $cond['p.id_divisions'];
        if (isset($cond['p.id_categories']))
            $q .= " AND p.id_categories = " . $cond['p.id_categories'];
        if (isset($cond['p.id_products_lines']))
            $q .= " AND p.id_products_lines = " . $cond['p.id_products_lines'];
        $qs1 = " GROUP BY pt1.id_products_tags";
        $qs1 .= " ORDER BY pt1.sort_order";
        //$qs2 = " GROUP BY pt.id_products_tags";
        //$qs2 .= " ORDER BY pt.sort_order";
        if (!$this->loggedIn)
            $sql1 = str_replace('{sql_query}', ' AND p'.$this->status_table.'.status = 1 AND pt1.id_parent = 0', $q);
        else
            $sql1 = str_replace('{sql_query}', ' AND pt1.id_parent = 0', $q);
          //  exit($sql_start_1 . $sql1 . $qs1);
        $query1  = $this->db->query($sql_start_1 . $sql1 . $qs1);
        $results = $query1->result_array();
        if (!empty($results)) {
            foreach ($results as $k => $v) {
                //$sql2 = str_replace('{sql_query}',' AND pt.id_parent = '.$v['id'],$q);
                //$query2 = $this->db->query($sql_start_2.$sql2.$qs2);
                //$results[$k]['options'] = $query2->result_array();
                $results[$k]['options'] = array();
                $this->db->select($select1);
                if ($this->langue != $this->default_langue)
                    $this->db->join("products_tags" . $this->table_ext . " t1", "t.id_products_tags = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
                $this->db->where(array(
                    //'deleted'=>0,
                    't.id_parent' => $v['id']
                ))->group_by("t.id_products_tags")->order_by('t.sort_order')->get('products_tags' . $this->table_ext . ' t')->result_array();
            }
        }
        //print '<pre>'; print_r($results);exit;
        return $results;
    }
	
	function get_download_center($cond = array(),$like = array(),$limit = '',$offset = 0)
	{
            $queryForPretPrd=""; 
     /* if ($this->langue == $this->default_langue)
       $select = 't.id_product_support AS id, t.id_products AS pid, t.title, pl.title AS language, dt.title AS document_type';
      else
       $select = 't.id_product_support AS id, t.id_products AS pid, t1.title, pl1.title AS language, dt1.title AS document_type';*/
	    if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
	    if ($this->langue == $this->default_langue)
       $select = 't.id_product_support AS id, t.id_products AS pid, p.title';
      else
       $select = 't.id_product_support AS id, t.id_products AS pid, p1.title';
      $this->db->select($select);
	  $this->db->from("product_support" . $this->table_ext . " t");
	  $this->db->join("products p","p.id_products = t.id_products");
	  if(isset($cond['category']) && !empty($cond['category'])) {
//		  $this->db->join("products_categories pc","pc.id_products = t.id_products");
                  $this->db->join("categories c","p.id_categories = c.id_categories");
                  //===================get all product based on parent and sub category=======
                  
                  $likeQuery="";
                  if($like !=null && count($like) > 0 && array_key_exists('p.title', $like)){
                      $likeQuery=" and p.title like '%".$like['p.title']."%'";
                  }
                  
           $queryForPretPrd =" "
                . "UNION ALL "
                ." SELECT `t`.`id_product_support` AS `id`, `t`.`id_products` AS `pid`, `p`.`title` "
                ." FROM `product_support" . $this->table_ext . "` `t` "
                ." JOIN `products` `p` ON `p`.`id_products` = `t`.`id_products`"
                ." JOIN (select id_categories from categories where id_parent in "
                ." (SELECT id_categories FROM `categories` where code='".$cond['category']."' and id_parent=0))A ON "
                . "`p`.`id_categories` = `A`.`id_categories` $likeQuery";
	  }
	  
	 // $this->db->join("product_support t1", "t.id_product_support = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
	  
	  if ($this->langue != $this->default_langue)
	  $this->db->join("products p1", "p.id_products = p1.translation_id AND p1.lang = '" . $this->langue . "'", "LEFT");
	  	  if(isset($cond['category']) && !empty($cond['category'])) {
			  $this->db->where('c.Code',$cond['category']);
			    unset($cond['category']);
		  }
	  if(!empty($cond)) {
		
	 	 $this->db->where($cond);
	 }
	  	  if(!empty($like))
	  $this->db->like($like);
	  if (!$this->loggedIn)
		  $this->db->where($this->status_table.'.status', 1);
	  //if ($this->langue == $this->default_langue)
		 // $this->db->where('t.translation_id', 0);
	 // $this->db->where('t.id_product_support', $id);
         
           //===================get all product based on parent and sub category=======         
          if($limit != '' && $queryForPretPrd=='')
	  $this->db->limit($limit,$offset);
	  
          
          $query   = $this->db->get();
          
          
            if($queryForPretPrd!=''){
                $tempQuery=$this->db->last_query();
                $tempQuery ="select * from (".$tempQuery.$queryForPretPrd.")A  ";
                 if($limit != ''){
                    $tempQuery .=' limit '.$offset. ' , 9';
                 }
                $query=$this->db->query($tempQuery);
            }
          //echo $this->db->last_query();exit;
	 if($limit != '') {
	  $results = $query->result_array();
	  //echo $this->db->last_query();exit;
	  }
	  else
	  $results = $query->num_rows();
	  return $results;
	}
	
    function get_products_menu()
    {
        if ($this->langue == $this->default_langue) {
            $select  = 't.id_divisions AS id, t.title, "division" AS type, t.image';
            $select1 = 't.id_categories AS id, t.title, "category" AS type';
            $select2 = 't.id_categories AS id, t.title, "line" AS type';
        } else {
            $select  = 't.id_divisions AS id, t1.title, "division" AS type, t.image';
            $select1 = 't.id_categories AS id, t1.title, "category" AS type';
            $select2 = 't.id_categories AS id, t1.title, "line" AS type';
        }
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("divisions" . $this->table_ext . " t1", "t.id_divisions = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        if (!$this->loggedIn)
            $this->db->where(''.$this->status_table.'.status', 1);
        if ($this->langue == $this->default_langue)
            $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_divisions");
        $this->db->order_by('t.sort_order');
        $query   = $this->db->get('divisions' . $this->table_ext . ' t', 3);
        $results = $query->result_array();
		
        if (!empty($results)) {
            foreach ($results as $k => $v) {
                $c1 = array(
                    't.id_divisions' => $v['id'],
                    't.id_parent' => 0,
                );
				if ($this->langue == $this->default_langue)
                $c1['t.translation_id'] = 0;
				else
				$c1['t1.translation_id !='] = 0;
                if (!$this->loggedIn)
                    $c1[''.$this->status_table.'.status'] = 1;
           /*     if ($this->langue == $this->default_langue)
                    $c1['t.translation_id'] = 0;*/
                $results[$k]['categories'] = array();
                $this->db->select($select1);
                if ($this->langue != $this->default_langue)
                    $this->db->join("categories" . $this->table_ext . " t1", "t.id_categories = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
                $results[$k]['categories'] = $this->db->where($c1)->group_by("t.id_categories")->order_by('t.sort_order')->get('categories' . $this->table_ext . ' t', 3)->result_array();
				//echo $this->db->last_query();exit;
				
				
                if (!empty($results[$k]['categories'])) {
                    foreach ($results[$k]['categories'] as $k1 => $v1) {
                        $c2 = array(
                            't.id_parent' => $v1['id']
                        );
                       if ($this->langue == $this->default_langue)
                $c2['t.translation_id'] = 0;
				else
				$c2['t1.translation_id !='] = 0;
                        if (!$this->loggedIn)
                            $c2[''.$this->status_table.'.status'] = 1;
                        $results[$k]['categories'][$k1]['lines'] = array();
                        $this->db->select($select2);
                        if ($this->langue != $this->default_langue)
                            $this->db->join("categories" . $this->table_ext . " t1", "t.id_categories = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
                        $this->db->where($c2);
                        $this->db->group_by('t.id_categories');
                        $this->db->order_by('t.sort_order');
                        $results[$k]['categories'][$k1]['lines'] = $this->db->get('categories' . $this->table_ext . ' t', 5)->result_array();
						//echo $this->db->last_query();exit;
                    }
                }
            }
        }
		//print '<pre>'; print_r($results);exit;
        return $results;
    }
	
    function get_division($id)
    {
        $c = array(
            "t.id_divisions" => $id
        );
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $select = 't.id_divisions AS id, t.title, t.description';
        else
            $select = 't.id_divisions AS id, t1.title, t1.description';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("divisions" . $this->table_ext . " t1", "t.id_divisions = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
       // if ($this->langue == $this->default_langue)
           // $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_divisions");
        $query = $this->db->get('divisions' . $this->table_ext . ' t');
        return $query->row_array();
    }
	
	function get_divisions_social()
	{
       /* $c['status'] = 1;
		$c['translation_id'] = 1;
		$select = 'facebook, twitter, instagram, youtube, google_plus, linked_in';
        $this->db->select($select);
        $this->db->where($c);
		$this->db->order_by("sort_order");
        $query = $this->db->get('divisions');
        return $query->result_array();*/
		
		$c = array();
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $select = 't.id_divisions AS id, t.title, t.facebook, t.twitter, t.instagram, t.youtube, t.google_plus, t.linked_in, t.apple_store, t.google_play_store';
        else
            $select = 't.id_divisions AS id, t1.title, t.facebook, t.twitter, t.instagram, t.youtube, t.google_plus, t.linked_in, t.apple_store, t.google_play_store';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("divisions" . $this->table_ext . " t1", "t.id_divisions = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        //if ($this->langue == $this->default_langue)
           // $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_divisions");
		  $this->db->order_by("t.sort_order");
        $query = $this->db->get('divisions' . $this->table_ext . ' t');
        return $query->result_array();
		
	}
	
	function get_tag($id)
    {
        $c = array(
            "t.id_products_tags" => $id
        );
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if ($this->langue == $this->default_langue)
            $select = 't.id_products_tags AS id, t.title, t.description';
        else
            $select = 't.id_products_tags AS id, t1.title, t1.description';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("products_tags" . $this->table_ext . " t1", "t.id_products_tags = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
      //  if ($this->langue == $this->default_langue)
           // $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_products_tags");
        $query = $this->db->get('products_tags' . $this->table_ext . ' t');
        return $query->row_array();
    }
	
    function get_category($id)
    {
        $c = array(
            "t.id_categories" => $id
        );
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if ($this->langue == $this->default_langue)
            $select = 't.id_categories AS id, t.title, t.description';
        else
            $select = 't.id_categories AS id, t1.title, t1.description';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("categories" . $this->table_ext . " t1", "t.id_categories = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        //if ($this->langue == $this->default_langue)
           // $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_categories");
        $query = $this->db->get('categories' . $this->table_ext . ' t');
        return $query->row_array();
    }
	
    function get_product_line($id)
    {
        $c = array(
            "t.id_products_lines" => $id
        );
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $select = 't.id_products_lines AS id, t.title';
        else
            $select = 't.id_products_lines AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("products_lines" . $this->table_ext . " t1", "t.id_products_lines = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        //if ($this->langue == $this->default_langue)
          //  $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_products_lines");
        $query = $this->db->get('products_lines' . $this->table_ext . ' t');
        return $query->row_array();
    }
	
    function get_all_divisions()
    {
        $c = array(
          //  't.translation_id' => 0
        );
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $select = 't.id_divisions AS id, t.title, t.image';
        else
            $select = 't.id_divisions AS id, t1.title, t.image';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("divisions" . $this->table_ext . " t1", "t.id_divisions = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
       // if ($this->langue == $this->default_langue)
           // $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_divisions");
             $this->db->order_by("t.sort_order");
        $query = $this->db->get('divisions' . $this->table_ext . ' t');
        return $query->result_array();
    }
	
    function get_categories_by_division($id)
    {
        $c = array(
            't.id_divisions' => $id,
            't.id_parent' => 0,
            //'t.translation_id' => 0
        );
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $select = 't.id_categories AS id, t.title';
        else
            $select = 't.id_categories AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("categories" . $this->table_ext . " t1", "t.id_categories = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
       // if ($this->langue == $this->default_langue)
           // $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_categories");
        $query = $this->db->get('categories' . $this->table_ext . ' t');
		//echo $this->db->last_query();exit;
        return $query->result_array();
    }
	
	function get_categories_page($c = array(),$limit = '',$offset = 0)
    {
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $select = 't.id_categories AS id, t.title, t.image_small';
        else
            $select = 't.id_categories AS id, t1.title, t.image_small';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("categories" . $this->table_ext . " t1", "t.id_categories = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
       // if ($this->langue == $this->default_langue)
          //  $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_categories");
		$this->db->order_by("t.sort_order");
		if($limit != '')
		$this->db->limit($limit,$offset);
        $query = $this->db->get('categories' . $this->table_ext . ' t');
		//echo $this->db->last_query();exit;
		if($limit != '')
        return $query->result_array();
		else
		return $query->num_rows();
    }
	
    function get_categories_by_category($id)
    {
        $c = array(
            't.id_parent' => $id,
          //  't.translation_id' => 0
        );
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $select = 't.id_categories AS id, t.title';
        else
            $select = 't.id_categories AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("categories" . $this->table_ext . " t1", "t.id_categories = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        //if ($this->langue == $this->default_langue)
          //  $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_categories");
        $query = $this->db->get('categories' . $this->table_ext . ' t');
        return $query->result_array();
    }
	
    function get_product_lines_by_category($id)
    {
        $c = array(
            't.id_categories' => $id,
            //'t.translation_id' => 0
        );
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $select = 't.id_products_lines AS id, t.title';
        else
            $select = 't.id_products_lines AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("products_lines" . $this->table_ext . " t1", "t.id_products_lines = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
       // if ($this->langue == $this->default_langue)
            //$this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_products_lines");
        $query = $this->db->get('products_lines' . $this->table_ext . ' t');
        return $query->result_array();
    }
	
    ///////////////////////////////////////////////////////////////////////////////////////////
    function get_products_history()
    {
        $c = array(
          //  "t.translation_id" => 0
        );
		 if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $select = 't.id_history_of_products AS id, t.title, t.year, t.image';
        else
            $select = 't.id_history_of_products AS id, t1.title, t.year, t.image';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("history_of_products" . $this->table_ext . " t1", "t.id_history_of_products = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
       // if ($this->langue == $this->default_langue)
           // $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_history_of_products");
        $query = $this->db->get('history_of_products' . $this->table_ext . ' t');
        return $query->result_array();
    }
	
    function get_history_max_year()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c = array(
                'translation_id' => 0
            );
        if (!$this->loggedIn)
            $c['status'] = 1;
        $r        = $this->db->select("MAX(year) AS max_year")->where($c)->get('history_of_products')->row_array();
        $max_year = 0;
        if (!empty($r))
            $max_year = $r['max_year'];
        return $max_year;
    }
	
    function get_history_min_year()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c = array(
                'translation_id' => 0
            );
        if (!$this->loggedIn)
            $c['status'] = 1;
        $r        = $this->db->select("MIN(year) AS min_year")->where($c)->get('history_of_products')->row_array();
        $min_year = 0;
        if (!empty($r))
            $min_year = $r['min_year'];
        return $min_year;
    }
	
    function get_history_timeline($min_y, $max_y)
    {
        $c = array(
            'year >=' => $min_y,
            'year <' => $max_y
        );
     //  if ($this->langue == $this->default_langue)
         $c['translation_id'] = 0;
			//else
			// $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c['status'] = 1;
        $results = $this->db->select('id_history_of_products AS id, year')->where($c)->group_by('year')->order_by('year')->get('history_of_products')->result_array();
        if (!empty($results)) {
            foreach ($results as $k => $res) {
                $c1 = array(
                    't.year' => $res['year']
                );
                if ($this->langue == $this->default_langue)
                    $c1['t.translation_id'] = 0;
					else
			 $c1['t1.translation_id !='] = 0;
                if (!$this->loggedIn)
                    $c1[''.$this->status_table.'.status'] = 1;
                //$results[$k]['items'] = $this->db->select('id_history_timeline AS id, title')->where($c1)->order_by('sort_order')->get('history_timeline')->result_array();
                if ($this->langue == $this->default_langue)
                    $select = 't.id_history_of_products AS id, t.description AS title';
                else
                    $select = 't.id_history_of_products AS id, t1.description AS title';
                $this->db->select($select);
                if ($this->langue != $this->default_langue)
                    $this->db->join("history_of_products" . $this->table_ext . " t1", "t.id_history_of_products = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
                $this->db->where($c1);
                $this->db->group_by("t.id_history_of_products");
                $query                = $this->db->get('history_of_products' . $this->table_ext . ' t');
				//echo $this->db->last_query();exit;
                $results[$k]['items'] = $query->result_array();
            }
        }
        return $results;
    }
	
    ///////////////////////////////////////////////////////////////////////////////////////
    function get_home_page_boxes()
    {
        $c = array(
            't.promote_to_home_page' => 1
        );
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $select = 't.id_divisions AS id, t.title, t.promote_to_home_page';
        else
            $select = 't.id_divisions AS id, t1.title, t.promote_to_home_page';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("divisions" . $this->table_ext . " t1", "t.id_divisions = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
      //  if ($this->langue == $this->default_langue)
            //$this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_divisions");
        $this->db->order_by('t.sort_order');
        $query     = $this->db->get('divisions' . $this->table_ext . ' t');
        $divisions = $query->result_array();
        if (!empty($divisions)) {
            foreach ($divisions as $k => $v) {
                $c1 = array(
                    't.promote_to_home_page !=' => 0,
                    't.id_parent' => 0,
                    't.id_divisions' => $v['id']
                );
                if ($this->langue == $this->default_langue)
                    $c1['t.translation_id'] = 0;
					else
			 $c1['t1.translation_id !='] = 0;
                if (!$this->loggedIn)
                    $c1[''.$this->status_table.'.status'] = 1;
                /*$divisions[$k]['categories'] = $this->db->select('id_categories AS id, title, promote_to_home_page, image_wide, image_small')->where($c1)->where('id_divisions',$v['id'])->order_by('sort_order')->get('categories')->result_array();*/
                if ($this->langue == $this->default_langue)
                    $select = 't.id_categories AS id, t.title, t.promote_to_home_page, t.image_wide, t.image_small';
                else
                    $select = 't.id_categories AS id, t1.title, t.promote_to_home_page, t.image_wide, t.image_small';
                $this->db->select($select);
                if ($this->langue != $this->default_langue)
                    $this->db->join("categories" . $this->table_ext . " t1", "t.id_categories = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
                $this->db->where($c1);
                $this->db->group_by("t.id_categories");
                $this->db->order_by('t.sort_order');
                $query                       = $this->db->get('categories' . $this->table_ext . ' t');
                $divisions[$k]['categories'] = $query->result_array();
            }
        }
        return $divisions;
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    function get_all_envcsr()
    {
        $c = array();
			if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_environmental_and_csr AS id, title')->where($c)->order_by('sort_order')->get('environmental_and_csr')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_environmental_and_csr AS id, t.title';
        else
            $select = 't.id_environmental_and_csr AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("environmental_and_csr" . $this->table_ext . " t1", "t.id_environmental_and_csr = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
       // if ($this->langue == $this->default_langue)
           // $this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_environmental_and_csr");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('environmental_and_csr' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function get_one_envcsr($id)
    {
        $c = array(
            't.id_environmental_and_csr' => $id
        );
		if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_environmental_and_csr AS id, title, description')->where($c)->get('environmental_and_csr')->row_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_environmental_and_csr AS id, t.title, t.description';
        else
            $select = 't.id_environmental_and_csr AS id, t1.title, t1.description';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("environmental_and_csr" . $this->table_ext . " t1", "t.id_environmental_and_csr = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        //if ($this->langue == $this->default_langue)
            //$this->db->where('t.translation_id', 0);
        $this->db->group_by("t.id_environmental_and_csr");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('environmental_and_csr' . $this->table_ext . ' t');
        return $query->row_array();
    }
    function get_first_envcsr()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_environmental_and_csr AS id, title, description')->where($c)->order_by('sort_order')->get('environmental_and_csr')->row_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_environmental_and_csr AS id, t.title, t.description';
        else
            $select = 't.id_environmental_and_csr AS id, t1.title, t1.description';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("environmental_and_csr" . $this->table_ext . " t1", "t.id_environmental_and_csr = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_environmental_and_csr");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('environmental_and_csr' . $this->table_ext . ' t');
        return $query->row_array();
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    function get_global_policy()
    {
        $c = array();
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        //return $this->db->select('id_global_policy AS id, title, description')->where($c)->order_by('sort_order')->get('global_policy')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_global_policy AS id, t.title, t.description';
        else
            $select = 't.id_global_policy AS id, t1.title, t1.description';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("global_policy" . $this->table_ext . " t1", "t.id_global_policy = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_global_policy");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('global_policy' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function get_site_policy()
    {
        $c = array();
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        //return $this->db->select('id_site_policy AS id, title, description')->where($c)->order_by('sort_order')->get('site_policy')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_site_policy AS id, t.title, t.description';
        else
            $select = 't.id_site_policy AS id, t1.title, t1.description';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("site_policy" . $this->table_ext . " t1", "t.id_site_policy = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_site_policy");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('site_policy' . $this->table_ext . ' t');
        return $query->result_array();
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    function get_countries($with_centers = FALSE)
    {
        $c = array();
        if (!$this->loggedIn)
            $c['t.status'] = 1;
       // if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
		//	else
			// $c['t1.translation_id !='] = 0;
        if ($this->langue == $this->default_langue)
            $select = 't.id_countries AS id, t.title';
        else
            $select = 't.id_countries AS id, t.title';
        $this->db->select($select);
        if ($with_centers) {
            $this->db->join('service_centers sc', 'sc.id_countries = t.id_countries');
        }
        if ($this->langue != $this->default_langue)
            $this->db->join("countries" . $this->table_ext . " t1", "t.id_countries = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_countries");
        $this->db->order_by('t.title');
        $query = $this->db->get('countries' . $this->table_ext . ' t');
	//echo $this->db->last_query();exit;
        return $query->result_array();
    }
    function get_states_by_country($cid, $with_centers = FALSE)
    {
        $c = array();
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if ($this->langue == $this->default_langue)
            $select = 't.id_states AS id, t.title';
        else
            $select = 't.id_states AS id, t.title';
        $this->db->select($select);
        if ($with_centers) {
            $this->db->join('service_centers' . $this->table_ext . ' sc', 'sc.id_states = t.id_states');
        }
       // if ($this->langue != $this->default_langue)
          //  $this->db->join("states" . $this->table_ext . " t1", "t.id_states = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->where('t.id_countries', $cid);
        $this->db->group_by("t.id_states");
        $this->db->order_by('t.title');
        $query = $this->db->get('states' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function get_products_by_state($sid)
    {
        $c = array(
            'sc.id_states' => $sid
        );
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c['sc.status'] = 1;
        //return $this->db->select('scp.id_service_centers_products AS id, scp.title')->join()->where($c)->group_by('scp.id_service_centers_products')->get('service_centers_products scp')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_service_centers_products AS id, t.title';
        else
            $select = 't.id_service_centers_products AS id, t.title';
        $this->db->select($select);
        $this->db->join('service_centers' . $this->table_ext . ' sc', 'sc.id_service_centers = t.id_service_centers');
        if ($this->langue != $this->default_langue)
            $this->db->join("service_centers_products" . $this->table_ext . " t1", "t.id_service_centers_products = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_service_centers_products");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('service_centers_products' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function search_service_centers($country = '', $state = '', $product = '')
    {
        if ($this->langue == $this->default_langue)
            $select = 't.id_service_centers AS id, t.title, c.title AS country_name, s.title AS state_name, t.address, t.google_map_coordinates AS map_co, scp.title AS product_name';
        else
            $select = 't.id_service_centers AS id, t1.title, c1.title AS country_name, s1.title AS state_name, t1.address, t.google_map_coordinates AS map_co, scp1.title AS product_name';
        $q = 'SELECT ' . $select . ' FROM service_centers' . $this->table_ext . ' t';
        $q .= ' LEFT JOIN service_centers_products' . $this->table_ext . ' scp ON scp.id_service_centers = sc.id_service_centers';
        $q .= ' LEFT JOIN countries' . $this->table_ext . ' c ON c.id_countries = sc.id_countries';
        $q .= ' LEFT JOIN states' . $this->table_ext . ' s ON s.id_states = sc.id_states';
        if ($this->langue == $this->default_langue) {
            $q .= ' LEFT JOIN service_centers' . $this->table_ext . ' t1 ON t.id_service_centers = t1.translation_id AND t1.lang = "' . $this->langue . '"';

            $q .= ' LEFT JOIN service_centers_products' . $this->table_ext . ' scp1 ON scp.id_service_centers_products = scp1.translation_id AND scp1.lang = "' . $this->langue . '"';
            $q .= ' LEFT JOIN countries' . $this->table_ext . ' c1 ON c.id_countries = c1.translation_id AND c1.lang = "' . $this->langue . '"';
            $q .= ' LEFT JOIN states' . $this->table_ext . ' s1 ON s.id_states = s1.translation_id AND s1.lang = "' . $this->langue . '"';
        }
        $q .= ' WHERE 1';
        if ($this->langue == $this->default_langue)
            $q .= ' AND t.translation_id = 0';
			else
			 $q .= ' AND t1.translation_id != 0';
        if (!$this->loggedIn)
            $q .= ' AND '.$this->status_table.'.status = 1';
			
			$array_bind = array();
        if ($product != '') {
            $q .= ' AND scp.id_service_centers_products = ?';
			array_push($array_bind,$product);
		}
        if ($state != '') {
            $q .= ' AND t.id_states = ?';
			array_push($array_bind,$state);
		}
        if ($country != '') {
            $q .= ' AND t.id_countries = ?';
			array_push($array_bind,$country);
		}
        $q .= ' GROUP BY scp.id_service_centers_products';
        $q .= ' ORDER BY scp.id_service_centers_products';
        //echo $q;exit;
        $query   = $this->db->query($q,$array_bind);
        $results = $query->result_array();
        //print '<pre>'; print_r($results); exit;
        return $results;
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    function get_feedback_types()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_feedback_types AS id, title')->where($c)->get('feedback_types')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_feedback_types AS id, t.title';
        else
            $select = 't.id_feedback_types AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("feedback_types" . $this->table_ext . " t1", "t.id_feedback_types = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_feedback_types");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('feedback_types' . $this->table_ext . ' t');
        return $query->result_array();
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    function get_careers_divisions()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_careers_divisions AS id, title')->where($c)->get('careers_divisions')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_careers_divisions AS id, t.title';
        else
            $select = 't.id_careers_divisions AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("careers_divisions" . $this->table_ext . " t1", "t.id_careers_divisions = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_careers_divisions");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('careers_divisions' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function get_departments_by_division($div)
    {
        $c = array(
            't.id_careers_divisions' => $div
        );
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        if ($this->langue == $this->default_langue)
            $select = 't.id_departments AS id, t.title';
        else
            $select = 't.id_departments AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("departments" . $this->table_ext . " t1", "t.id_departments = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_departments");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('departments' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function get_regions()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_regions AS id, title')->where($c)->get('regions')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_regions AS id, t.title';
        else
            $select = 't.id_regions AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("regions" . $this->table_ext . " t1", "t.id_regions = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_regions");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('regions' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function get_experience_levels()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_experience_levels AS id, title')->where($c)->get('experience_levels')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_experience_levels AS id, t.title';
        else
            $select = 't.id_experience_levels AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("experience_levels" . $this->table_ext . " t1", "t.id_experience_levels = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_experience_levels");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('experience_levels' . $this->table_ext . ' t');
        return $query->result_array();
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    function get_product_keys()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_product_keys AS id, title')->where($c)->get('product_keys')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_product_keys AS id, t.title';
        else
            $select = 't.id_product_keys AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("product_keys" . $this->table_ext . " t1", "t.id_product_keys = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_product_keys");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('product_keys' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function get_products_languages()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_products_languages AS id, title')->where($c)->get('products_languages')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_products_languages AS id, t.title';
        else
            $select = 't.id_products_languages AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("products_languages" . $this->table_ext . " t1", "t.id_products_languages = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_products_languages");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('products_languages' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function get_document_types()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_document_types AS id, title')->where($c)->get('document_types')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_document_types AS id, t.title';
        else
            $select = 't.id_document_types AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("document_types" . $this->table_ext . " t1", "t.id_document_types = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_document_types");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('document_types' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function get_emulations()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_emulations AS id, title')->where($c)->get('emulations')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_emulations AS id, t.title';
        else
            $select = 't.id_emulations AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("emulations" . $this->table_ext . " t1", "t.id_emulations = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_emulations");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('emulations' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function get_operating_systems()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_operating_systems AS id, title')->where($c)->get('operating_systems')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_operating_systems AS id, t.title';
        else
            $select = 't.id_operating_systems AS id, t1.title';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("operating_systems" . $this->table_ext . " t1", "t.id_operating_systems = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_operating_systems");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('operating_systems' . $this->table_ext . ' t');
        return $query->result_array();
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    function get_corporate_profile_categories()
    {
        $c = array();
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_corporate_profile_categories AS id, title')->where($c)->get('corporate_profile_categories')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_corporate_profile_categories AS id, t.title, t.external_link';
        else
            $select = 't.id_corporate_profile_categories AS id, t1.title, t.external_link';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("corporate_profile_categories" . $this->table_ext . " t1", "t.id_corporate_profile_categories = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_corporate_profile_categories");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('corporate_profile_categories' . $this->table_ext . ' t');
        return $query->result_array();
    }
    function get_corporate_profile_one_category($id)
    {
        $c = array(
            't.id_corporate_profile_categories' => $id
        );
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_corporate_profile_categories AS id, title, google_map_coordinates, image')->where($c)->order_by("sort_order")->get('corporate_profile_categories')->row_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_corporate_profile_categories AS id, t.title, t.google_map_coordinates, t.image';
        else
            $select = 't.id_corporate_profile_categories AS id, t1.title, t.google_map_coordinates, t.image';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("corporate_profile_categories" . $this->table_ext . " t1", "t.id_corporate_profile_categories = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_corporate_profile_categories");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('corporate_profile_categories' . $this->table_ext . ' t');
        return $query->row_array();
    }
    function corporate_profile_items($cid)
    {
        $c = array(
            't.id_corporate_profile_categories' => $cid
        );
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_corporate_profile AS id, title, description')->where($c)->get('corporate_profile')->result_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_corporate_profile AS id, t.title, t.description';
        else
            $select = 't.id_corporate_profile AS id, t1.title, t1.description';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("corporate_profile" . $this->table_ext . " t1", "t.id_corporate_profile = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_corporate_profile");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('corporate_profile' . $this->table_ext . ' t');
        return $query->result_array();
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    function get_office($id)
    {
        $c = array(
            't.id_branches' => $id
        );
        if ($this->langue == $this->default_langue)
            $c['t.translation_id'] = 0;
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $c[''.$this->status_table.'.status'] = 1;
        //return $this->db->select('id_branches AS id, title, google_map_coordinates, sub_title, image, address, phone, email, fax')->where($c)->get('branches')->row_array();
        if ($this->langue == $this->default_langue)
            $select = 't.id_branches AS id, t.title, t.google_map_coordinates, t.sub_title, t.image, t.address, t.phone, t.email, t.fax';
        else
            $select = 't.id_branches AS id, t1.title, t.google_map_coordinates, t1.sub_title, t.image, t1.address, t.phone, t.email, t.fax';
        $this->db->select($select);
        if ($this->langue != $this->default_langue)
            $this->db->join("branches" . $this->table_ext . " t1", "t.id_branches = t1.translation_id AND t1.lang = '" . $this->langue . "'", "LEFT");
        $this->db->where($c);
        $this->db->group_by("t.id_branches");
        $this->db->order_by('t.sort_order');
        $query = $this->db->get('branches' . $this->table_ext . ' t');
        return $query->row_array();
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    function get_support_categories()
    {
        $sql = " ";
        if ($this->langue == $this->default_langue)
            $sql = " AND t.translation_id = 0";
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $sql = ' AND '.$this->status_table.'.status = 1';
        if ($this->langue == $this->default_langue)
            $select = 't.id_categories AS id, t.title';
        else
            $select = 't.id_categories AS id, t1.title';
        $q = "SELECT " . $select . " FROM categories" . $this->table_ext . " t";
        if ($this->langue != $this->default_langue)
            $q .= " LEFT JOIN categories" . $this->table_ext . " t1 ON t.id_categories = t1.translation_id AND t1.lang = '" . $this->langue . "'";
        //$q .= " JOIN products_categories pc ON pc.id_categories = c.id_categories";
        //$q .= " JOIN products p ON p.id_products = pc.id_products";
        //$q .= " JOIN product_support ps ON ps.id_products = p.id_products";
        $q .= " WHERE t.id_categories NOT IN(SELECT id_parent FROM categories) ";
        $q .= " OR (t.id_categories NOT IN(SELECT id_parent FROM categories) AND t.id_parent = 0) ";
        $q .= $sql;
        $q .= " GROUP BY t.id_categories";
        $q .= " ORDER BY t.title";
        $query   = $this->db->query($q);
        $results = $query->result_array();
        return $results;
    }
    function filter_support_categories($data = array())
    {
        $sql = "";
        if ($this->langue == $this->default_langue)
            $sql = " AND t.translation_id = 0";
			else
			 $c['t1.translation_id !='] = 0;
        if (!$this->loggedIn)
            $sql = ' AND '.$this->status_table.'.status = 1 AND '.$this->pstatus_table.'.status = 1';
        if ($this->langue == $this->default_langue)
            $select = 't.id_categories AS id, t.title,t.Code,t.id_parent';
        else
            $select = 't.id_categories AS id, t1.title,t1.Code';
        $q = "SELECT " . $select . " FROM categories" . $this->table_ext . " t";
        //$q .= " JOIN products_categories pc ON pc.id_categories = c.id_categories";
        $q .= " JOIN products" . $this->table_ext . " p ON p.id_categories = t.id_categories";
        $q .= " JOIN product_support" . $this->table_ext . " ps ON ps.id_products = p.id_products";
        $q .= " JOIN products_languages" . $this->table_ext . " pl ON ps.id_products_languages = pl.id_products_languages";
        $q .= " JOIN document_types" . $this->table_ext . " dt ON ps.id_document_types = dt.id_document_types";
        if ($this->langue != $this->default_langue)
            $q .= " LEFT JOIN categories" . $this->table_ext . " t1 ON t.id_categories = t1.translation_id AND t1.lang = '" . $this->langue . "'";
        $q .= " WHERE 1 ";
        if (isset($data['modal']) && $data['modal'] != "")
            $q .= " AND p.title LIKE '%" . $data['modal'] . "%'";
        if (isset($data['category']) && $data['category'] != 0) {
            //$ids = $this->fct->get_sub_ids("categories",$data['category'],TRUE);
            //if(!empty($ids))
            //$q .= " AND pc.id_categories IN(".implode(",",$ids).")";
            $q .= " AND p.id_categories = " . $data['category'];
        }
        if (isset($data['language']) && $data['language'] != "")
            $q .= " AND ps.id_products_languages = " . $data['language'];
        if (isset($data['d']) && !empty($data['d']))
            $q .= " AND ps.id_document_types IN(" . implode(",", $data['d']) . ")";
        if (isset($data['opsystem']) && $data['opsystem'] != "" && (in_array(4, $data['d']) || in_array(7, $data['d'])))
            $q .= " AND ps.id_operating_systems = " . $data['opsystem'];
        if (isset($data['emulation']) && $data['emulation'] != "" && (in_array(4, $data['d'])))
            $q .= " AND ps.id_emulations = " . $data['emulation'];
        $q .= $sql;
       
        
        
        //===============get parent category with sub category==========
        $finalQuery ="select id, title,Code ,id_parent from "
                . " (Select id_categories AS id, title,Code ,id_parent From categories" . $this->table_ext 
                . " Where id_parent=0 and id_categories in ("
                . "select B.id_parent from( "
                . $q.")B)"
                . "Union All "
                . $q .")A group by id";
        $query   = $this->db->query($finalQuery);
		//echo $this->db->last_query();exit;
        $results = $query->result_array();
        return $results;
    }
    function filter_support_products($data, $limit = "", $offset = 0)
    {
        $sql1 = "";
        $sql2 = "";
        if (!$this->loggedIn) {
            $sql1 = ' AND '.$this->pstatus_table.'.status = 1';
            $sql2 = ' AND ps.status = 1';
        }
        if ($this->langue == $this->default_langue)
            $select = 'ps.id_product_support AS id, p.id_products AS pid, p.title, pl.title AS language, dt.title AS document_type';
        else
            $select = 'ps.id_product_support AS id, p.id_products AS pid, p1.title, pl1.title AS language, dt1.title AS document_type';
        $q = "SELECT " . $select . " FROM product_support" . $this->table_ext . " ps";
        $q .= " JOIN products" . $this->table_ext . " p ON p.id_products = ps.id_products";
        $q .= " JOIN products_languages" . $this->table_ext . " pl ON ps.id_products_languages = pl.id_products_languages";
        $q .= " JOIN document_types" . $this->table_ext . " dt ON ps.id_document_types = dt.id_document_types";
        if ($this->langue != $this->default_langue) {
            $q .= " LEFT JOIN products" . $this->table_ext . " p1 ON p.id_products = p1.translation_id AND p1.lang = '" . $this->langue . "'";
            $q .= " LEFT JOIN products_languages" . $this->table_ext . " pl1 ON pl.id_products_languages = pl1.translation_id AND pl1.lang = '" . $this->langue . "'";
            $q .= " LEFT JOIN document_types" . $this->table_ext . " dt1 ON dt.id_document_types = dt1.translation_id AND dt1.lang = '" . $this->langue . "'";
        }
        //if(isset($data['category']) && $data['category'] != 0)
        //$q .= " JOIN products_categories pc ON pc.id_categories = c.id_categories";
        $q .= " WHERE 1";
        if ($this->langue == $this->default_langue)
            $q .= " AND p.translation_id = 0";
			else
			 $q .= " AND p1.translation_id != 0";
        if (isset($data['modal']) && $data['modal'] != "")
            $q .= " AND p.title LIKE '%" . $data['modal'] . "%'";
        if (isset($data['category']) && $data['category'] != 0) {
            $q .= " AND p.id_categories = " . $data['category'];
        }
		if (isset($data['product']) && $data['product'] != 0) {
            $q .= " AND ps.module = 'products' AND ps.record = ".$data['product'];
        }
        if (isset($data['language']) && $data['language'] != "")
            $q .= " AND ps.id_products_languages = " . $data['language'];
       // if (isset($data['d']) && !empty($data['d']))
           // $q .= " AND ps.id_document_types IN(" . implode(",", $data['d']) . ")";
        if (isset($data['opsystem']) && $data['opsystem'] != "" && (in_array(4, $data['d']) || in_array(7, $data['d'])))
            $q .= " AND ps.id_operating_systems = " . $data['opsystem'];
        if (isset($data['emulation']) && $data['emulation'] != "" && (in_array(4, $data['d'])))
            $q .= " AND ps.id_emulations = " . $data['emulation'];
        $q .= $sql1;
        $q2 = " GROUP BY ps.id_product_support";
        $q2 .= " ORDER BY p.title, language, document_type";
        $q3 = "";
        if ($limit != "") {
            $q3 = " LIMIT " . $limit . " OFFSET " . $offset;
        }
        //	echo $q . $q2 . $q3;exit;
	
        $query = $this->db->query($q . $q2 . $q3);
        if ($limit != "") {
            $results = $query->result_array();
			//echo $this->db->last_query();exit;
        } else
            $results = $query->num_rows();
        return $results;
    }
    function get_compliance_files($cond = array(),$like = array(),$limit = '',$offset = 0)
	{
            $select = 'id_compliance,title,description as content';
//         if (!$this->loggedIn)
            $this->db->where('status', 1);
        if ($this->langue == $this->default_langue)
            $this->db->where('translation_id', 0);
				else
				 $this->db->where('translation_id !=', 0);
      $this->db->select($select);
	  $this->db->from("compliance_review");
	 
	  if(!empty($cond)) {
		
	 	 $this->db->where($cond);
	 }
	  	  if(!empty($like))
	  $this->db->like($like);
	  if($limit != '')
	  $this->db->limit($limit,$offset);
          
	  $query   = $this->db->get();
//          echo $this->db->last_query();exit;
	 if($limit != '') {
	  $results = $query->result_array();
          
         }
	  else{
            $results = $query->num_rows();
            
          }
	  return $results;
	
}
    function get_download_files($cond = array(),$like = array(),$limit = '',$offset = 0)
	{
            $select = 'fileId AS id,originalFileName,uploadedFileName,name';
      $this->db->select($select);
	  $this->db->from("upload_files");
	 
	  if(!empty($cond)) {
		
	 	 $this->db->where($cond);
	 }
	  	  if(!empty($like))
	  $this->db->like($like);
	  if($limit != '')
	  $this->db->limit($limit,$offset);
          
	  $query   = $this->db->get();
//          echo $this->db->last_query();exit;
	 if($limit != '') {
	  $results = $query->result_array();
          
         }
	  else{
            $results = $query->num_rows();
            
          }
	  return $results;
	}
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
}