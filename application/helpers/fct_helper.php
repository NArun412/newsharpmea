<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('get_page')) {

    function get_page($id, $lang = "") {
        $CI = & get_instance();
        if ($lang == '')
            $lang = $CI->lang->lang();

        $d_lang = default_lang();

        if ($lang == $d_lang)
            $select = 't.id_website_pages, t.title, t.description, t.image ';
        else
            $select = 't.id_website_pages, t1.title, t1.description, t.image ';

        $CI->db->select($select);
        if ($lang != $d_lang)
            $CI->db->join("website_pages t1", "t.id_website_pages = t1.translation_id AND t1.lang = '" . $lang . "'");
        $CI->db->where('t.id_website_pages', $id);
        $query = $CI->db->get('website_pages t');
//echo $CI->db->last_query();exit;
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

}

if (!function_exists('getAll')) {

    function getAll($table, $order) {
        $CI = & get_instance();
        $CI->db->where('deleted', 0);
        if ($CI->db->field_exists($order, $table)) {
            $CI->db->order_by($order);
        }
        if ($CI->db->field_exists('created_date', $table)) {
            $CI->db->order_by('created_date', 'desc');
        }
        $query = $CI->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

}

if (!function_exists('date_in_formate')) {

    function date_in_formate($date) {
        if ($date != "") {
            list($d, $m, $y) = explode('/', $date);
            $date = $y . "-" . $m . "-" . $d;
        } else {
            $date = "0000-00-00";
        }
        return $date;
    }

}

if (!function_exists('date_in_formate_new')) {

    function date_in_formate_new($date) {
        if ($date != "") {
            list($m, $d, $y) = explode('/', $date);
            $date = $y . "-" . $m . "-" . $d;
        } else {
            $date = "0000-00-00";
        }
        return $date;
    }

}

if (!function_exists('date_out_formate')) {

    function date_out_formate($date) {
        if (!empty($date) && $date != "0000-00-00") {
            list($y, $m, $d) = explode('-', $date);
            $date = $d . "/" . $m . "/" . $y;
        } else {
            $date = "";
        }
        return $date;
    }

}

if (!function_exists('date_out_formate_new')) {

    function date_out_formate_new($date) {
        if (!empty($date) && $date != "0000-00-00") {
            list($y, $m, $d) = explode('-', $date);
            $date = $m . "/" . $d . "/" . $y;
        } else {
            $date = "";
        }
        return $date;
    }

}

if (!function_exists('new_date_format')) {

    function new_date_format($date) {
        if ($date != "" && $date != "0000-00-00") {
            list($y, $m, $d) = explode('-', $date);
            $new_date = $d . "." . $m . "." . $y;
        } else {
            $new_date = "";
        }
        return $new_date;
    }

}

if (!function_exists('date_format_with_month')) {

    function date_format_with_month($date) {
        if ($date != "" && $date != "0000-00-00") {
            list($y, $m, $d) = explode('-', $date);
            $new_date = $d . " " . month_name($m) . " " . $y;
        } else {
            $new_date = "";
        }
        return $new_date;
    }

}



if (!function_exists('date_difference')) {

    function date_difference($d1, $d2) {
        return (int) abs((strtotime($d1) - strtotime($d2)) / (60 * 60 * 24));
    }

}

if (!function_exists('getonerecord')) {

    function getonerecord($table, $condition) {
        $CI = & get_instance();
        $CI->db->where('deleted', 0);
        $CI->db->where($condition);
        $query = $CI->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

}





if (!function_exists('getonecell')) {

    function getonecell($table, $select, $cond) {
        $CI = & get_instance();
        $CI->db->select($select);
        $CI->db->from($table);
        $CI->db->where($cond);
        $query = $CI->db->get();
        $res = $query->row_array();
        if (count($res) > 0)
            return $res[$select];
    }

}

if (!function_exists('getonerow')) {

    function getonerow($table, $condition) {
        $CI = & get_instance();
        $CI->db->where($condition);
        $query = $CI->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

}

if (!function_exists('number_word')) {

    function number_word($x) {
        $word = array(
            '1' => 'one',
            '2' => 'two',
            '3' => 'three',
            '4' => 'four',
            '5' => 'five',
            '6' => 'six',
            '7' => 'seven',
            '8' => 'eight',
            '9' => 'nine',
            '10' => 'ten',
            '11' => 'eleven',
            '12' => 'twelve',
        );
        return $word[$x];
    }

}

if (!function_exists('month_name')) {

    function month_name($x) {
        $word = array(
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'May',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Aug',
            '9' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        );
        return $word[$x];
    }

}

if (!function_exists('getAll_cond')) {

    function getAll_cond($table, $order, $cond, $cond1 = array()) {
        $CI = & get_instance();
        $CI->db->where('deleted', 0);
        $CI->db->where($cond);
        $CI->db->where_in($cond1);
        if ($CI->db->field_exists($order, $table)) {
            $CI->db->order_by($order);
        }
        if ($CI->db->field_exists('created_date', $table)) {
            $CI->db->order_by('created_date', 'desc');
        }
        $query = $CI->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

}

if (!function_exists('getAll_cond2')) {

    function getAll_cond2($table, $order, $cond) {
        $CI = & get_instance();
        $CI->db->where('deleted', 0);
        $CI->db->where('id_locations', $CI->session->userdata('country'));
        $CI->db->where($cond);
        if ($CI->db->field_exists($order, $table)) {
            $CI->db->order_by($order);
        }
        if ($CI->db->field_exists('created_date', $table)) {
            $CI->db->order_by('created_date', 'desc');
        }
        $query = $CI->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

}

if (!function_exists('check_if_exist')) {

    function check_if_exist($table, $cond) {
        $CI = & get_instance();
        $CI->db->where($cond);
        $query = $CI->db->get($table);
        if ($query->num_rows() == 0)
            return false;
        else
            return true;
    }

}

if (!function_exists('getAll_limit_cond')) {

    function getAll_limit_cond($table, $order, $des, $limit, $cond) {
        $CI = & get_instance();
        $CI->db->where('deleted', 0);
        $CI->db->where($cond);
        if ($CI->db->field_exists($order, $table)) {
            $CI->db->order_by($order, $des);
        }
        $query = $CI->db->get($table, $limit);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

}

if (!function_exists('get_first_row')) {

    function get_first_row($table, $where = array()) {
        $CI = & get_instance();
        if (count($where) > 0)
            $query = $CI->db->get_where($table, $where);
        else
            $query = $CI->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->first_row();
        } else {
            return array();
        }
    }

}

if (!function_exists('get_last_row')) {

    function get_last_row($table, $where = array()) {
        $CI = & get_instance();
        if (count($where) > 0)
            $query = $CI->db->get_where($table, $where);
        else
            $query = $CI->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->last_row();
        }
    }

}

if (!function_exists('date_formate')) {

    function date_formate($date) {
        if (!empty($date) && $date != "0000-00-00") {
            $date_timestamp = strtotime($date);
            $new_date = date('d M Y', $date_timestamp);
        } else {
            $new_date = "";
        }
        return $new_date;
    }

}

if (!function_exists('switch_url')) {

    function switch_url($table, $lang, $id, $controller) {
        $this->fct->getonerow($table, 'title_url', array('lang' => $lang, 'id_parent' => $id));
        redirect(site_url($controller . '/' . $tite_url));
    }

}

/* if ( ! function_exists('route_to')){
  function route_to($path,$sub = array()) {
  $CI =& get_instance();
  $route = array_search($path, $CI->router->routes, TRUE);
  //print_r($path);
  //print_r($CI->router->routes);
  if($route == '') {
  $route = $path;
  } else {
  $parameters = array();
  $route_arr = explode('/',$route);
  if(!empty($sub)){
  for($i = 1; $i <= sizeof($sub); $i++){
  $route_arr[count($route_arr) - $i] = $sub[count($sub) - $i];
  }
  }
  //$parameters = array_reverse($parameters);
  $lang = $CI->config->item('multi_language');
  if($lang == 1){
  unset($route_arr[0]);
  }
  if(isset($route_arr))
  $route = join('/',$route_arr);
  }
  return site_url($route);
  }
  } */

if (!function_exists('route_to_d')) {

    function route_to_d($path, $lang = '') {
        $CI = & get_instance();
        $routes = $CI->config->item("routers");

        //exit($lang);
        if ($lang == '')
            $lang = $CI->lang->lang();

        //exit($lang);
        //if($lang == 'ar')
        //return base_url().$lang.'/'.$path;
        if (isset($routes[$lang][$path])) {
            //exit(site_url($routes[$lang][$path]));
            return site_url($routes[$lang][$path]);
            //	return base_url().$lang.'/'.$routes[$lang][$path];
        } else {
            return site_url($path);
            //return base_url().$lang.'/'.$path;
        }
        //return site_url($route);
    }

}


if (!function_exists('route_to')) {

    function route_to($path, $lang = '') {
        $CI = & get_instance();
        $routes = $CI->config->item("routers");

        //exit($lang);
        if ($lang == '')
            $lang = $CI->lang->lang();

        //exit($lang);
        //if($lang == 'ar')
        //return base_url().$lang.'/'.$path;
        if (isset($routes[$lang][$path])) {
            //exit(site_url($routes[$lang][$path]));
            //return site_url($routes[$lang][$path]);
            return base_url() . $lang . '/' . $routes[$lang][$path];
        } else {
            return base_url() . $lang . '/' . $path;
        }
        //return site_url($route);
    }

}
if (!function_exists('route_For_Product_Index')) {

    function route_For_Product_Index($path, $lang = '') {
        $CI = & get_instance();
        $routes = $CI->config->item("routers");

        //exit($lang);
        if ($lang == '')
            $lang = $CI->lang->lang();

        //exit($lang);
        //if($lang == 'ar')
        //return base_url().$lang.'/'.$path;
        
            return base_url() . $lang . '/' . $path;
        //return site_url($route);
    }

}
if (!function_exists('route_For_Product_Title')) {

    function route_For_Product_Title($path, $lang = '') {
              
        $CI = & get_instance();
        $routes = $CI->config->item("routers");

        //exit($lang);
        if ($lang == '')
            $lang = $CI->lang->lang();

        //exit($lang);
        //if($lang == 'ar')
        //return base_url().$lang.'/'.$path;
        if (isset($routes[$lang][$path])) {
            //exit(site_url($routes[$lang][$path]));
            //return site_url($routes[$lang][$path]);
            return base_url() . $lang . '/products/index/' . $path;
        } else {
            return base_url() . $lang . '/' . $path;
        }
        //return site_url($route);
    }

}

if (!function_exists('error_message')) {

    function error_message($msg) {
//$message ='<div class="alert-box error"><span>error: </span>'.$msg.'</div>';
        $message = '<div class="alert-box error"><span></span>' . $msg . '</div>';
        return $message;
    }

}

if (!function_exists('success_message')) {

    function success_message($msg) {
//$message ='<div class="alert-box success"><span>success: </span>'.$msg.'</div>';
        $message = '<div class="alert-box success"><span></span>' . $msg . '</div>';
        return $message;
    }

}

if (!function_exists('randomPassword')) {

    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

}

if (!function_exists('admin_menu_groups')) {

    function admin_menu_groups() {
        $groups = get_instance()->db->query('SELECT * FROM menu_groups ORDER BY sort_order')->result_array();
        //print '<pre>'; print_r($groups); exit;
        $multi_languages = getonecell('settings', 'multi_languages', array('id_settings' => 1));
        $arr = array();
        if (!empty($groups)) {
            foreach ($groups as $grp) {
                if ($grp['id'] == 4 && $multi_languages != 1)
                    continue;
                $arr[$grp['id']] = $grp['name'];
            }
        }
        //print '<pre>'; print_r($arr); exit;
        return $arr;
    }

}

/* if ( ! function_exists('admin_menu_groups')){
  function admin_menu_groups() {
  $arr = array(
  1=>'Website Management',
  2=>'Products Management',
  3=>'SEO Management',
  5=>'System Keys',
  6=>'Forms Submissions',
  7=>'Users Management',
  );
  $multi_languages = getonecell('settings', 'multi_languages', array('id_settings' => 1));
  if($multi_languages == 1)
  $arr[4] = 'Languages';

  return $arr;
  }
  } */

if (!function_exists('get_max_id')) {

    function get_max_id($table) {
        $d = get_instance()->db->query('SELECT MAX(id_' . $table . ') AS id FROM ' . $table . '')->row_array();
        return $d['id'] + 1;
    }

}

if (!function_exists('has_sub_levels')) {

    function has_sub_levels($table, $id) {
        $d = get_instance()->db->query('SELECT id_' . $table . ' FROM ' . $table . ' WHERE id_parent = ' . $id)->num_rows();
        if ($d > 0)
            return true;
        else
            return false;
    }

}

if (!function_exists('user_ip')) {

    function user_ip() {
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}



if (!function_exists('permissions')) {

    function permissions() {
        return array('view', 'edit', 'create', 'delete', 'translate');
    }

}

if (!function_exists('clean_url')) {

    function clean_url($vp_string, $replace = array(), $delimiter = '-') {
        /* if( !empty($replace) ) {
          $str = str_replace((array)$replace, ' ', $str);
          }

          $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
          $clean = preg_replace(":/[^a-zA-Z0-9/_|+ -]/:", '', $clean);
          $clean = strtolower(trim($clean, '-'));
          $clean = preg_replace(":/[/_|+ -]+/:", $delimiter, $clean); */

        /* $vp_string = trim($vp_string);

          $vp_string = html_entity_decode($vp_string);

          $vp_string = strip_tags($vp_string);

          $vp_string = strtolower($vp_string);

          $vp_string = preg_replace('~[^ a-z0-9_.]~', ' ', $vp_string);

          $vp_string = preg_replace('~ ~', '-', $vp_string);

          $vp_string = preg_replace('~-+~', '-', $vp_string);

          return $vp_string;

          //return $clean; */
        $url = $vp_string;
        # Prep string with some basic normalization
        $url = strtolower($url);
        $url = strip_tags($url);
        $url = stripslashes($url);
        $url = html_entity_decode($url);

        # Remove quotes (can't, etc.)
        $url = str_replace('\'', '', $url);

        # Replace non-alpha numeric with hyphens
        $match = '/[^a-z0-9]+/';
        $replace = '-';
        $url = preg_replace($match, $replace, $url);

        $url = trim($url, '-');

        return $url;
    }

}

if (!function_exists('display_dateformat')) {

    function display_dateformat() {
        $CI = & get_instance();
        return $CI->config->item('display_date_format');
    }

}

if (!function_exists('dateformat')) {

    function dateformat($date, $format = 'Y-m-d') {
        if ($date != '' && $date != '0000-00-00' && $date != '0000-00-00 00:00:00')
            return date($format, strtotime($date));
        return '-';
    }

}