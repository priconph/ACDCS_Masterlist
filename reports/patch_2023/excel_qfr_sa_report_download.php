<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pkid 		= trim($_GET['id'],' ');
$file	 	= get_file_path_by_pkid($pkid);
$file_path 	= $file['file_path'];

$file = $file_path . 'e_'.$pkid . '.xls'; 
echo $file;
if (file_exists($file)) {
	$file_name 	= "Special Acceptance.xls";
	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
	ob_clean();
    readfile($file);
    exit;
} else if(file_exists($file_path . 'e_'.$pkid . '.xlsx')) {
	$file_name 	= "Special Acceptance.xlsx";
	$file = $file_path . 'e_'.$pkid . '.xlsx'; 
	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
	ob_clean();
    readfile($file);
    exit;
} else {
	echo 'File not found!';
}

	

function get_file_path_by_pkid($fkdetails) {
	require_once('../class/oop_tqts.php');
	$array_fields = array('path.file_path');
	$table 	   	= 'tbl_qfr_special_acceptance_attachment details ';
	$joins 	   	= 'INNER JOIN tbl_file_path path ON path.pkid = details.fkfile_path';
	$sql_where 	= 'WHERE details.pkid="'.$fkdetails.'" AND details.logdel=0';
	$sql_order 	= '';
	$sql_limit 	= 'LIMIT 0,1';
	$file 		= array();
	$result = TQTS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	echo TQTS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	if($row = mysqli_fetch_array($result)){
		$file['file_path'] = $row['file_path'];
	}
	return $file;
}

/* Code ni Buds */
// $file 		= $_GET['file_path'];
// if(!file_exists($file)){
	// echo '['.$file.'] file does not exist!';
	// exit;
// }
// $filename 	= "Special Acceptance.xlsx";
// header('Content-Description: File Transfer');
// header('Content-Type: application/octet-stream');
// header('Content-Disposition: attachment; filename="'.basename($filename).'"');
// header('Expires: 0');
// header('Cache-Control: must-revalidate');
// header('Pragma: public');
// header('Content-Length: ' . filesize($file));
// ob_clean();
// readfile($file);
// exit;
?>