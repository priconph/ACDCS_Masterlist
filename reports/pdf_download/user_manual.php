<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$memo_file_name = "ACDCS Document Review Process User Manual";
$memo_file_path = './acdcs_drp_user_manual.pdf';
if (file_exists($memo_file_path)) {
	/* Viewing of PDF */
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="' . $memo_file_name . '"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	//Read the file
	@readfile($memo_file_path);
    exit;
}else{
	echo 'File Not Exist';
	exit;
}

