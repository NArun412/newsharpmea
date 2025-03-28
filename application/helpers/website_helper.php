<?php
if ( ! function_exists('get_seo'))
{
	function get_seo($id = 0,$module = '',$record = 0,$lang = "")
	{
		$CI =& get_instance();
		$empty = array(
			"id"=>"",
			"meta_title"=>"",
			"meta_description"=>"",
			"meta_keywords"=>"",
			"url_route"=>""
		);
		
		if($lang == "")
		$lang = $CI->lang->lang();
			
		$res = $empty;
		if($id == 0 && $module == '' && $record == 0)
		return $res;
		else {
			if($id == 0) {
				$cond = array(
					't.module'=>$module,
					't.record'=>$record,
					't.lang'=>$lang,
					//'t.status'=>1
				);
			}
			else {
				$cond = array(
					't.id_seo'=>$id,
					//'t.status'=>1
				);
			}
			//if($module == 'categories') { print '<pre>';print_r($cond); exit; }
			
			
			$d_lang = default_lang();
			if($lang != $d_lang && $id != 0)
			$select = 't.id_seo, t1.meta_title, t1.meta_description, t1.meta_keywords, t1.url_route';
			else
			$select = 't.id_seo, t.meta_title, t.meta_description, t.meta_keywords, t.url_route';
			
			$CI->db->select($select);
			if($lang != $d_lang && $id != 0)
			$CI->db->join("seo t1","t.id_seo = t1.translation_id AND t1.lang = '".$lang."'");
			$CI->db->where($cond);	
			$query=$CI->db->get('seo t');
			//echo $CI->db->last_query();exit;
			if($query->num_rows() > 0)
			$res = $query->row_array();
		}
		return $res;
	}
}

if ( ! function_exists('img_url'))
{
	function img_url($abs = '')
	{
		if($abs == 'absolute')
		return get_instance()->config->item("img_path_absolute");
		else
		return get_instance()->config->item("img_path");
	}
}

if ( ! function_exists('dynamic_img_url'))
{
	function dynamic_img_url($abs = '')
	{
		if($abs == 'absolute')
		return get_instance()->config->item("dynamic_img_path_absolute");
		else
		return get_instance()->config->item("dynamic_img_path");
	}
}

if ( ! function_exists('assets_url'))
{
	function assets_url($abs = '')
	{
		if($abs == 'absolute')
		return get_instance()->config->item("assets_path_absolute");
		else
		return get_instance()->config->item("assets_path");
	}
}

if ( ! function_exists('dateformat'))
{
	function dateformat($date,$format = 'M d,Y')
	{
		return date($format,strtotime($date));
	}
}



if ( ! function_exists('banner_pages'))
{
	function banner_pages($page = '')
	{
		$arr = array(
			0=>"- select -",
			1=>"Home Page",
			2=>"About Us - History",
			3=>"About Us - Profile",
			8=>"Latest News",
			9=>"Contact Us",
			10=>"Careers - Form",
			12=>"Careers - Why Work Here",
			18=>"Careers - Culter At Sharp",
			11=>"Philosophy",
			13=>"Global Basic History",
			14=>"MD Message",
			15=>"Site Policy",
			16=>"Sitemap",
			17=>"Support"
		);
		if($page == '') return $arr;
		else return $arr[$page];
	}
}
if ( ! function_exists('get_banner_page_records'))
{
function get_banner_page_records($elem)
{
	$CI = get_instance();
	
	$table = '';
	$field = '';
	switch($elem) {
		case 4:
			$table = 'products';
			$field = 'title';
			$order = 'title';
			$where = '1 = 1';
			break;
		case 5:
			$table = 'divisions';
			$field = 'title';
			$order = 'title';
			$where = '1 = 1';
			break;
		case 6:
			$table = 'categories';
			$field = 'title';
			$order = 'title';
			$where = '1 = 1';
			break;
		case 7:
			$table = 'products_tags';
			$field = 'title';
			$order = 'title';
			$where = 'id_parent != 0';
			break;
		case 8:
			$table = 'news';
			$field = 'title';
			$order = 'title';
			$where = '1 = 1';
			break;
	}
	if($table != '' && $field != '') {
		$records = $CI->db->select('id_'.$table.' AS id, '.$field.' AS title')->where($where)->order_by($order)->get($table)->result_array();
		return $records;
	}
	return array();
}
function get_banner_page_records_html($arr,$selected = 0) {
	$html = '<option value="">- select record -</option>';
	if(!empty($arr)) {
		foreach($arr as $record) {
			$cl = '';
			if($record['id'] == $selected) $cl = 'selected="selected"';
			$html .= '<option value="'.$record['id'].'" '.$cl.'>'.$record['title'].'</option>';
		}
	}
	return $html;
}
function render_banner_page_records_html($elem,$selected = 0) {
	$arr = get_banner_page_records($elem);
	$html = get_banner_page_records_html($arr,$selected = 0);
	return $html;
}
}
if ( ! function_exists('history_years'))
{
	function history_years()
	{
		return range(1912,date("Y"));
	
	}
}

if ( ! function_exists('get_division_theme'))
{
	function get_division_theme($id)
	{
		$ids = array();
		$theme = 'default';
		$arr = get_instance()->db->select('theme')->where("id_divisions",$id)->get("divisions")->row_array();
		if(!empty($arr) && isset($arr['theme']) && $arr['theme'] == 2)
		$theme = 'blue';
		return $theme;
	}
}

if ( ! function_exists('product_tags_ids'))
{
	function product_tags_ids($id)
	{
		$ids = array();
		$ids_arr = get_instance()->db->where("id_products",$id)->get("products_tags_rel")->result_array();
		//echo get_instance()->db->last_query();exit;
		if(!empty($ids_arr)) {
			foreach($ids_arr as $id) {
				array_push($ids,$id['id_products_tags']);
			}
		}
		//print_r($ids);exit;
		return $ids;
	}
}

if ( ! function_exists('today'))
{
	function today()
	{
		return date('Y-m-d');
	}
}

if ( ! function_exists('get_cell')){
function get_cell($table,$cell,$field_id,$id)
{
	$CI =& get_instance();
	if ($CI->db->field_exists($field_id,$table))
{
   	$d = $CI->db->where($field_id,$id)->get($table)->row_array();
	if(isset($d[$cell])) return $d[$cell];
}

	return '';
}
}

if ( ! function_exists('get_cell_translate_only')){
function get_cell_translate_only($table,$cell,$translation_id,$lng)
{
	$CI =& get_instance();
	$CI->db->select('t1.'.$cell);
	$CI->db->from($table." t");
	$CI->db->join($table." t1","t.id_".$table." = t1.translation_id","LEFT");
	$CI->db->where('t1.translation_id',$translation_id);
	$CI->db->where('t1.lang',$lng);
	$d = $CI->db->get()->row_array();
	if(isset($d[$cell])) return $d[$cell];
	return '';
}
}

if ( ! function_exists('get_cell_translate')){
function get_cell_translate($table,$cell,$field_id,$id)
{
	$CI =& get_instance();
	$lng = $CI->lang->lang();
	if($lng == default_lang()) {
		return get_cell($table,$cell,$field_id,$id);
	}
	else {
		$CI->db->join($table." t1","t.id_".$table." = t1.translation_id AND t1.lang = '".$lng."'","LEFT");
		$CI->db->where('t.'.$field_id,$id);
		$d = $CI->db->get($table." t")->row_array();
		if(isset($d[$cell])) return $d[$cell];
		return '';
	}
	
}
}


function get_all_sub_ids($table,$id = 0,$fld = 'id')
{
	$CI =& get_instance();
	$ids = array();
	if(is_array($id)) {
		$ids = array_merge($ids,$id);
		$recs = $CI->db->where_in('id_parent',$id)->get($table)->result_array();
	}
	else {
		if($id != 0) array_push($ids,$id);
		$recs = $CI->db->where('id_parent',$id)->get($table)->result_array();
	}
	if(!empty($recs)) {
		foreach($recs as $rec) {
			array_push($ids,$rec[$fld]);
			$new_ids = get_all_sub_ids($table,$rec[$fld],$fld);
			if(!empty($new_ids))
			$ids = array_merge($ids,$new_ids);
		}
	}
	return $ids;
}


if ( ! function_exists('if_localhost')){
function if_localhost()
{
	$whitelist = array(
		'127.0.0.1',
		'::1'
	);
	if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
		return true;
	}
	return false;
}
}


if ( ! function_exists('extract_google_map_coordinates')){
function extract_google_map_coordinates($coor,$t = 'lat')
{
	$arr = explode(',',$coor);
	if(count($arr) == 2) {
		if($t == 'lat')
		return trim($arr[0]);
		else
		return trim($arr[1]);
	}
	return false;
}
}

function support_pagination($count,$limit,$offset = 0)
{
	$html = '';
	if($limit < $count) {
	$c = $count / $limit;
	if(floor($c) < $c) $c = floor($c) + 1;
	$html = '<div class="support-pagination">';
		for($i=1;$i<=$c;$i++)  {
			$cl = '';
			if( ($offset + 1) == $i) $cl = 'active';
			$html .= '<a class="'.$cl.'" onclick="load_support_page(this,'.$i.','.$limit.')">'.$i.'</a>';
		}
	$html .= '</div>';
	
	$prev_offset = $offset - 1;
	$next_offset = $offset + 1;
	$html = '';
	$html .= '<div class="support-navigation">';
	$showing = ($offset + 1) * $limit;
	if($showing > $count) {
		$showing = $count;
	}
	$html .= '<span>'.lang("showing").' '.$showing.' '.lang('out of').' '.$count.'</span>';
	if($prev_offset >= 0)
	$html .= '<a onclick="nav_support('.$prev_offset.')"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>';
	if($next_offset <= $c)
	$html .= '<a onclick="nav_support('.$next_offset.')"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>';
	$html .= '</div>';
	}
	return $html;
}



function decodeHtmlEnt($str) {
    $ret = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
    $p2 = -1;
    for(;;) {
        $p = strpos($ret, '&#', $p2+1);
        if ($p === FALSE)
            break;
        $p2 = strpos($ret, ';', $p);
        if ($p2 === FALSE)
            break;
            
        if (substr($ret, $p+2, 1) == 'x')
            $char = hexdec(substr($ret, $p+3, $p2-$p-3));
        else
            $char = intval(substr($ret, $p+2, $p2-$p-2));
            
        //echo "$char\n";
        $newchar = iconv(
            'UCS-4', 'UTF-8',
            chr(($char>>24)&0xFF).chr(($char>>16)&0xFF).chr(($char>>8)&0xFF).chr($char&0xFF) 
        );
        //echo "$newchar<$p<$p2<<\n";
        $ret = substr_replace($ret, $newchar, $p, 1+$p2-$p);
        $p2 = $p + strlen($newchar);
    }
    return $ret;
}
function custom_url_clean($url) {
   $url = preg_replace('~[^\\pL0-9_]+~u', '', $url);
   $url = trim($url, "-");
   $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
   $url = strtolower($url);
   $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
   return $url;
}

function block_http_call_if_bad($str)
	{
		$valid = true;
		//echo $str;exit;
		if (preg_match('/[^A-Za-z0-9.# _$-]/i', $str)) $valid = false;
		return $valid;
	}
	
	function replace_month_to_ar($d)
{
	$en_month = date('M',strtotime($d));
	$ar_month = month_to_ar($en_month);
	//$ar_month = str_replace($ar_month,'');
	return str_replace($en_month,$ar_month,$d);
}
function month_to_ar($t)
{
	$months = array(
    "Jan" => "يناير",
    "Feb" => "فبراير",
    "Mar" => "مارس",
    "Apr" => "أبريل",
    "May" => "مايو",
    "Jun" => "يونيو",
    "Jul" => "يوليو",
    "Aug" => "أغسطس",
    "Sep" => "سبتمبر",
    "Oct" => "أكتوبر",
    "Nov" => "نوفمبر",
    "Dec" => "ديسمبر"
);
if(isset($months[$t])) return $months[$t];
return '';
}