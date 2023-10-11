<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../handler/common_function.php');
$excel_class = '../class/excel_new.php';
if(file_exists($excel_class)){
	include($excel_class);
}else{
	echo 'File '.$excel_class.' does not exist';
	exit;
}
/** 
 * *LIST OF FUNCTIONS*
 * !get_file_path_by_pkid
 * !get_control_number
 * !get_originator_esignature
 * !get_y_coordinate_esignature
 * !get_x_coordinate_esignature
 * !get_checker_esignature
 */

$pkid 		= trim($_GET['id'],' ');
$checker_esignature = get_checker_esignature($pkid);
$originator_esignature 			= get_originator_esignature($pkid);
$ctrl_no						= get_control_number($pkid);
$file	 						= get_file_path_by_pkid($pkid);
$file_path 						= $file['file_path'];
$file 							= $file_path . 'e_'.$pkid . '.xls'; 
$file_x 						= $file_path . 'e_'.$pkid . '.xlsx'; 

/* 
$y_coordinate_esignature = get_y_coordinate_esignature($pkid);
$x_coordinate_esignature = get_x_coordinate_esignature($pkid);
$originator_esignature_count 	= get_originator_esignature_count($pkid);
$originator_esignature_second 	= get_originator_esignature_second($pkid); */

require_once('../class/oop_tqts.php'); //NOTE : fsignature get the signature of every approver according to the EMPLOYEE NUMBER
$return 		= $_POST;
$result 		= '';
$array_fields 	= array('approver_username,y_coordinate,x_coordinate,order_id');
$table 	   		= 'tbl_qfr_sa_main_approvers';
$joins 	   		= '';
$sql_where 		= 'WHERE `fkid` = "'.$pkid.'" AND `logdel` = 0 AND `status` = 1';
$sql_order 		= 'ORDER BY order_id';
$sql_limit 		= '';
$result 		= TQTS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
$file_ctrl 		= array();


if (file_exists($file)) {
	$file_name 	= "Special Acceptance.xlsx";
	$file = $file_path . 'e_'.$pkid . '.xls'; 
	
	// Load an existing spreadsheet
	$phpExcel = PHPExcel_IOFactory::load($file);
	// Get the first sheet
	$sheet = $phpExcel ->getActiveSheet();
	// Insert one new row before row 2
	$sheet ->getCell('X2')->setValue($ctrl_no);

	/* if the prepared by == 2, 2 prepared by else 1 prepared by */
	
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('image');
		$objDrawing->setDescription('image');
		$objDrawing->setPath($originator_esignature);
		$objDrawing->setWidthAndHeight(90,90);
		$objDrawing->setResizeProportional(true);
		$objDrawing->setWorksheet($phpExcel->getActiveSheet());
		$objDrawing->setCoordinates('AA'.'7');

		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('image');
		$objDrawing->setDescription('image');
		$objDrawing->setPath($checker_esignature);
		$objDrawing->setWidthAndHeight(90,90);
		$objDrawing->setResizeProportional(true);
		$objDrawing->setWorksheet($phpExcel->getActiveSheet());
		$objDrawing->setCoordinates('AA'.'9');

	while($row = mysqli_fetch_assoc($result)){
	
		$file_ctrl = $row ['approver_username'];
		$column = $row ['y_coordinate'];
		$row = $row ['x_coordinate'];
		$get_esignature = return_esignature_sar($file_ctrl);

		// Create the PHPExcel spreadsheet writer object
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('image');
		$objDrawing->setDescription('image');
		$objDrawing->setPath($get_esignature);
		$objDrawing->setWidthAndHeight(90,90);
		$objDrawing->setResizeProportional(true);
		$objDrawing->setWorksheet($phpExcel->getActiveSheet());
		$objDrawing->setCoordinates($column.$row);
	}
	// Create the PHPExcel spreadsheet writer object
	$writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");

	/* Redirect to browser (download) instead of saving the result to file */
	/* Only for xls format */
	ob_end_clean();
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
	header('Cache-Control: max-age=0');
	// Save the spreadsheet
	$writer->save('php://output');
    exit;
} else if(file_exists($file_x)) {
	/* MODIFY THE CONTROL NUMBER AND RETAIN IMAGE */
	$file_name 	= 'Special Acceptance.xlsx';
	// $file_x 	= $file_path . 'e_'.$pkid . '.xlsx'; 
	// Load an existing spreadsheet
 	$phpExcel = PHPExcel_IOFactory::load($file_x);
	// Get the first sheet
	$sheet = $phpExcel ->getActiveSheet(0);
	// Insert setValue 
	$sheet ->getCell('X2')->setValue($ctrl_no);

	/* if the prepared by == 2, 2 prepared by else 1 prepared by */

	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('image');
	$objDrawing->setDescription('image');
	$objDrawing->setPath($originator_esignature);
	$objDrawing->setWidthAndHeight(90,90);
	$objDrawing->setResizeProportional(true);
	$objDrawing->setWorksheet($phpExcel->getActiveSheet());
	$objDrawing->setCoordinates('AA'.'7');

	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('image');
	$objDrawing->setDescription('image');
	$objDrawing->setPath($checker_esignature);
	$objDrawing->setWidthAndHeight(90,90);
	$objDrawing->setResizeProportional(true);
	$objDrawing->setWorksheet($phpExcel->getActiveSheet());
	$objDrawing->setCoordinates('AA'.'9');
	while($row = mysqli_fetch_assoc($result)){
		
		$file_ctrl = $row ['approver_username'];
		$column = $row ['y_coordinate'];
		$row = $row ['x_coordinate'];
		$get_esignature = return_esignature_sar($file_ctrl);

		// Create the PHPExcel spreadsheet writer object
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('image');
		$objDrawing->setDescription('image');
		$objDrawing->setPath($get_esignature);
		$objDrawing->setWidthAndHeight(90,90);
		$objDrawing->setResizeProportional(true);
		$objDrawing->setWorksheet($phpExcel->getActiveSheet());
		$objDrawing->setCoordinates($column.$row);
	}
	$writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");

	/* Redirect to browser (download) instead of saving the result to file */
	/* Only for xlsx format */
	ob_end_clean();
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
	header('Cache-Control: max-age=0');

	// Save the spreadsheet
	$writer->save('php://output');
    // readfile($file_x);
    exit;
} else {
	echo 'File not found!';
}


function get_file_path_by_pkid($fkdetails) {
	require_once('../class/oop_tqts.php');
	$result 		= '';
	$array_fields = array('path.file_path');
	$table 	   	= 'tbl_qfr_special_acceptance_attachment details ';
	$joins 	   	= 'INNER JOIN tbl_file_path path ON path.pkid = details.fkfile_path';
	$sql_where 	= 'WHERE details.fkspecial_acceptance="'.$fkdetails.'" AND details.logdel=0';
	$sql_order 	= '';
	$sql_limit 	= 'LIMIT 0,1';
	$file 		= array();
	$result = TQTS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	// echo TQTS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	if($row = mysqli_fetch_array($result)){
		$file['file_path'] = $row['file_path'];
	}
	return $file;
}
function get_control_number($fkdetails){
	require_once('../class/oop_tqts.php');
	$result_w1 		= '';
	$array_fields = array('control_number');
	$table 	   	= 'tbl_qfr_special_acceptance';
	$joins 	   	= '';
	$sql_where 	= 'WHERE pkid="'.$fkdetails.'" AND logdel=0';
	$sql_order 	= '';
	$sql_limit 	= 'LIMIT 0,1';
	$file_ctrl 	= array();
	$result_w1 = TQTS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	echo TQTS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	if($row = mysqli_fetch_array($result_w1)){
		$file_ctrl = $row;
	}
	$get_ctrl_no = $file_ctrl['control_number'];
	return $get_ctrl_no;
}
function get_originator_esignature($pkid){ //NOTE : fsignature originator
	require_once('../class/oop_tqts.php');
	$return 		= $_POST;
	$result 		= '';
	$array_fields 	= array('created_by');
	$table 	   		= 'tbl_qfr_special_acceptance';
	$joins 	   		= '';
	$sql_where 	= 'WHERE pkid="'.$pkid.'" AND logdel=0';
	$sql_order 		= '';
	$sql_limit 		= '';
	$result 		= TQTS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	$get_esignature = '';
	while($row = mysqli_fetch_array($result)){
		$file_ctrl = $row ['created_by'];
		$get_esignature = return_esignature_sar($file_ctrl);
	}
	return $get_esignature;
}

function get_y_coordinate_esignature($pkid){ //NOTE : fsignature originator
	require_once('../class/oop_tqts.php'); //NOTE : fsignature get the signature of every approver according to the EMPLOYEE NUMBER
	$return 		= $_POST;
	$result 		= '';
	$array_fields 	= array('y_coordinate,x_coordinate');
	$table 	   		= 'tbl_qfr_sa_main_approvers';
	$joins 	   		= '';
	$sql_where 		= 'WHERE `fkid` = "'.$pkid.'" AND `logdel` = 0 AND `status` = 1';
	$sql_order 		= 'ORDER BY `fkid` DESC';
	$sql_limit 		= '';
	$result 		= TQTS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	$esignature 	= array();
	while($row = mysqli_fetch_array($result)){
		$esignature = $row['y_coordinate'];
	}
	return $esignature;
}
function get_x_coordinate_esignature($pkid){ //NOTE : fsignature originator
	require_once('../class/oop_tqts.php'); //NOTE : fsignature get the signature of every approver according to the EMPLOYEE NUMBER
	$return 		= $_POST;
	$result 		= '';
	$array_fields 	= array('y_coordinate,x_coordinate');
	$table 	   		= 'tbl_qfr_sa_main_approvers';
	$joins 	   		= '';
	$sql_where 		= 'WHERE `fkid` = "'.$pkid.'" AND `logdel` = 0 AND `status` = 1';
	$sql_order 		= 'ORDER BY `fkid` DESC';
	$sql_limit 		= '';
	$result 		= TQTS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	$esignature 	= array();
	while($row = mysqli_fetch_array($result)){
		$esignature = $row['x_coordinate'];
	}
	return $esignature;
}
function get_checker_esignature($pkid){ //NOTE : fsignature originator
	require_once('../class/oop_tqts.php'); //NOTE : fsignature get the signature of every approver according to the EMPLOYEE NUMBER
	$return 		= $_POST;
	$result 		= '';
	$array_fields 	= array('approver_username');
	$table 	   		= 'tbl_qfr_sa_approvers';
	$joins 	   		= '';
	$sql_where 		= 'WHERE `fkid` = "'.$pkid.'" AND `logdel` = 0 AND `status` = 1';
	$sql_order 		= '';
	$sql_limit 		= '';
	$result 		= TQTS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	$file_ctrl 		= array();
	while($row = mysqli_fetch_assoc($result)){
		$file_ctrl = $row ['approver_username'];
		$get_esignature = return_esignature_sar($file_ctrl);
	}
	return $get_esignature;
}