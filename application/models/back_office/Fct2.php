<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Fct extends CI_Model{
	public function __construct(){
	parent::__construct();
	$this->loggedIn = validate_user();
	$this->langue = $this->lang->lang();
	$this->default_langue = default_lang(); 
	$this->table_ext = '';
}

function write_file($path,$data){
$this->load->helper("file");
if ( ! write_file($path, $data))
{
echo 'Unable to write the file';
}
else
{
//echo 'File written!';
}
}

////////////////////////////////////////////////////////////////////////////////////////////
public function select_many_to_many($field,$id,$table_1,$table_2,$order = 'title')
{
	$this->db->select($table_1.'.*');
	$this->db->from($table_1);
	$this->db->join($table_2,$table_1.'.id_'.$table_1.' = '.$table_2.'.id_'.$table_1);
	$cond = array(
		$table_2.'.'.$field=>$id,
	);
	$this->db->where($cond);
	$this->db->group_by($table_1.'.id_'.$table_1);
	$this->db->order_by($table_1.'.'.$order);
	$query = $this->db->get();
	$arr = $query->result_array();
	$results = array();
	foreach($arr as $ar) {
		array_push($results,$ar['id_'.$table_1]);
	}
	return $results;
}
	
public function insert_many_to_many($field,$id,$arr,$table_1,$table_2)
{
	$old_ids = array();
	$new_ids = array();
	$deleted_ids = array();
	$this->db->select('*');
	$this->db->from($table_2);
	$cond = array($field=>$id);
	$this->db->where($cond);
	$this->db->order_by($table_2.'.id_'.$table_2);
	$query = $this->db->get();
	$results = $query->result_array();
	if(!empty($results)) {
		foreach($results as $res) {
			array_push($old_ids,$res['id_'.$table_1]);
		}
	}
	foreach($arr as $ar) {
		if(!in_array($ar,$old_ids)) {
			array_push($new_ids,$ar);
		}
	}
	if(!empty($old_ids)) {
		foreach($old_ids as $id11) {
			if(!in_array($id11,$arr)) {
				array_push($deleted_ids,$id11);
			}
		}
	}
	if(!empty($deleted_ids)) {
		$q = 'DELETE FROM '.$table_2.' WHERE '.$field.' = '.$id.' AND id_'.$table_1.' IN ('.implode(',',$deleted_ids).')';
		$this->db->query($q);
	}
	if(!empty($new_ids)) {
		foreach($new_ids as $new_id) {
			$data['created_date'] = date('Y-m-d h:i:s');
			$data['id_'.$table_1] = $new_id;
			$data[$field] = $id;
			$this->db->insert($table_2,$data);
			//echo $this->db->last_query();exit;
		}
	}
}
////////////////////////////////////////////////////////////////////////////////////////////	
function is_connected_to_module($module,$con_mod)
{
	$mod_id = get_cell('content_type','id_content','name',$module);
	$con_id = get_cell('content_type','id_content','name',$con_mod);
	if($mod_id != '' && $con_id != '') {
		$check = $this->db->query('SELECT * FROM content_type_connects WHERE (id_content = '.$mod_id.' AND id_connect = '.$con_id.') OR (id_content = '.$con_id.' AND id_connect = '.$mod_id.')')->num_rows();
		if($check > 0) return true;
	}
	return false;
}
function get_connected_modules_ids($id)
{
	$res = array();
	$arr = $this->get_connected_modules($id);
	if(!empty($arr)) {
		foreach($arr as $k=>$ar) {
			array_push($res,$ar['id']);
		}
	}
	return $res;
}
function get_module_connections_ids($id)
{
	$res = array();
	$arr = $this->get_module_connections($id);
	if(!empty($arr)) {
		foreach($arr as $k=>$ar) {
			array_push($res,$ar['id']);
		}
	}
	return $res;
}
function insert_connected_modules($id,$arr = array())
{
	$this->db->where("id_content",$id)->delete("content_type_connects");
	if(!empty($arr)) {
		$get_table_name = get_cell('content_type','name','id_content',$id); 
		$table_name = str_replace(' ','_',$get_table_name);
		$this->database_g->create_connect_fields($table_name);
		foreach($arr as $ar) {
			$this->db->insert("content_type_connects",array(
				"id_content"=>$id,
				"id_connect"=>$ar
			));
		}
	}
	return true;
}
function insert_module_connections($id,$arr = array())
{
	$this->db->where("id_connect",$id)->delete("content_type_connects");
	if(!empty($arr)) {
		foreach($arr as $ar) {
			$get_table_name = get_cell('content_type','name','id_content',$ar); 
			$table_name = str_replace(' ','_',$get_table_name);
			$this->database_g->create_connect_fields($table_name);
			$this->db->insert("content_type_connects",array(
				"id_connect"=>$id,
				"id_content"=>$ar
			));
		}
	}
	return true;
}
function check_module_connection($cond_connect= array())
{
	  $q = "";
	  $q .= " select  c2.* ";
	  $q .= " from  content_type_connects ctc ";
	  $q .= " join content_type c on ctc.id_content=c.id_content ";
	  $q .= " join content_type c2 on ctc.id_connect=c2.id_content ";

	  $q .= " where 1 ";
	  $q .= " and c.name='".$cond_connect['name']."'";
       $q .= " and c2.name='".$cond_connect['name2']."'";
	  $q .=" GROUP BY c.id_content";
		
	  $query = $this->db->query($q);
	  $data = $query->row_array();
	  return $data;
	}

function get_connected_modules($id)
{
 $this->db->select("c.name, c.id_content AS id");
 $this->db->from("content_type c");
 $this->db->join("content_type_connects ctc","c.id_content = ctc.id_connect");
 $this->db->where("ctc.id_content",$id);
 $this->db->group_by('c.id_content');
 $this->db->order_by('c.name');
 $query = $this->db->get();
 //echo $this->db->last_query();exit;
 return $query->result_array();
}
function get_module_connections($id)
{
 $this->db->select("c.name, c.id_content AS id");
 $this->db->from("content_type c");
 $this->db->join("content_type_connects ctc","ctc.id_content = c.id_content");
 $this->db->where("ctc.id_connect",$id);
 $this->db->group_by('c.id_content');
 $this->db->order_by('c.name');
 $query = $this->db->get();

 return $query->result_array();
}
////////////////////////////////////////////////////////////////////////////////////////////
function get_trash_modules()
{
	return $this->db->select("module")->group_by("module")->get("trash")->result_array();
}
function get_trash($cond = array(),$like = array(),$limit = '',$offset = 0)
{
	$this->db->select("*");
	$this->db->from("trash");
	if(!empty($cond))
	$this->db->where($cond);
	if(!empty($like))
	$this->db->like($like);
	$this->db->order_by("id DESC");
	if($limit != "")
	$this->db->limit($limit,$offset);
	$query = $this->db->get();
	if($limit != "")
	return $query->result_array();
	else
	return $query->num_rows();
}
////////////////////////////////////////////////////////////////////////////////////////////
function get_role_permissions($role_id)
{
	$arr = $this->db->select('*')->where('id_roles',$role_id)->get('roles_permissions')->result_array();
	$perms = array();
	if(!empty($arr)) {
		foreach($arr as $ar) {
			$perms[$ar['module']] = array();
			foreach(permissions() as $perm) {
				$perms[$ar['module']][$perm] = $ar[$perm];
			}
		}
	}
	return $perms;
}
function get_user_permissions($user_id)
{
	$arr = $this->db->select('*')->where('id_users',$user_id)->get('users_permissions')->result_array();
	$perms = array();
	if(!empty($arr)) {
		foreach($arr as $ar) {
			$perms[$ar['module']] = array();
			foreach(permissions() as $perm) {
				$perms[$ar['module']][$perm] = $ar[$perm];
			}
		}
	}
	return $perms;
}
function get_roles()
{
	if(user_role() == 1)
	return $this->db->order_by("sort_order")->get('roles')->result_array();
	else
	return $this->db->where("critical",0)->order_by("sort_order")->get('roles')->result_array();
}
////////////////////////////////////////////////////////////////////////////////////////////
function get_settings()
{
	return $this->db->where("id_settings",1)->get("settings")->row_array();
}
function getonecell($table,$select,$cond){
$this->db->where($cond);
$query=$this->db->get($table);
$res=$query->row_array();
if(count($res) >0)
return $res[$select];
}
function getonerow($table,$condition){
$this->db->where($condition);
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->row_array();
} else { return array(); }
}
function getonerow_lang($table,$cond){

$fields=$this->fct->get_module_fields($table);

	//$this->db->reset_query();
	if($this->langue == $this->default_langue){
	$select = "t.*";}
	else{
	$select = "t.*, t1.title as title_".$this->langue;
	foreach($fields as $field){
		
	if($field['translated']==1){
	$select .= ", t1.".$field['name'].' as '.$field['name'].'_'.$this->langue;}	}
	}
	
	$this->db->select($select);
	$this->db->from($table.$this->table_ext." t");
	if($this->langue != $this->default_langue)
	$this->db->join($table." t1","t.id_".$table." = t1.translation_id AND t1.lang = '".$this->langue."'","LEFT");
	if(!$this->loggedIn)
	$this->db->where('t.status',1);
	
	$this->db->where('t.translation_id',0);
	foreach($cond as $k=>$v){
		unset($cond[$k]);
		$cond['t.'.$k]=$v;}
	
	$this->db->where($cond);
	$this->db->group_by('t.id_'.$table);
	$query = $this->db->get();
	$results = $query->row_array();

return $results;
}

function getAll_001($table,$order,$cond){
//$this->db->where('deleted',0);
$this->db->where($cond);
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order); }
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}

function getAll_cond($table,$order,$cond){
//$this->db->where('deleted',0);
$this->db->where($cond);
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order); }
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}
function getAll_1($table,$order) {
//$this->db->where('deleted',0);
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order); }
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}
function getAll($table,$order){
//$this->db->where('deleted',0);
if ($this->db->field_exists($order, $table)){
$this->db->order_by($order); }
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->result_array();
} else { return array(); }
}
/////
function get_page($id){
$this->db->where('id_website_pages' , $id);
if($this->config->item('multi_language') == true){
$this->db->where('lang' , $this->lang->lang()); }
$query=$this->db->get('website_pages');
if($query->num_rows() > 0 ){
return $query->row_array();
} 
}
function get_note_labels($id){
$this->db->where('id_note_labels' , $id);
$query=$this->db->get('note_labels');
if($query->num_rows() > 0 ){
return $query->row_array();
} 
}
function getonerecord($table,$condition){
//$this->db->where('deleted',0);
$this->db->where($condition);
$query=$this->db->get($table);
if($query->num_rows() > 0 ){
return $query->row_array();
} 
}
function if_existings($table, $cond){
$this->db->where($cond);
$query = $this->db->get($table);
return ($query->num_rows() > 0)? true : false;
}
// Get Location By IP.
public function location_by_ip($ip = ""){
if($ip == "") $ip = $this->input->ip_address();
$location_arr = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
return $location_arr;
}
// Generaten Token.
public function generate_token(){
$ip = $_SERVER['REMOTE_ADDR'];
$uniqid = uniqid(mt_rand(), true); 
$formKey = md5($ip . $uniqid);
return $formKey;	
}
public function createNewCaptcha()
{
	$img = '';
	 $vals = array(
		  'img_path' => './captcha/',
		  'img_url' => base_url().'captcha/'
		  );
		  $cap = create_captcha($vals);
		  if($cap != '') {
		  $data = array(
		  'captcha_time' => $cap['time'],
		  'ip_address' => $this->input->ip_address(),
		  'word' => $cap['word']
		  );
		  $query = $this->db->insert_string('captcha', $data);
		  $this->db->query($query);
		  $img = $cap['image'];
	  }
	  return $img;
}
///////////////////////////////////////////////////////////////////////////////////////
// Delete Directory and all related
public static function deleteDirectory($dir) {
if (!file_exists($dir)) return true;
if (!is_dir($dir)) return unlink($dir);
foreach (scandir($dir) as $item) {
if ($item == '.' || $item == '..') continue;
if (!self::deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
}
return rmdir($dir);
}
// upload file 
public function uploadImage($image,$path,$dir = 'uploads'){
$config = array();
$config['allowed_types'] = 'jpg|jpeg|png|gif|txt|pdf|docx|doc|csv|avi|flv|wmv|mpeg|mp3|mp4|php|xml|java|html';
$config['upload_path'] ='./'.$dir.'/'.$path;
$config['max_size'] = '4096';
//echo $config['upload_path'];exit;
$this->load->library('upload');
$this->upload->initialize($config);


if(!$this->upload->do_upload($image,true)){
echo $this->upload_err = $this->upload->display_errors();
exit;
//return "";
}
else{
$path = $this->upload->data();
return  $path['file_name'];
}
}
// upload Video
public function uploadVideo($image,$path){
$config = array();
$config['allowed_types'] = 'avi|flv|wmv|mpeg|mp3|mp4';
$config['upload_path'] ='./uploads/'.$path;
$config['max_size'] = '40960';
//$config['max_size'] = '24048';
$config['encrypt_name'] = TRUE;
$this->load->library('upload');
$this->upload->initialize($config);
if(!$this->upload->do_upload($image)){
echo $this->upload_err = $this->upload->display_errors();
exit;
//$this->upload_err = $this->upload->display_errors();
//return array('error' => true, 'error_message' => $this->upload_err);
return false;
} else {
$path = $this->upload->data();
return  $path['file_name'];
}
}
// create thumbs crop
public function createthumb($name,$table,$thumb_value,$dir = 'uploads'){
$path = $dir.'/'.$table.'/';
// Create an array that holds the various image sizes
$configs = array();
$sumb_val=explode(",",$thumb_value);
foreach($sumb_val as $key => $value){
list($width,$height) = explode("x",$value);
$configs[] = array('source_image' => $name, 'new_image' => $value."/".$name, 'width' => $width, 'height' => $height, 'maintain_ratio' => FALSE);
}



$this->load->library('image_lib');
foreach ($configs as $config) {
$this->image_lib->thumb($config, FCPATH.''.$path);
}
}
// create thumbs resize
public function createthumb1($name,$table,$thumb_value,$dir = 'uploads'){
$path = $dir.'/'.$table.'/';
// Create an array that holds the various image sizes
$configs = array();
$sumb_val=explode(",",$thumb_value);
foreach($sumb_val as $key => $value){
list($width,$height) = explode("x",$value);
$configs[] = array('source_image' => $name, 'new_image' => $value."/".$name, 'width' => $width, 'height' => $height, 'maintain_ratio' => TRUE);
}
$this->load->library('image_lib');
foreach ($configs as $config) {
$this->image_lib->thumb($config, FCPATH.''.$path);
}
}
function upload_image_from_admin($module,$field,$id = 0)
{
	$uploads_directory = "uploads";
	$field_name = $field;
	$field = str_replace(" ","_",$field);
	$new_image = '';
	if(!empty($_FILES[$field]["name"])) {
		$get_content_attr = $this->db->join("content_type c","c.id_content = a.id_content")->select("a.thumb, a.thumb_val, a.resize_status")->where(array(
			"c.used_name"=>$module,
			"a.name"=>$field_name
		))->get("content_type_attr a")->row_array();
		
		if($id != "" && $id != 0) {
				
			if( !enable_review() || (enable_review() && user_can_publish()) ) {
				$old_image = get_cell($module,$field,"id_".$module,$id);
				if(!empty($old_image) && file_exists('./'.$uploads_directory.'/'.$module.'/'.$field.'/'.$old_image))
				unlink("./".$uploads_directory."/".$module.'/'.$field."/".$old_image);
				if($get_content_attr["thumb"] == 1){
				
					$sumb_val1=explode(",",$get_content_attr['thumb_val']);
					
					foreach($sumb_val1 as $key => $value) {
						if(file_exists("./".$uploads_directory."/".$module.'/'.$field."/".$value."/".$old_image)){
							unlink("./".$uploads_directory."/".$module.'/'.$field."/".$value."/".$old_image);	 
						}							
					} 
				}
			}
		}
		$new_image = $this->uploadImage($field,$module.'/'.$field);
	
		if($get_content_attr["thumb"] == 1) {
			
			if($get_content_attr["resize_status"] == 1)
			$this->createthumb1($new_image,$module.'/'.$field,$get_content_attr['thumb_val']);
			else
			$this->createthumb($new_image,$module.'/'.$field,$get_content_attr['thumb_val']);
		}
	}
	return $new_image;
}
function upload_file_from_admin($module,$field,$id = 0)
{
	$uploads_directory = "uploads";
	$field_name = $field;
	$field = str_replace(" ","_",$field);
	$new_file = '';
	if(!empty($_FILES[$field]["name"])) {
		$get_content_attr = $this->db->join("content_type c","c.id_content = a.id_content")->select("a.thumb, a.thumb_val, a.resize_status")->where(array(
			"c.used_name"=>$module,
			"a.name"=>$field_name
		))->get("content_type_attr a")->row_array();
		if($id!="" && $id != 0) {
			if(!enable_review() || (enable_review() && user_can_publish())) {
				$old_file = get_cell($module,$field,"id_".$module,$id);
				if(!empty($old_file) && file_exists('./'.$uploads_directory.'/'.$module.'/'.$field.'/'.$old_file))
				unlink("./".$uploads_directory."/".$module.'/'.$field."/".$old_image);
			}
		}
		$new_file = $this->uploadImage($field,$module.'/'.$field);
	}
	return $new_file;
}
///////////////////////////////////////////////////////////////////////////////////////
// url generate
public function cleanURL($url)
	{
		$url_name = strtolower($url);
		$cond = array('url_route' => $url_name);
		if($id != '')
		$cond["id_".$table." != "] = $id;
		$check_exist = $this->if_existings($table, $cond);
		if ($check_exist == false)
		{
			return $url_name;
		}

		$new_url_name = '';
		for ($i = 1; $i < 1000; $i++)
		{
			$new_url_name = $url_name.$i;
			$cond = array('title_url' =>  $new_url_name);
			if($id != '')
			$cond["id_".$table." != "] = $id;
			$check_exist = $this->if_existings($table, $cond);
			if ($check_exist == false)
			{
				$return_url = $new_url_name;
				break;
			}
		}
		return $return_url;
}
public function cleanURL_Ar($table, $url_name , $id = '')
	{
		$url_name = strtolower($url_name);
		$cond = array('title_url' => $url_name);
		if($id != '')
		$cond["id_".$table." != "] = $id;
		$check_exist = $this->if_existings($table, $cond);
		if ($check_exist == false)
		{
			return $url_name;
		}
		

		$new_url_name = '';
		for ($i = 1; $i < 1000; $i++)
		{
			$new_url_name = $url_name.$i;
			$cond = array('title_url' =>  $new_url_name);
			if($id != '')
			$cond["id_".$table." != "] = $id;
			$check_exist = $this->if_existings($table, $cond);
			if ($check_exist == false)
			{
				$return_url = $new_url_name;
				break;
			}
		}
		return $return_url;
}	
///////////////////////////////////////////////////////////////////////////////////////
function get_module_fields($content_type,$display = FALSE,$enable_filter = FALSE,$cond=array())
{
	$cond['c.name'] = $content_type;
	if($display)
	$cond['a.display'] = 1;
	if($enable_filter) {
		$cond['a.enable_filtration'] = 1;
		$cond['a.id_attr !='] = 3;
		$cond['a.id_attr !='] = 4;
		$cond['a.id_attr !='] = 5;
	}
	//if(!empty($cond)){print_r($cond);exit;}
	if(isset($cond['translated'])) {
		$cond['a.translated'] = 1;unset($cond['translated']);}
	
	$results = $this->db->select("a.id_attr AS id, a.name, a.display, a.display_name, a.type, a.foreign_key, a.translated, a.id_content, a.validation, c.name AS table_name, f.name AS foreign_table, a.required, a.max_length, a.min_length, a.unique_val, a.matches, a.hint")->from("content_type_attr a")->join("content_type c","c.id_content = a.id_content")->join("content_type f","f.id_content = a.foreign_key","left")->where($cond)->group_by("a.id_attr")->order_by('a.sort_order')->get()->result_array();
	return $results;
}
function get_modules($arr = FALSE)
{
	$results = $this->db->select("id_content AS id, name")->where(array(
		'deleted'=>0,
		'enable_seo'=>1
	))->get("content_type")->result_array();	
	if($arr) {
		$newrr = array();
		foreach($results as $r) {
			$newrr[$r['name']] = str_replace('_',' ',$r['name']);
		}
		return $newrr;
	}
	else
	return $results;
}
function get_content_types($grp = '')
{
	if($grp != '')
	$this->db->where("menu_group",$grp);
	$this->db->order_by("name");
	return $this->db->get('content_type')->result_array();
}
function get_content_types_generator()
{
	$ignore = array('users','roles','roles_permissions','users_permissions','seo','careers_applications','contact_form','newsletter_subscribers','images','routes');
	return $this->db->where_not_in('name',$ignore)->order_by("name")->get('content_type')->result_array();
}
///////////////////////////////////////////////////////////////////////////////////////
/*function record_approval($module,$record) {
	$cond = array(
		"module"=>$module,
		"record"=>$record
	);
	$check = $this->db->where($cond)->get("review_status")->row_array();
	if(empty($check)) {
		$this->db->insert("review_status",$cond);
	}
	$field = '';
	if(user_role() == 4) $field = "admin_approve";
	if(user_role() == 2) $field = "super_admin_approve";
	if($field != '')
	$this->db->where($cond)->update("review_status",array($field=>1));
	return true;
}*/
function publish_record($module,$record)
{
	$review_data = $this->db->where("id_".$module,$record)->get($module.'_review')->row_array();
	$i = array();
	foreach($review_data as $k => $rev) {
		if($k != "id_".$module && $k != "created_on" && $k != "status" && $k != "translation_id" && $k != "sort_order") {
			$i[$k] = $rev;
		}
		$i[$k] = $rev;
	}
	$this->clean_live_file($module,$record,$i);
	if(!empty($i))
	$this->db->where("id_".$module,$record)->update($module,$i);
	return true;
}
function clean_live_file($module,$record,$new_data)
{
	$this->load->model($module.'_m');
	$mod = $module.'_m';
	$live_data = $this->$mod->get($record);
	foreach($live_data as $k1 => $d) {
		$get_attr = $this->db->join("content_type c","c.id_content = a.id_content")->where(array(
			"c.used_name"=>$module,
			"a.name"=>str_replace("_"," ",$k1)
		))->get("content_type_attr a")->row_array();
		if($get_attr['type'] == 4 && $live_data[$k1] != $new_data[$k1]) {
			if(file_exists('./uploads/'.$module.'/'.$live_data[$k1]))
			unlink('./uploads/'.$module.'/'.$k1.'/'.$live_data[$k1]);
			if($get_attr["thumb"] == 1){
				$sumb_val1=explode(",",$get_attr['thumb_val']);
				foreach($sumb_val1 as $key => $value) {
					if(file_exists("./uploads/".$module.'/'.$k1."/".$value."/".$live_data[$k1])){
						unlink("./uploads/".$module.'/'.$k1."/".$value."/".$live_data[$k1]);	 
					}							
				} 
			}
		}
		elseif($get_attr['type'] == 5 && $live_data[$k1] != $new_data[$k1]) {
			if(file_exists('./uploads/'.$module.'/'.$k1.'/'.$live_data[$k1]))
			unlink('./uploads/'.$module.'/'.$k1.'/'.$live_data[$k1]);
		}
	}
	return true;
}
function record_review($module,$record,$lang = '')
{
	if($lang == '') $lang = default_lang();
	$check = $this->db->where(array(
		"module"=>$module,
		"record"=>$record,
		"status"=>0,
		"lang"=>$lang
	))->get("content_type_reviews")->row_array();
	if(empty($check)) {
		$arr = array();
		$arr['module'] = $module;
		$arr['record'] = $record;
		$arr['created_on'] = date("Y-m-d H:i:s");
		$arr['user_id'] = user_id();
		$arr['status'] = 0;
		$arr['lang'] = $lang;
		$this->db->insert('content_type_reviews',$arr);
		$rid = $this->db->insert_id();
	}
	else {
		$rid = $check['id'];
	}
	return $rid;
}
function get_reviews()
{
	$res = $this->db->select("c.*, u.name AS user_name")->join("users u","u.id_users = c.user_id")->where(array(
		"c.approved_by_super_admin"=>0,
		//"c.status"=>0
	))->order_by("c.id DESC")->get("content_type_reviews c")->result_array();
	return $res;
}
function change_approval($module,$record,$lang,$appr)
{
	if($this->user_role <= 3) {
		$check = $this->db->where(array(
			"module"=>$module,
			"record"=>$record,
			"lang"=>$lang,
			//"status"=>0
		))->order_by("id DESC")->get("content_type_reviews")->row_array();
		//print '<pre>'; print_r($check); exit;
		if(empty($check)) {
			$id = $this->record_review($module,$record,$lang);
		}
		else {
			$id = $check['id'];
		}
		if($this->user_role == 2 || $this->user_role == 1) $rol = 'approved_by_super_admin';
		else $rol = 'approved_by_admin';
		
		$u[$rol] = $appr;
		if($rol == 'approved_by_super_admin' && $appr == 1) $u['status'] = 1;
		$this->db->where("id",$id)->update("content_type_reviews",$u);
		//exit($rol);
		if($appr == 1)
		$this->fct->insert_log("disapprove",$module,$record);
		else
		$this->fct->insert_log("approve",$module,$record);
		if($this->user_role == 2) {
			$default_lang = default_lang();
			if($default_lang != $lang) {
				$this->db->where(array(
					"translation_id"=>$record,
					"lang"=>$lang
				))->update($module,array("status"=>$appr));
				$this->db->where(array(
					"translation_id"=>$record,
					"lang"=>$lang
				))->update($module.'_review',array("status"=>$appr));
			}
			else {
				$this->db->where(array(
					"id_".$module=>$record,
				))->update($module,array("status"=>$appr));
				$this->db->where(array(
					"id_".$module=>$record,
				))->update($module.'_review',array("status"=>$appr));
			}
		}
	}
	return true;
}
function change_status($module,$record,$status)
{
	$this->db->where(array(
		"id_".$module=>$record
	))->update($module,array(
		"status"=>$status
	));
	$affected_rows = $this->db->affected_rows();
	if($affected_rows > 0) {
		if($status == 1)
			$this->fct->insert_log("publish",$module,$record);
		elseif($status == 2)
			$this->fct->insert_log("under_review",$module,$record);
		else
			$this->fct->insert_log("unpublish",$module,$record);
	}
	return true;
}
///////////////////////////////////////////////////////////////////////////////////////
function insert_log($action,$module = '',$record = 0,$user_id = 0)
{
	if($user_id == 0)
	$user_id = user_id();
	
	$user_ip = user_ip();
	$arr['ip'] = $user_ip;
	$arr['action'] = $action;
	$arr['module'] = $module;
	$arr['record'] = $record;
	$arr['date'] = date("Y-m-d H:i:s");
	$arr['uid'] = $user_id;
	$this->db->insert('log',$arr);
	return true;
}
function move_to_trash($module,$record = 0,$data,$field_title = 'title')
{
	$title = '';
	if(isset($data[$field_title])) { 
	$title = $data[$field_title];
	if($data[$field_title] == '') $title = $module; }
	else if(!isset($data[$field_title])) $title = '';
	else $title = $data[$field_title];
	$this->db->insert("trash",array(
		'module'=>$module,
		'record'=>$record,
		'title'=>$title,
		'content'=>json_encode($data)
	));
	return $this->db->insert_id();
}
///////////////////////////////////////////////////////////////////////////////////////

function get_tree($table,$id_parent = 0,$translated = FALSE,$normaldropDown = false)
{
	$flds = ', t.title';
	if ($translated && $this->langue != $this->default_langue) {
		$flds = ', t1.title';
	}
	$c = array();
	if ($translated && $this->langue == $this->default_langue)
	$c = array('t.translation_id'=>0);
	elseif($translated)
	$c = array('t1.translation_id !='=>0);
	$this->db->select("t.id_".$table." AS id".$flds);
	if($translated && $this->langue != $this->default_langue) {
		$this->db->join($table.' t1','t.id_'.$table.' = t1.translation_id AND t1.lang = "'.$this->langue.'"','LEFT');
	}
	if($this->user_info['manage_his_content'] == 1) {
		$this->db->join('log','log.record = '.$this->table.'.id_'.$table.' AND log.module = "'.$table.'"');
	}
	if($this->user_info['manage_his_division'] == 1) {
		if(in_array($table,array('products_technologies','products_tabs','product_support')))
		$this->db->join('products','products.id_products = '.$table.'.record AND '.$table.'.module = "products"');
	}
	$this->db->where($c);
	if(!$normaldropDown) {
		$this->db->where(array(
			"t.id_parent"=>$id_parent,
		));
	}


	if($this->user_info['manage_his_content'] == 1) {
		//exit($this->user_id);
		$this->db->where(array(
			'log.action'=>'create',
			'log.uid'=>$this->user_id
		));
	}
	if($this->user_info['manage_his_division'] == 1) {
		if(in_array($table,array('products_technologies','products_tabs','product_support')))
		$this->db->where('products.id_divisions',$this->user_info['id_divisions']);
		
		if(in_array($table,array('divisions','categories','products')))
		$this->db->where('id_divisions',$this->user_info['id_divisions']);
	}
if($this->user_role != 1 && ($table == 'roles' || $table == 'users'))
	$this->db->where('id_roles >',1);

	$query = $this->db->get($table.' t');
	//echo $this->db->last_query();exit;
	$results = $query->result_array();
	if(!empty($results) && !$normaldropDown) {
		foreach($results as $k => $res) {
			$results[$k]['sub_levels'] = $this->get_tree($table,$res["id"],$translated);
		}
	}

	return $results;
}
function get_parent_ids($table,$id,$with_selected_id = TRUE) {
	$arr = array();
	$get = $this->db->where("id_".$table,$id)->get($table)->row_array();
	if($with_selected_id)
	
	array_push($arr,$get['id_'.$table]);
	$count_parent = $this->db->where("id_".$table,$get['id_parent'])->get($table)->num_rows();
	while($count_parent > 0) {
		$get = $this->db->where("id_".$table,$get['id_parent'])->get($table)->row_array();
		array_push($arr,$get['id_'.$table]);
		$count_parent = $this->db->where("id_".$table,$get['id_parent'])->get($table)->num_rows();
	}
	return $arr;
}
function get_sub_ids($table,$id,$with_selected_id = TRUE,$arr = array()) {
	if($with_selected_id)
	array_push($arr,$id);
	$get = $this->db->where("id_parent",$id)->get($table)->result_array();
	foreach($get as $g) {
		array_push($arr,$g['id_'.$table]);
		$this->get_sub_ids($table,$id,$with_selected_id,$arr);
	}
	return $arr;
}
function display_tree_dropdown($arr,$ind = '', $selected = 0)
{
	$html = '';
	foreach($arr as $k => $ar) {
		$cl = '';
		if(is_array($selected)) {
			if(in_array($ar['id'],$selected)) $cl = 'selected="selected"';
		}
		else {
			if($ar['id'] == $selected) $cl = 'selected="selected"';
		}
		$html .= '<option value="'.$ar['id'].'" '.$cl.'>'.$ind.' '.$ar['title'].'</option>';
		if(!empty($ar['sub_levels'])) {
			$ind1 = $ind.'-->';
			$html .= $this->display_tree_dropdown($ar['sub_levels'],$ind1,$selected);
		}
	}
	return $html;
}
///////////////////////////////////////////////////////////////////////////////////////
function create_password_request($user)
{
	$token = $this->generate_password(8);
	$token = $this->checktoken($token);
	//echo 'test: '.$token;exit;
	$created_date = date('Y-m-d h:i:s');
	$time = strtotime($created_date);
	$expiration_date = strtotime("+5 day", $time);
	$expiration_date = date('Y-m-d h:i:s',$expiration_date);
	$data = array(
		'id_user' => $user['id_users'],
		'created_date' => $created_date,
		'expiration_date' => $expiration_date,
		'token' => md5($token)
	);
	$this->db->insert('password_requests',$data);
	//print_r($data);exit;
	return $data;
}
function generate_password($length) {
    $chars = time()."abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars),0,$length);
}
function checktoken($token)
{
	$cond = array('token'=>md5($token));
	$check = $this->getonerow('password_requests',$cond);
	if(!empty($check)) {
		$token = $this->generate_password(8);
		$this->checktoken($token);
	}
	else {
		return $token;
	}
}
///////////////////////////////////////////////////////////////////////////////////////
function get_languages()
{
	return $this->db->order_by('sort_order')->get("languages")->result_array();
}
function update_translations()
{
 $languages = $this->db->get("languages")->result_array();
 $arr = array();
 $find1 = array("lang[");
 $replace1 = array('$lang[');
 $find2 = array("'");
 $replace2 = array("\'");
 $default_lang = default_lang();
 foreach($languages as $lang) {
  $static_lang = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');";
  $arr = $this->db->where(array(
   "lang"=>$lang['symbole'],
   //"status"=>1
  ))->get("static_words")->result_array();
  if(!empty($arr)) {
   foreach($arr as $ar) {
    if($lang['symbole'] != $default_lang) {
     $ind_x = get_cell('static_words','index','id_static_words',$ar['translation_id']);
    }
    else {
     $ind_x = $ar['index'];
    }
    $static_lang .= "
lang['".strtoupper($ind_x)."'] = '".str_replace($find2,$replace2,$ar['title'])."';";
   }
   $path = "./application/language/".strtolower($lang['title'])."/statics_lang.php";
   $static_lang = str_replace($find1,$replace1,$static_lang);
   $this->fct->write_file($path,$static_lang);
  }
 }
 return true;
}
function insert_update_seo($module,$record = 0,$data)
{
	$fields = $this->db->list_fields('seo');
	$check = $this->db->where(array(
		'module'=>$module,
		'record'=>$record
	))->get('seo')->row_array();
	$arr = array();
	foreach($fields as $k => $field) {
		if($field != "id_".$module) {
			if(isset($data[$field])) {
				$arr[$field] = $data[$field];
			}
		}
	}
	if(!empty($arr)) {
		if(empty($check)) {
			$arr['module'] = $module;
			$arr['record'] = $record;
			$arr['created_on'] = date("Y-m-d H:i:s");
			//$arr['status'] = $record;
			if(!isset($arr['meta_title'])) $arr['meta_title'] = '';
			
			if($arr['meta_title'] == '' && isset($data['title'])) $arr['meta_title'] = $data['title'];
			if(!isset($arr['url_route']) || (isset($arr['url_route']) && empty($arr['url_route']) ) ) {
				$arr['url_route'] = $arr['meta_title'];
			}
			$arr['lang'] = default_lang();	
			$this->db->insert('seo',$arr);
		}
		else {
			$this->db->where('id_seo',$check['id_seo'])->update('seo',$arr);
		}
	}
	$this->update_routes();
	return true;
}
function create_default_seo($module,$record,$title,$lang = '')
{
	$default_lang = default_lang();
	$trans_id = 0;
	$proceed = TRUE;
	if($lang != '') {
		if($lang != $default_lang) {
			$record_trans_id = get_cell($module,'translation_id','id_'.$module,$record);
			if($record_trans_id != '') {
				$seo_record = $this->db->where(array(
					'module'=>$module,
					'record'=>$record_trans_id
				))->get('seo')->row_array();
				//print_r($seo_record);exit;
				if(!empty($seo_record)) $trans_id = $seo_record['id_seo'];
				else $proceed = FALSE;
			}

		}
	}
	if($proceed) {
		$arr['translation_id'] = $trans_id;
		$arr['module'] = $module;
		$arr['record'] = $record;
		$arr['created_on'] = date("Y-m-d H:i:s");
		$arr['title'] = $title;
		$arr['meta_title'] = $title;
		$arr['meta_keywords'] = $title;
		$arr['meta_description'] = $title;
		$arr['url_route'] = $this->prepare_url($title);
		$arr['status'] = 1;
		if($lang == '')
		$arr['lang'] = default_lang();
		else
		$arr['lang'] = $lang;
		$this->db->insert('seo',$arr);
		$this->fct->insert_log("create",'seo',$record);
		return $this->db->insert_id();
	}
	return false;
}
function update_routes()
{
	//return true;
	$default_lang = default_lang();
	$languages = $this->db->get("languages")->result_array();
	$arr = array();
	$find1 = array("route[");
	$replace1 = array('$route[');
	$find2 = array("'");
	$replace2 = array("\'");
	
	$find3 = array("config[");
	$replace3 = array('$config[');
	
	$config_code = '<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");
config[\'routers\']["'.$default_lang.'"]["test"] = "test";';
	
	$routes_code = '<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");
route[\'default_controller\'] = "home/index";';
	$routes_langs_code = array();
	foreach($languages as $lang) {
		$symbol = $lang['symbole'];
		$q = "SELECT * FROM seo WHERE lang = '".$symbol."'";
		$query = $this->db->query($q);
		$arr = $query->result_array();
	
		//print '<pre>'; print_r($arr); exit;
		if(!empty($arr)) {
			foreach($arr as $ar) {
				$url_index = '';
				switch($ar['module']) {
					case 'divisions':
						$url_index = 'products/index/'.$ar['record'];
						break;
					case 'categories':
						$parend_ids = array($ar['record']);
						$pid_record = $this->db->select('id_parent')->where('id_categories',$ar['record'])->get('categories')->row_array();
						while(!empty($pid_record) && $pid_record['id_parent'] != 0) {
							array_push($parend_ids,$pid_record['id_parent']);
							$pid_record = $this->db->select('id_parent')->where('id_categories',$pid_record['id_parent'])->get('categories')->row_array();
						}
						//print '<pre>';print_r($parend_ids);exit;
						$cat_url_index = '';
						$div_id_record = $this->db->select('id_divisions')->where('id_categories',$parend_ids[count($parend_ids) - 1])->get('categories')->row_array();
						
						if(!empty($div_id_record)) {
							$cat_url_index = 'products/index/'.$div_id_record['id_divisions'];
							for($i = count($parend_ids) - 1; $i >= 0; $i--)
							$cat_url_index .= '/'.$parend_ids[$i];
						}
						if($cat_url_index != '')
						$url_index = $cat_url_index;
						else
						$url_index = '';
						break;
					case 'products':
						$url_index = 'products/details/'.$ar['record'];
						break;
					case 'news':
						$url_index = 'news/details/'.$ar['record'];
						break;
					case '':
						$url_index = $ar['url_index'];
						break;
					default:
						$url_index = $ar['url_index'];
						break;
				}
				//$url = $this->prepare_url($ar['url_route'],$ar['id_seo']);
				$url = $ar['url_route'];
				/*if($url != ''){
					$url_u = $this->prepare_url($url,$ar['id_seo']);
					$this->db->where('id_seo',$ar['id_seo'])->update('seo',array('url_route'=>$url_u));
				}*/
				if($url_index != '' && !empty($url)) {
					$check = $this->db->where(array(
						"url_route"=>$url,
						"lang"=>$symbol
					))->get("routes_bk")->num_rows();
					if($check == 0) {
						$this->db->insert("routes_bk",array(
							"url_route"=>$url,
							"lang"=>$symbol,
							"url_index"=>$url_index
						));
					}
					if($default_lang == default_lang())
					$routes_code.= '
route["'.$url.'"] = "'.$url_index.'";';
					$routes_code.= '
route["^'.$symbol.'/'.$url.'"] = "'.$url_index.'";';
					$config_code.= '
config["routers"]["'.$symbol.'"]["'.$url_index.'"] = "'.$url.'";';
				}
			}
		}
	}
	$routes_code .= '
route["^en/(.+)$"] = "$1";
route["^ar/(.+)$"] = "$1";
route["^en$"] = route["default_controller"];
route["^ar$"] = route["default_controller"];
route["back_office"] = "back_office/login";
route["404_override"] = "error404";';

	$path = "./application/config/routes.php";
	$routes_code = str_replace($find1,$replace1,$routes_code);
	$this->fct->write_file($path,$routes_code);
	
	$path1 = "./application/config/url_routes.php";
	$config_code = str_replace($find3,$replace3,$config_code);
	$this->fct->write_file($path1,$config_code);

	return true;
}
function prepare_url($url,$id)
{
	$new_url = $url;

	$if_exist = $this->if_existings("seo", array("url_route"=>$new_url,"id_seo !="=>$id));
	$i = 0;
	$add = '';
	while($if_exist) {
		$new_url = $url.$add;
		$i++;
		$add = '-'.$i;
		$if_exist = $this->if_existings("seo", array("url_route"=>$new_url,"id_seo !="=>$id));
	}
	$find=array(' ',')','(');
	$replace=array('-','-','-');
	$new_url=str_replace($find,$replace,$new_url);
	$new_url = urlencode($new_url);
            
		
	return strtolower($new_url);
}
///////////////////////////////////////////

//
}