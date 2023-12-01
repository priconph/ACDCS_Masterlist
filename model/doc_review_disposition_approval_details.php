<?php
namespace Model;
use ACDCS;
class DocReviewDispositionApprovalDetails{
    public function getDocReviewDispositionById($doc_review_disposition_approval_details_id){
        require_once('../class/oop_acdcs.php');
		$request = $_POST;
		$array_fields = array('disposition.tbl_doc_review_masterlist_id');
		$table 	   	= 'tbl_doc_review_disposition_approval_details approval';
		$joins 	   	= 'RIGHT JOIN tbl_doc_review_disposition disposition ON disposition.id = approval.tbl_doc_review_disposition_id';
		$sql_where 	= 'WHERE approval.id = '.$doc_review_disposition_approval_details_id.' AND approval.deleted_at IS NULL AND disposition.deleted_at IS NULL';
		$sql_order 	= '';
		$sql_limit 	= '';
		$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($query)){
			$request['tbl_doc_review_masterlist_id'] 	= $row['tbl_doc_review_masterlist_id'];
		}
		return $request;
    }

    // public function DocReviewDispositionApprovalDetailsById($doc_review_disposition_id){
    //     require_once('../class/oop_acdcs.php');
	// 	$request = $_POST;
	// 	$array_fields = array('*');
	// 	$table 	   	= 'tbl_doc_review_disposition_approval_details';
	// 	$joins 	   	= '';
	// 	$sql_where 	= 'WHERE tbl_doc_review_disposition_id = '.$doc_review_disposition_id.' AND deleted_at IS NULL';
	// 	$sql_order 	= '';
	// 	$sql_limit 	= '';
	// 	$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	// 	$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	// 	while($row = mysqli_fetch_array($query)){
	// 		$request['tbl_doc_review_masterlist_id'] 	= $row['tbl_doc_review_masterlist_id'];
	// 	}
	// 	return $request;
    // }
}


?>