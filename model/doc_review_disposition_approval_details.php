<?php
namespace Model;
use ACDCS;
class DocReviewDispositionApprovalDetails{
    public function getDocReviewDispositionById($doc_review_disposition_approval_details_id){
        require_once('../class/oop_acdcs.php');
		$request = $_POST;
		$array_fields = array('disposition.tbl_doc_review_masterlist_id,disposition.disposition');
		$table 	   	= 'tbl_doc_review_disposition_approval_details approval';
		$joins 	   	= 'RIGHT JOIN tbl_doc_review_disposition disposition ON disposition.id = approval.tbl_doc_review_disposition_id';
		$sql_where 	= 'WHERE approval.id = '.$doc_review_disposition_approval_details_id.' AND approval.deleted_at IS NULL AND disposition.deleted_at IS NULL';
		$sql_order 	= '';
		$sql_limit 	= '';
		$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($query)){
			$request['tbl_doc_review_masterlist_id'] 	= $row['tbl_doc_review_masterlist_id'];
			$request['disposition'] 					= $row['disposition'];
		}
		return $request;
    }
    public function getActiveDocById($doc_review_disposition_approval_details_id){
        require_once('../class/oop_acdcs.php');
		$request = $_POST;
		$array_fields = array('masterlist.*,disposition.*,active.*,approval.*');
		$table 	   	= 'tbl_doc_review_disposition_approval_details approval';
		$joins 	   	= 'RIGHT JOIN tbl_doc_review_disposition disposition ON disposition.id = approval.tbl_doc_review_disposition_id';
		$joins 	   	.= ' RIGHT JOIN tbl_doc_review_masterlist masterlist ON  masterlist.id = disposition.tbl_doc_review_masterlist_id';
		$joins 	   	.= ' RIGHT JOIN tbl_active_docs active ON  active.fkid_document = masterlist.fkid_document';
		
		$arr_sql_or = array();
		foreach ($doc_review_disposition_approval_details_id as $key => $value) {
			$sql_where 	= 'WHERE approval.id = '.$doc_review_disposition_approval_details_id[0].'';
			$arr_sql_or[] = ' approval.id = '.$doc_review_disposition_approval_details_id[$key].'';
		}
		if(count($doc_review_disposition_approval_details_id) > 1){
			$sql_or = implode(' OR',$arr_sql_or);
			$sql_where 	= 'WHERE '.$sql_or.'';
		}
		$sql_where .= ' AND approval.deleted_at IS NULL AND disposition.deleted_at IS NULL';
		// $sql_where 	= 'WHERE approval.id = '.$doc_review_disposition_approval_details_id.' AND approval.deleted_at IS NULL AND disposition.deleted_at IS NULL';
		$sql_order 	= '';
		$sql_limit 	= '';
		$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);

		while($row = mysqli_fetch_array($query)){
			$request['tbl_doc_review_masterlist_id'][] 	= $row['tbl_doc_review_masterlist_id'];
			$request['sec_heads_username'][]			= $row['sec_heads_username'];
			$request['status'][] 						= $row['status'];
			$request['disposition'][] 					= $row['disposition'];
			$request['date_received'][] 				= $row['date_received'];
			$request['model'][] 						= $row['model'];
			$request['originator_code'][] 				= $row['originator_code'];
			$request['doc_title'][] 					= $row['doc_title'];
			$request['doc_no'][]						= $row['doc_no'];
			$request['rev_no'][] 						= $row['rev_no'];
			$request['expiration_date'][] 				= $row['expiration_date'];
			$request['doc_type'][] 						= $row['doc_type'];

		}
		return $request;
    }

    public function getActiveDocDetailsById($doc_review_disposition_approval_details_id){
        require_once('../class/oop_acdcs.php');
		$request = $_POST;
		$array_fields = array('masterlist.*,disposition.*,active.*,approval.*');
		$table 	   	= 'tbl_doc_review_disposition_approval_details approval';
		$joins 	   	= 'RIGHT JOIN tbl_doc_review_disposition disposition ON disposition.id = approval.tbl_doc_review_disposition_id';
		$joins 	   	.= ' RIGHT JOIN tbl_doc_review_masterlist masterlist ON  masterlist.id = disposition.tbl_doc_review_masterlist_id';
		$joins 	   	.= ' RIGHT JOIN tbl_active_docs active ON  active.fkid_document = masterlist.fkid_document';
		$sql_where 	= 'WHERE approval.id = '.$doc_review_disposition_approval_details_id.' AND approval.deleted_at IS NULL AND disposition.deleted_at IS NULL';
		$sql_order 	= '';
		$sql_limit 	= '';
		$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($query)){
			$request['tbl_doc_review_masterlist_id'] 	= $row['tbl_doc_review_masterlist_id'];
			$request['sec_heads_username']			= $row['sec_heads_username'];
			$request['status'] 						= $row['status'];
			$request['disposition'] 					= $row['disposition'];
			$request['date_received'] 				= $row['date_received'];
			$request['model'] 						= $row['model'];
			$request['originator_code'] 				= $row['originator_code'];
			$request['doc_title'] 					= $row['doc_title'];
			$request['doc_no']						= $row['doc_no'];
			$request['rev_no'] 						= $row['rev_no'];
			$request['expiration_date'] 				= $row['expiration_date'];
			$request['doc_type'] 						= $row['doc_type'];

		}
		return $request;
    }
}


?>