<?php
$oop 		= '../../class/oop_acdcs.php';
if(file_exists($oop)){
	require_once($oop);
}else{
	echo 'oop not found!';
	exit;
}

$systemone_model 		= '../../model/systemone.php';
if(file_exists($systemone_model)){
	require_once($systemone_model);
}else{
	echo 'oop not found!';
	exit;
}
/* Do excel here  */

$excel_class = '../../class/excel_new.php';
if(file_exists($excel_class)){
	include($excel_class);
}else{
	echo 'File '.$excel_class.' does not exist';
	exit;
}
/*
	Model
*/
use Model\Hris;

$query = $_GET['query']; //query from 

$excel = new EXCEL;
/* set title */
$excel->title = 'Advanced Search Results';
/* add a new page */
$excel->add_page();
// $excel->set_page_orientation_a4_landscape();
/* use the default font style */
$excel->set_default_font_style();
/* select an active page */
$excel->set_active_sheet(0);

// $excel->set_margin();
// $excel->set_print_area();

$width_allowance_custom = 28;
$width_allowance_custom_1 = 10;

/* set width */
$excel->set_width('B',$width_allowance_custom_1);
$excel->set_width('C',$width_allowance_custom+5);
$excel->set_width('D',$width_allowance_custom_1+5);
$excel->set_width('E',$width_allowance_custom+20);
$excel->set_width('F',$width_allowance_custom_1);
$excel->set_width('G',$width_allowance_custom_1);
$excel->set_width('H',$width_allowance_custom_1);
$excel->set_width('I',$width_allowance_custom_1);
$excel->set_width('J',$width_allowance_custom+10);
$excel->set_width('K',$width_allowance_custom);

$excel->place_value('B1','Date Received','string');
$excel->place_value('C1','Model','string');
$excel->place_value('D1','Originator Code','string');
$excel->place_value('E1','Title','string');
$excel->place_value('F1','Document No.','string');
$excel->place_value('G1','Rev No.','string');
$excel->place_value('H1','Expiration','string');
$excel->place_value('I1','Disposition','string');
$excel->place_value('J1','Reviewed by/ Reviewed Date','string');
$excel->place_value('K1','DCC Email Date','string');

$row_cnt = 2;
$script = ACDCS::getInstance()->export_advance_search_result_script($query);
$result = ACDCS::getInstance()->export_advance_search_result($query);
echo json_encode($result);
while($row = mysqli_fetch_array($result)){
	// echo ($row['doc_no'] != "")? $row['doc_no'] : $row['obsolete_doc_no'];
	
	$excel->place_value('B'.$row_cnt,( $row['date_received'] != "" )? $row['date_received'] : $row['obsolete_date_received'],'string');
	$excel->place_value('C'.$row_cnt,( $row['model'] != "" ) ? $row['model'] : $row['obsolete_model'],'string');
	$excel->place_value('D'.$row_cnt,( $row['originator_code'] != "" ) ? $row['originator_code'] : $row['obsolete_originator_code'],'string');
	$excel->place_value('E'.$row_cnt,( $row['doc_title'] != "" ) ? $row['doc_title'] : $row['obsolete_doc_title'],'string');
	$excel->place_value('F'.$row_cnt,( $row['doc_no'] != "" ) ? $row['doc_no'] : $row['obsolete_doc_no'],'string');
	$excel->place_value('G'.$row_cnt,( $row['rev_no'] != "" )? $row['rev_no'] : $row['obsolete_rev_no'],'string');
	$excel->place_value('H'.$row_cnt,( $row['expiration_date'] != "" )? $row['expiration_date'] : $row['obsolete_expiration_date'],'string');
	switch ($row['disposition']) {
		case 1:
			$doc_review_disposition = 'Revision';
			break;
		case 2:
			$doc_review_disposition = 'Retain';
			break;
		case 3:
			$doc_review_disposition = 'Obsolete';
			break;
		case 4:
			$doc_review_disposition = 'Extended';
			break;
		default:
			$doc_review_disposition = '';
			break;
	}
	$excel->place_value('I'.$row_cnt,$doc_review_disposition,'string');
	/*
	reviewed_by
	*/
	$date = date_create($row['created_at']);
	$reviewed_by = Hris::get_emp_name_by_username_systemone($row['reviewed_by']);

	$excel->place_value('J'.$row_cnt,$reviewed_by.'/'.date_format($date,'m-d-Y').'','string');
	$excel->place_value('K'.$row_cnt,date('Y-m-d', strtotime($row['dcc_email'])),'string');
	$row_cnt++;
}
// exit;
/* set file name */
$filename = "Advanced Search Results";
/* output excel - filename, excel version (2003,2007) */
$excel->output($filename,'2007');
/*

SELECT SQL_CALC_FOUND_ROWS disposition.disposition, masterlist.id, masterlist.fkid_document,masterlist.document_status,masterlist.is_expiration_date_exist, masterlist.deleted_at,active.date_received, active.model, active.doc_title,active.doc_no, active.rev_no,masterlist.originator_code,obsolete.doc_no AS obsolete_doc_no,obsolete.date_received AS obsolete_date_received, obsolete.model AS obsolete_model,obsolete.doc_title AS obsolete_doc_title,obsolete.doc_no AS obsolete_doc_no, obsolete.rev_no AS obsolete_rev_no,active.expiration_date AS active_expiration_date,obsolete.expiration_date AS obsolete_expiration_date FROM tbl_doc_review_masterlist masterlist
LEFT JOIN tbl_active_docs active ON active.fkid_document = masterlist.fkid_document
LEFT JOIN tbl_obsolete_docs obsolete ON obsolete.fkid_document = masterlist.fkid_document
LEFT JOIN tbl_doc_review_disposition disposition ON disposition.tbl_doc_review_masterlist_id = masterlist.id
WHERE (masterlist.`deleted_at` IS NULL AND masterlist.document_status >= 3)
AND (disposition.disposition IS NOT NULL AND disposition.deleted_at IS NULL)
AND (disposition.disposition IS NOT NULL AND disposition.deleted_at IS NULL)
AND (
( active.`view_status` =1 AND active.`logdel` =0 )
AND ((active.`expiration_date` BETWEEN '2023-12-01' AND '2023-12-31') OR (active.`expiration_date1` BETWEEN '2023-12-01' AND '2023-12-31') OR (active.`expiration_date2` BETWEEN '2023-12-01' AND '2023-12-31') OR (active.`expiration_date3` BETWEEN '2023-12-01' AND '2023-12-31'))
)
ORDER BY masterlist.id DESC ;

*/
?>