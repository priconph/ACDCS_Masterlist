<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    /* Download the Default Excel from Directory */
$pkid 		= trim($_GET['id'],' ');
$folder 	= trim($_GET['folder'],' ');
/* File path*/
$file	 	= get_file_path_by_pkid($pkid);
$file_others = get_other_file_path_by_pkid($pkid);
$file_path 			= $file['file_path'];
$file_path_others 	= $file_others['file_path'];
/* File Location */
$file 			= $file_path . 'e_'.$pkid . '.xls'; 
$file_x 		= $file_path . 'e_'.$pkid . '.xlsx'; 
$file_other 	= $file_path_others . 'e_'.$pkid . '.xls'; 
$file_other_x 	= $file_path_others . 'e_'.$pkid . '.xlsx'; 
/* File Names */
$get_file_name 	= fn_get_file_name($pkid);
$get_other_file_name 	= fn_get_other_file_name($pkid);
$new_file_name 	= $get_file_name['file_name'];
$new_other_file_name 	= $get_other_file_name['file_name'];


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
function get_other_file_path_by_pkid($fkdetails) {
	require_once('../class/oop_tqts.php');
	$result 		= '';
	$array_fields = array('path.file_path');
	$table 	   	= 'tbl_qfr_sa_other_attachment details ';
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
function fn_get_file_name($pkid){
	require_once('../class/oop_tqts.php'); //NOTE : fsignature get the signature of every approver according to the EMPLOYEE NUMBER
	$return 		= $_POST;
	$result 		= '';
	$array_fields 	= array('file_name');
	$table 	   		= 'tbl_qfr_special_acceptance_attachment';
	$joins 	   		= '';
	$sql_where 		= 'WHERE `fkspecial_acceptance` = "'.$pkid.'" AND `logdel` = 0';
	$sql_order 		= '';
	$sql_limit 		= '';
	$result 	= TQTS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	if($row = mysqli_fetch_assoc($result)){
		$return['file_name'] = $row ['file_name'];
	}else{
		$return['file_name'] = 'Special Acceptance';
	}
	return $return;
}
function fn_get_other_file_name($pkid){
	require_once('../class/oop_tqts.php'); //NOTE : fsignature get the signature of every approver according to the EMPLOYEE NUMBER
	$return 		= $_POST;
	$result 		= '';
	$array_fields 	= array('file_name');
	$table 	   		= 'tbl_qfr_sa_other_attachment
	';
	$joins 	   		= '';
	$sql_where 		= 'WHERE `fkspecial_acceptance` = "'.$pkid.'" AND `logdel` = 0';
	$sql_order 		= '';
	$sql_limit 		= '';
	$result 	= TQTS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	if($row = mysqli_fetch_assoc($result)){
		$return['file_name'] = $row ['file_name'];
	}else{
		$return['file_name'] = 'Special Acceptance';
	}
	return $return;
}

/** Get the folder name then download excel based on the folder path*/
if($folder=="sa"){
	if (file_exists($file)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.basename($new_file_name).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		ob_end_clean();
		readfile($file);
	}else if(file_exists($file_x)){
		header('Content-Description: File Transfer');
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.basename($new_file_name).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file_x));
		ob_end_clean();
		readfile($file_x);
	}else{
		echo '<br>File Not Exist';
	}
}else if($folder=="other_file"){
	if (file_exists($file_other)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.basename($new_other_file_name).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file_other));
		ob_end_clean();
		readfile($file_other);
	}else if(file_exists($file_other_x)){
		header('Content-Description: File Transfer');
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.basename($new_other_file_name).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file_other_x));
		ob_end_clean();
		readfile($file_other_x);
	}else{
		echo 'File Not Exist';
	}
}else{
	echo "<br>Folder Not Exist!";
}