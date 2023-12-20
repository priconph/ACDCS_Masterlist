<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use Model\FolderPath;
use Model\DocReviewDisposition;
require_once('../../model/folder_path.php');
require_once('../../model/doc_review_disposition.php');

require_once('../../class/oop_acdcs.php');


/* Download the Default Excel from Directory */
$doc_review_disposition_id 		= trim($_GET['doc_review_disposition_id'],' ');
$folder_path = FolderPath::folderPath('memo'); //change temporary_document to memo
$get_doc_review_disposition 					= DocReviewDisposition::getDocReviewDisposition($doc_review_disposition_id);

$memo_file_name = $get_doc_review_disposition['memo_file_name'];
$ext= pathinfo($memo_file_name, PATHINFO_EXTENSION);

// echo $memo_file_path = '../'.$folder_path['folder_path'].$get_doc_review_disposition['memo_file_filtered_name'].'.'.$ext;
// exit;
$memo_file_path = '../'.$folder_path['folder_path'].$get_doc_review_disposition['memo_file_filtered_name'].'.'.$ext;
if (file_exists($memo_file_path)) {
	/* Downloading of PDF */
	// header('Content-Description: File Transfer');
    // header('Content-Type: application/octet-stream');
    // header("Content-Type: application/force-download");
    // header('Content-Disposition: attachment; filename=' . urlencode(basename($memo_file_name)));
    // // header('Content-Transfer-Encoding: binary');
    // header('Expires: 0');
    // header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    // header('Pragma: public');
    // header('Content-Length: ' . filesize($memo_file_path));
    // ob_clean();
    // readfile($memo_file_path);

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

