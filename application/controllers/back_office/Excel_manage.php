<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Excel_manage extends MY_Controller {

public function __construct()
{
parent::__construct();
$this->limit = 100;
$this->admin_dir = 'back_office';
$this->load->model($this->admin_dir."/export_model");
//$this->load->model($this->admin_dir."/import_model");
$this->template->set_template("admin");
$this->default_dir = './libraries/export_files';
$this->default_lang = default_lang();
$this->force_exported = array('confirmed_downloads','contact_form','newsletter_subscribers','careers_applications');
$this->static_fields = array('translation_id','status','title','lang');
}

function export($module)
{
	$data = array();
	$data['title'] = 'Export Module';
	
	$data['export_fields'] = $this->export_model->get_export_fields_by_module($module);
	
	$data['module'] = $module;
	$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$this->session->set_userdata("admin_redirect_link",$url);
	$this->template->add_js('assets/js/custom/excel_manage.js');
	
	$this->template->write_view('header','back_office/includes/header',$data);
	//$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
	$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
	$this->template->write_view('content','back_office/excel_manage/export_template',$data);
	$this->template->render();
}
function do_export()
{
//if($this->acl->has_permission($section,'export')) {
$section = $this->input->post('module');

$limit = $this->limit;
if(isset($_POST['offset'])) {
$offset = $_POST['offset'];

$cond = array();
$like = array();

if(in_array($section,$this->force_exported))
$cond["exported"] = 0;

//print_r($cond);exit;

$filters = $this->fct->get_module_fields($section,FALSE,TRUE);

if($this->input->post("title") != "") {
	$like["title"] = $this->input->post("title");
}
if($this->input->post("status") != "") {
	$cond["status"] = $this->input->post("status");
}
/*if(has_parent_foreign($section)) {
$cond["id_parent"] = 0;
}*/
foreach($filters as $filter) {
	$attr_name = str_replace(" ","_",$filter["name"]);
		if($filter["type"] == 2) {
			if($this->input->post($attr_name) != "") {
				$like[$attr_name] = $this->input->post($attr_name);
			}
		}
		else {
			if($filter["type"] == 7) {
				$f_table = get_foreign_table($filter['foreign_key']);
				$var = intval($filter['id_content']) - intval($filter['foreign_key']);
				if($var == 0) {
					if($this->input->post("id_parent") != "") {
						$cond["id_parent"] = $this->input->post("id_parent");
					}
				}
				else {
					if($this->input->post("id_".$f_table) != "") {
						$cond["id_".$f_table] = $this->input->post("id_".$f_table);
					}
				}
			}
			else {
				if($this->input->post($attr_name) != "") {
					$cond[$attr_name] = $this->input->post($attr_name);
				}
			}
		}
}

$cc= $this->export_model->getCount($section,$cond,$like);

if($offset == 0 && $cc == 0) {
	$return = array(
		'result'=>0,
		'msg'=>'No Records Found.....'
	);

}
else {
	$data = $this->export_model->getRecords($section,$cond,$like,$limit,$offset);
if(empty($data['arr'])) {
	$return = array(
		'result'=>1,
		'msg'=>'System is downloading your file..',
		'download_url'=>admin_url('excel_manage/download/'.$section)
	);
}
else {
	$this->generate_excel($section,$data['title'],$data['keys'],$data['arr']);
	$in_progress = $offset + $this->limit;
	if($in_progress > $cc) $in_progress = $cc;
	$return = array(
		'result'=>2,
		'new_offset'=>$in_progress,
		'msg'=>'System is working on '.$in_progress.' out of '.$cc.' record(s)'
	);
}
}
}
else {
	$return = array(
		'result'=>0,
		'msg'=>'No records found...'
	);
}
$this->load->view('json',array('return'=>$return));
}
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
private function generate_excel($section,$title,$keys,$arr)
{
	$filename = str_replace(' ','-',$title).'.xlsx';
	
	$session_id = session_id();
	$session_sid = $this->session->userdata("sid_".$section);

	if($session_sid == "") {
		$session_sid = $session_id."_".$section;
		$this->session->set_userdata("sid_".$section,$session_sid);
	}

	require_once './libraries/Excel/Classes/PHPExcel.php';
	$objPHPExcel = new PHPExcel();
	
	//$wizard = new PHPExcel_Helper_HTML();
	
	//print '<pre>'; print_r($wizard); exit;

	if(isset($arr['style']) && !empty($arr['style']))
	$styleArray1 = $arr['style'];
	$styleArray1['font']= array(
			'bold' => TRUE,
			'size'  => 11,
			'name'  => 'Verdana',
			'color' => array('rgb' => '000000'),
	);
	$styleArray1['alignment']= array(
			'wrap'       => true,
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
	);
	
	$styleArray2['font']= array(
			'bold' => FALSE,
			'size'  => 8,
			'name'  => 'Verdana',
			'color' => array('rgb' => '000000'),
	);
	$styleArray2['alignment']= array(
			'wrap'       => true,
			//'vAlign' => 'vtop',
			//'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			//'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
	);
	$start_count = 2;
	$dir = $this->default_dir.'/'.$this->session->userdata("sid_".$section);
	
	$file_exits = FALSE;
	if(file_exists($dir.'/'.$filename)) {
		$file_exits = TRUE;
		$objPHPExcel = PHPExcel_IOFactory::load($dir.'/'.$filename);
		$start_count = $objPHPExcel->getActiveSheet()->getHighestRow();
	}
	if(!$file_exits) {
	  $sheet = $objPHPExcel->getActiveSheet();
	  // set main title in sheet
	  $sheet->setCellValueByColumnAndRow(0, 1, $title);
	  $sheet->mergeCells('A1:'.$this->toAlpha(count($keys)).'1');
	  $sheet->getStyle('A1')->applyFromArray($styleArray1);
	  $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
						   ->setLastModifiedBy("Maarten Balliauw")
						   ->setTitle("Office 2007 XLSX Test Document")
						   ->setSubject("Office 2007 XLSX Test Document")
						   ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
						   ->setKeywords("office 2007 openxml php")
						   ->setCategory("Test result file");
	  // set headers
	  $in=0;foreach($keys as $key => $val) {$in++;
		  //$objPHPExcel->setActiveSheetIndex(0)->setCellValue($this->toAlpha($in).'2', '="'.$val.'"');
		  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($this->toAlpha($in).'2',$val);
	  }
	  
	  // set sheet title
	  $objPHPExcel->getActiveSheet()->setTitle($title);
	  // update headers styles
	  $in=0;foreach($keys as $key => $val) {$in++;
		  $objPHPExcel->getActiveSheet()->getStyle($this->toAlpha($in).'2')->applyFromArray($styleArray1);
		  //$objPHPExcel->getActiveSheet()->getStyle($this->toAlpha($in).'2'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
		  //$objPHPExcel->getActiveSheet()->getColumnDimension($this->toAlpha($in))->setAutoSize(true);
		  //$calculatedWidth = $sheet->getColumnDimension($i)->getWidth();
		  $objPHPExcel->getActiveSheet()->getColumnDimension($this->toAlpha($in))->setWidth(30);
		  
	  }
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	}
	
	// append data
	$i=$start_count;
	foreach($arr as $key_1 => $val_1) {
		$i++;
		$j=0;
		foreach($val_1 as $key_2 => $val_2) {$j++;
			//$val_2 = $wizard->toRichTextObject($val_2);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($this->toAlpha($j).''.$i.'', $val_2);
			$objPHPExcel->getActiveSheet()->getStyle($this->toAlpha($j).''.$i.'')->applyFromArray($styleArray2);
			//$objPHPExcel->setActiveSheetIndex(0)->setCellValue($this->toAlpha($j).''.$i.'', '="'.$val_2.'"');
		}
		//$objPHPExcel->getActiveSheet()->getRowDimension($i)->setVisible(false);
		//$objPHPExcel->getActiveSheet()->getColumnDimension($i)->setHeight(10);
		$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(40);
	}
	// end append data
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	// Redirect output to a client's web browser (Excel5)
	//$step = ($this->input->post('offset') / $this->limit) + 1;
	//$filename = str_replace(' ','-',$title).'-'.$this->input->post('offset').'.xlsx';
	
	
	//$this->db->insert('excel_files',array('name'=>$filename,'session_id'=>$this->session->userdata("sid_".$section)));
	/*header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
	header('Cache-Control: max-age=0');*/
	
	//exit($this->default_dir);
	if(!is_dir($this->default_dir)) {
		if (!mkdir($this->default_dir, 0755, true)) {
			die('Failed to create folders...');
		}
	}
	
	$structure = $this->default_dir.'/'.$session_sid;
	if(!is_dir($structure)) {
	if (!mkdir($structure, 0755, true)) {
		die('Failed to create folders...');
	}
	}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

	$objWriter->save($this->default_dir.'/'.$session_sid.'/'.$filename);
}
//////////////////////////////////////////////////////////////////////////////////////////////
public function download($section)
{
 //$this->load->library('zip');
 $this->load->helper('download');
 $dir = $this->default_dir.'/'.$this->session->userdata("sid_".$section);
 if(is_dir($dir)) {
 $files = scandir($dir.'/');

 array_shift($files); 
 array_shift($files);
 //print '<pre>';print_r($files);exit;
 if(!empty($files)) {
	 $filename = $files[0];
	 if($filename != '.' && $filename != '..') {
		// echo $filename;exit;
		 $filedata = file_get_contents($dir.'/'.$filename);
		 $this->deleteDirectory($dir);
		 force_download($filename,$filedata); 
		/* foreach($files as $file) {
			$path = $dir.'/'.$file;
			$this->zip->read_file($path,false);
			unlink($dir.'/'.$file);
		 }
		 $this->deleteDirectory($dir);
		 $this->zip->download('download.zip');*/
	 }
 }
 else {
	$this->session->set_userdata("error_message","No records found...");
	redirect($this->session->userdata('admin_redirect_link'));
 }
 }
 else {
	$this->session->set_userdata("error_message","No records found...");
	redirect($this->session->userdata('admin_redirect_link'));
 }

}
function kill_export_process()
{
	$module = $this->input->post('module');
	$dir = $this->default_dir.'/'.$this->session->userdata("sid_".$module);
	$this->deleteDirectory($dir);
	
	$return = array(
		'result'=>1,
		'msg'=>'Export Cancelled...'
	);
	$this->load->view('json',array('return'=>$return));
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////

// IMPORT /////////////////////////////////////////////////////////////////////////////////////////////////////////
function import($module)
{
	$data = array();
	$data['title'] = 'Import Module';
	
	$data['import_fields'] = $this->export_model->get_export_fields_by_module($module);
	
	$data['module'] = $module;
	$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$this->session->set_userdata("admin_redirect_link",$url);
	$this->template->add_js('assets/js/custom/excel_manage.js');
	
	$this->template->write_view('header','back_office/includes/header',$data);
	//$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
	$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
	$this->template->write_view('content','back_office/excel_manage/import_template',$data);
	$this->template->render();
}
public function do_import()
{
	$section = $this->input->post('module');

	$limit = $this->limit;
	$offset = 0;
	if(isset($_POST['offset']))
	$offset = $_POST['offset'];
	
	// upload file into server if not uploaded
	if($this->input->post('filename') == '' || $this->input->post('dirname') == '') {
		$input_name = 'upload_file';
		$sess = $this->session->userdata("imp_ses123");
		if($sess == "") {
			$dir_name = md5(time().rand()).'_import';
			$this->session->set_userdata("imp_ses123",$dir_name);
		}
		else {
			$dir_name = $sess;
		}
		if(!is_dir($this->default_dir)) {
			if (!mkdir($this->default_dir, 0755, true)) {
				die('Failed to create folders...');
			}
		}
		$structure = $this->default_dir.'/'.$dir_name;
		if(!is_dir($structure)) {
			if (!mkdir($structure, 0755, true)) {
				die('Failed to create folders...');
			}
		}
		//print '<pre>'; print_r($_FILES); exit;
		$config = array();
		$config['allowed_types'] = 'xlsx';
		$config['upload_path'] =$structure;
		$config['max_size'] = '18000';
		$this->load->library('upload',$config);
		if(!$this->upload->do_upload($input_name,true)){
			$this->upload_err = $this->upload->display_errors();
			$return = array(
				'result'=>0,
				'msg'=>$this->upload_err
			);
			$this->load->view('json',array('return'=>$return));
			die;
		}
		else{
			$path = $this->upload->data();
			$file_name = $path['file_name'];
		} 
	}
	else {
		$file_name = $this->input->post('filename');
		$dir_name = $this->input->post('dirname');
		$structure = $this->default_dir.'/'.$dir_name;
	}
	// end upload file
	
	// load data
	require_once './libraries/Excel/Classes/PHPExcel.php';
	$objPHPExcel = new PHPExcel();
	$filePath = $structure."/".$file_name;
	$objReader = PHPExcel_IOFactory::createReaderForFile($filePath);
	//$objReader->setLoadSheetsOnly(array($sheet_name));
	$objReader->setReadDataOnly(true);
	$objPHPExcel = $objReader->load($filePath);
	
	$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
	//exit($highestRow);
	$countRows = $highestRow - 2;
	
	if($countRows == 0) {
		$return = array(
			'result'=>0,
			'msg'=>'No records found...'
		);
		$this->load->view('json',array('return'=>$return));
		die;
	}
	if($countRows <= $offset) {
		
		if($section == 'static_words') {
			$this->fct->update_translations();
		}
	
		$dir = $this->default_dir.'/'.$dir_name;
		$this->deleteDirectory($dir);
	
		$return = array(
			'result'=>1,
			'msg'=>'Import done successfully, please wait while system reload the page...'
		);
		$this->load->view('json',array('return'=>$return));
		die;
	}
	
	$highestColumn = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
	//$excel_data = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
	$get_offset = $offset + 3;
	$get_limit = $offset + 2 + $this->limit;
	if($get_limit > $highestRow)
	$get_limit = $highestRow;
	$excel_data = $objPHPExcel->getActiveSheet()->rangeToArray('A'.$get_offset.':'.$highestColumn.$get_limit, NULL, true, true);
	// end get data
	
	// do import
	$fields = $this->export_model->get_export_fields_by_module($section,FALSE,TRUE);
	$static_fields = $this->static_fields;
	array_push($static_fields,'id_'.$section);
	//print '<pre>'; print_r($fields); exit;
	$main_model_load = $section.'_m';
	$this->load->model($main_model_load);
	foreach($excel_data as $record) {
		$import_data = array();
		$recordid = $record[0];
		$translation_id = $record[1];
		$lang = $record[2];
		$title = $record[3];
		$status = $record[4];
		$start = 0;
		if($lang == 'ar') {
			foreach($fields as $fld) {
				$fldname = str_replace(' ','_',$fld['name']);
				//$pos = $this->toAlpha($start);
				$pos = $start;
				if(!in_array($fldname,$static_fields)) {
					if($fld['type'] == 7) {
						$f_table = get_foreign_table($fld['foreign_key']);
						$tt = get_foreign_table($fld['id_content']);
						if($f_table == str_replace(' ','_',$tt))
						$fldname = 'id_parent';
						else
						$fldname = 'id_'.$f_table;
						$import_data[$fldname] = 0;
						if($record[$pos] == 0 || $record[$pos] == '0') {
							$import_data[$fldname] = $record[$pos];
						}
						elseif(is_numeric($record[$pos])) {
							$import_data[$fldname] = $record[$pos];
						}
						else {
							if($record[$pos] != '' && $this->default_lang == $lang) {
								$model_name = $f_table.'_m';
								$this->load->model($model_name);
								$check = $this->db->where(array('title'=>$record[$pos],'lang'=>$lang))->get($f_table)->row_array();
								if(empty($check)) {
									$import_data[$fldname] = $this->$model_name->insert_update(array(
										'title'=>$record[$pos],
										'lang'=>$lang,
										'status'=>$status
									));
								}
								else {
									//$import_data[$fldname] = $check[$fldname];
									$import_data[$fldname] = 0;
									if(isset($check[$fldname]))
									$import_data[$fldname] = $check[$fldname];
								}
							}
						}
					}
					else {
						$import_data[$fldname] = '';
						if(isset($record[$pos]))
						$import_data[$fldname] = $record[$pos];
					}
				}
				$start++;
			}
			
			$type = 'insert';
			if($recordid != 0) {
				$check = $this->db->where('id_'.$section,$recordid)->get($section)->num_rows();
				if($check == 0) {
					$import_data['title'] = $title;
					$import_data['translation_id'] = $translation_id;
					$import_data['lang'] = $lang;
					$import_data['status'] = $status;
					$import_data['created_on'] = date("Y-m-d H:i:s"); 
				}
				else {
					$type = 'update';
					$import_data['title'] = $title;	
					$import_data['translation_id'] = $translation_id;
					$import_data['status'] = $status;	
				}
			}
			else {
				$import_data['title'] = $title;
				$import_data['translation_id'] = $translation_id;
				$import_data['lang'] = $lang;
				$import_data['status'] = $status;
				$import_data['created_on'] = date("Y-m-d H:i:s"); 
			}
			if($type == 'insert') {
				$this->db->insert($section,$import_data);
				$this->db->insert($section.'_review',$import_data);
				$insert_id = $this->db->insert_id();
				$this->fct->insert_log("create",$section,$insert_id);
			}
			else {
				$this->db->where('id_'.$section,$recordid)->update($section,$import_data);
				//echo $this->db->last_query().'<br />';
				if($this->db->affected_rows() > 0)
				$this->fct->insert_log("update",$section,$recordid);
				$this->db->where('id_'.$section,$recordid)->update($section.'_review',$import_data);
			}
			//$this->$main_model_load->insert_update($import_data,$recordid);
		}
	}
	// exit;
	// end do import
	$new_offset = $offset + $this->limit;
	
	// re-call function
	$return = array(
		'result'=>2,
		'msg'=>'System imported '.$new_offset.' out of '.$countRows,
		'new_offset'=>$new_offset,
		'filename'=>$file_name,
		'dirname'=>$dir_name
	);
	$this->load->view('json',array('return'=>$return));
	die;
}
function kill_import_process()
{
	$module = $this->input->post('module');
	$dir_name = $this->session->userdata("imp_ses123");
	$dir = $this->default_dir.'/'.$dir_name;
	$this->deleteDirectory($dir);
	
	$return = array(
		'result'=>1,
		'msg'=>'Import Cancelled...'
	);
	$this->load->view('json',array('return'=>$return));
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
private function toAlpha($n,$case = 'upper') {
    $alphabet   = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    if($n <= 26){
        $alpha =  $alphabet[$n-1];
    } elseif($n > 26) {
        $dividend   = ($n);
        $alpha      = '';
        $modulo;
        while($dividend > 0){
            $modulo     = ($dividend - 1) % 26;
            $alpha      = $alphabet[$modulo].$alpha;
            $dividend   = floor((($dividend - $modulo) / 26));
        }
    }
    if($case=='lower'){
        $alpha = strtolower($alpha);
    }
    return $alpha;

}
private function deleteDirectory($dir){
	if (!file_exists($dir)) return true;
	if (!is_dir($dir)) return unlink($dir);
	foreach (scandir($dir) as $item) {
		if ($item == '.' || $item == '..') continue;
		if (!self::deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
	}
	return rmdir($dir);
}
}