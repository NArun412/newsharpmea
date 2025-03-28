<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Form_validation Class
 *
 * Extends Form_Validation library
 *
 * Adds one validation rule, "unique" and accepts a
 * parameter, the name of the table and column that
 * you are checking, specified in the forum table.column
 *
 * Note that this update should be used with the
 * form_validation library introduced in CI 1.7.0
 */
class MY_Form_validation extends CI_Form_validation {

	function __construct()
	{
	    parent::__construct();
	}

	// --------------------------------------------------------------------
	/**
	 * Unique
	 *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
function unique($str, $field)
{
$CI =& get_instance();
list($table, $column ,$iddd) = explode('.', $field, 3);
$CI->form_validation->set_message('unique', '%s '.lang('Exists'));
$q= "SELECT COUNT(*) AS dupe FROM $table WHERE $column = '$str'";
if($iddd != "")
$q.= " AND id_$table != $iddd";
$query = $CI->db->query($q);
$row = $query->row();
return ($row->dupe > 0) ? FALSE : TRUE;
}

function valid_url($str){
$strPattern = "/^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?/i";
if(preg_match($strPattern, $str))
{
 return true;
} else
 return false;
}

}
?>