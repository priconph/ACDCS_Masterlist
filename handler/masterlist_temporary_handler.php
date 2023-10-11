<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	

    // public function select_document_active_per_document($field_name,$field_value,$search_index) {
	// 		$query = "SELECT active.*,docs.pkid AS doc_pkid,docs.file_type, docs.doc_status, docs.fk_csaf,set_docs.control_number,set_docs.checksheet_type,set_docs.final_signatory_status,docs.section,`docs`.`dcs_remarks`,`docs`.`ta_remarks`,`docs`.`csaf_remarks`,`docs`.`fs_remarks`,`docs`.`dcc_approver_remarks`,`docs`.`checksheet_remarks`,`docs`.`translator_remarks`,val.pkid AS val_pkid, `active`.`page_no` FROM `tbl_active_docs` active LEFT JOIN tbl_documents docs ON docs.pkid = active.fkid_document LEFT JOIN tbl_set_documents set_docs ON set_docs.pkid = docs.fk_control_number LEFT JOIN tbl_dcc_validations val ON val.fkid = docs.pkid WHERE active.".$field_name." LIKE '%".$field_value."%' AND active.view_status= 1 AND active.`logdel`=0 AND docs.doc_status=1 ORDER BY `active`.`doc_no` DESC LIMIT ".$search_index.",10";
	// 		$select_query = $this->query($query);
	// 		$return = array();
	// 		$return['query'] = $query;
	// 		$return['sql_object'] = $select_query;
	// 		return $return;
	// 	}
?>


