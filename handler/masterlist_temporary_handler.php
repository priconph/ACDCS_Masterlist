<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../model/send_email.php');
require_once('../model/doc_review_disposition_approval_details.php');
require_once('../model/doc_review_disposition.php');
require_once('../model/active_document.php');
require_once('./common_function_handler.php');

use Model\EmailDetail;
use Model\RapidEmailer;
use Model\DocReviewDispositionApprovalDetails;
use Model\DocReviewDisposition;
use Model\ActiveDocument;
use Handler\DocuReviewDisposition;

	if(is_ajax()) {
		if(isset($_POST["action"]) && !empty($_POST["action"])) {
			$action = $_POST["action"];
			switch($action) {	
				case "read_temporary_review_docu" 					: read_temporary_review_docu(); break; 
				case "create_disposition_details" 					: create_disposition_details(); break; 
				case "edit_disposition_details" 					: edit_disposition_details(); break; 
				case "save_approver_decision" 						: save_approver_decision(); break; 
				case "read_doc_review_masterlist" 					: read_doc_review_masterlist(); break; 
				case "advanced_search" 								: advanced_search(); break; 
				case "dcc_review_again" 							: dcc_review_again(); break; 
			}	
		}
	}

	function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	/*
		TODO: Enable the Email to PO and Approvers | FIND: EmailDetail:: Mailer::getInstance()
	*/
	
	function create_disposition_details()
	{
		try{
			
			require_once('../class/oop_acdcs.php');
			
			/* Class List */
			$common_class = new CommonClass;

			$return = $_POST ;
			$response = array();
			$username = $_SESSION['username'];
			$disposition = $return['disposition'];
			$sec_heads_username = $return['sec_heads_username'];
			$datetime_today = date('Y-m-d H:i:s');
			$doc_review_masterlist_id = $return['doc_review_masterlist_id'];
			
			$doc_review_masterlist = $common_class->docReviewMasterlist($doc_review_masterlist_id);
			$dcc_emailed_at = $doc_review_masterlist['updated_at']; //NOTE: When DCC Email the For Review Document
			$new_status = 2;
			$table = 'tbl_doc_review_disposition';
			/* Get the document disposition then delete the previous disposition to count as review history */
			$get_doc_review_disposition_descending_id =  DocReviewDisposition::getDocReviewDispositionDescending($doc_review_masterlist_id);
			if( $get_doc_review_disposition_descending_id['is_exist'] == 1 ){
				$array_fields = array( 'deleted_at' ,'review_count');
				$array_values = array( date('Y-m-d H:i:s') , 1);
				$where = 'WHERE id = '.$get_doc_review_disposition_descending_id['id'].'';
				$query = ACDCS::getInstance()->update_query($table,$array_fields,$array_values,$where);
			}
			if($disposition == 1){ //Revision
				$array_fields = array('tbl_doc_review_masterlist_id','disposition','person_in_charge','due_date','reviewed_by','dcc_email','created_at');
				$array_values = array($doc_review_masterlist_id,$disposition,$return['person_in_charge'],$return['due_date'],$username,$dcc_emailed_at,$datetime_today);
				$docu_review_disposition_id = ACDCS::getInstance()->insert_query_id($table,$array_fields,$array_values);

				/* Scheduled email to Process owner */
				$scheduled_email[] = $common_class->sendScheduledEmailToProcessOwner($docu_review_disposition_id,$return['person_in_charge'],$return['due_date'],$username);
				
				/* Save Approval Details	and Send Email to Approvers */
				$email[] = $common_class->createApprovalDetails($docu_review_disposition_id,$sec_heads_username,$doc_review_masterlist_id);

				/* Change status*/
				$change_status[]= $common_class->changeStatusByDocReviewMasterlistById($doc_review_masterlist_id,$new_status);

				$response['is_success'] = 'true';
				$response['result_msg'] =  'Save Successfully';
				echo json_encode($response);
				return;
			}
			if($disposition == 2 || $disposition == 3 ){ //Retain & Obsolete
				$array_fields = array('tbl_doc_review_masterlist_id','disposition','reviewed_by','dcc_email','created_at');
				$array_values = array($doc_review_masterlist_id,$disposition,$username,$dcc_emailed_at,$datetime_today);
				$docu_review_disposition_id = ACDCS::getInstance()->insert_query_id($table,$array_fields,$array_values); 

				/* Approval Details	and Send Email to Approvers */
				$email[] = $common_class->createApprovalDetails($docu_review_disposition_id,$sec_heads_username,$doc_review_masterlist_id);
				/* Change status*/
				$change_status[]= $common_class->changeStatusByDocReviewMasterlistById($doc_review_masterlist_id,$new_status);

				$response['is_success'] = 'true';
				$response['result_msg'] =  'Save Successfully';
				echo json_encode($response);
				return;
			}
			if($disposition == 4){//Extended
				$fkid_document =  $doc_review_masterlist['fkid_document'];
				$active_docs = ActiveDocument::activeDocsByFkid($fkid_document);
				$date_difference = date_diff(date_create($return['extension_date']),date_create($active_docs['effectivity_date'])); //check the difference between ext date & effectivity date
				$month = $date_difference->format("%m"); //get the month only
				$year = $date_difference->format("%y");//get the year if the month is >= 12

 				// NOTE: Make every special character into underscore ( _ ) to locate the filename prevent problem in downloading of file 			
				$file_memo_tmp = $_FILES['file_memo']['tmp_name'];
				$file_memo_name = $_FILES['file_memo']['name'];				//'/etc#hosts/@Álix Ãxel likes - beer?!.pdf';
				$filtered_filename = '_'.Slug($file_memo_name, '_', '.');	 // _etc_hosts_alix_axel_likes_beer.pdf
 				
				// NOTE: Check if the file uploaded, the process required to upload MEMO
				if($file_memo_tmp != ""){
					$is_file_name_existed = $common_class->selectDispositionByFilename($file_memo_name);
					if($is_file_name_existed['is_exist']=='true'){
						$response['is_success'] ='false';
						$response['result_msg'] = 'Filename is already existed!';
						echo json_encode($response);
						return;
					}
					/* saved extended disposition w/ memo file */
					$array_fields = array('tbl_doc_review_masterlist_id','disposition','memo_file_name','memo_file_filtered_name','extension_date','reviewed_by','dcc_email');
					$array_values = array($doc_review_masterlist_id,$disposition,$file_memo_name,$filtered_filename,$return['extension_date'],$username,$dcc_emailed_at);
					$docu_review_disposition_id = ACDCS::getInstance()->insert_query_id($table,$array_fields,$array_values);
					/* Uploading of Data */
					$tbl_folder_path = $common_class->folderPath('memo'); //change temporary_document to memo
					$target_file = $tbl_folder_path['folder_path'].$docu_review_disposition_id.$filtered_filename;
					$request_upload_docu = $common_class->uploadDocument($tbl_folder_path['folder_path'],$file_memo_tmp,$target_file);
					/* Approval Details	and Send Email to Approvers	*/
					$email[] = $common_class->createApprovalDetails($docu_review_disposition_id,$sec_heads_username,$doc_review_masterlist_id);
					/* Change status*/
					$change_status[]= $common_class->changeStatusByDocReviewMasterlistById($doc_review_masterlist_id,$new_status);

					$response['is_success'] = ($request_upload_docu == 1) ? 'true' : 'false';
					$response['result_msg'] = ($response['is_success'] == 'true') ? 'File uploaded successfully !': 'Uploading Failed. Please Try Again!';
					echo json_encode($response);
					return;
				}

				if($file_memo_tmp == ""){
					if($month >= 10 || $year >= 1){
						$response['is_success'] ='false';
						$response['result_msg'] = 'This document is more than 9 months. Please submit a memo!';
						echo json_encode($response);
						return;
					}
					/* saved extended disposition w/o memo file */
					$array_fields = array('tbl_doc_review_masterlist_id','disposition','extension_date','reviewed_by','dcc_email','created_at');
					$array_values = array($doc_review_masterlist_id,$disposition,$return['extension_date'],$username,$dcc_emailed_at,$datetime_today);
					$docu_review_disposition_id = ACDCS::getInstance()->insert_query_id($table,$array_fields,$array_values);
					
					/* Approval Details	and Send Email to Approvers	*/
					$email[] = $common_class->createApprovalDetails($docu_review_disposition_id,$sec_heads_username,$doc_review_masterlist_id);
					/* Change status*/
					$change_status[]= $common_class->changeStatusByDocReviewMasterlistById($doc_review_masterlist_id,$new_status);
					
					$response['is_success'] ='true';
					$response['result_msg'] = 'Save successfully !';
					echo json_encode($response);
					return;
				}
			}
		}catch(\Exception $e){
			throw $e;
		}
	}

	function edit_disposition_details()
	{
		try {
			require_once('../class/oop_acdcs.php');

			$common_class = new CommonClass;
			$return = $_POST ;
			$response = array();
			$username = $_SESSION['username'];
			$remarks = $return['remarks'];
			$disposition = $return['disposition'];
			$sec_heads_username = $return['sec_heads_username'];
			$datetime_today = date('Y-m-d H:i:s');
			$doc_review_masterlist_id = $return['doc_review_masterlist_id'];
			$docu_review_disposition_id = $return['docu_review_disposition_id'];
			
			$doc_review_masterlist = $common_class->docReviewMasterlist($doc_review_masterlist_id);
			$dcc_emailed_at = $doc_review_masterlist['created_at'];
			$new_status = 2;

			$table = 'tbl_doc_review_disposition';
			if($disposition == 1){ //Revision
				
				$array_fields = array('tbl_doc_review_masterlist_id','disposition','person_in_charge','due_date','reviewed_by','dcc_email','remarks','updated_at');
				$array_values = array($doc_review_masterlist_id,$disposition,$return['person_in_charge'],$return['due_date'],$username,$dcc_emailed_at,$remarks,$datetime_today);
				$where = 'WHERE id = '.$docu_review_disposition_id.'';
				//Before updating the disposition, clear the doc review disposition table then update the data by id
				$clear_tbl_disposition[] = $common_class->clearTblDocReviewDispositionColumn($docu_review_disposition_id);
				$update_query = ACDCS::getInstance()->update_query($table,$array_fields,$array_values,$where);
				
				/* Scheduled email to Process owner */
				$scheduled_email[] = $common_class->sendScheduledEmailToProcessOwner($docu_review_disposition_id,$return['person_in_charge'],$return['due_date'],$username);
				
				/* Save Approval Details and Send Email to Approvers */
				$email[] = $common_class->createApprovalDetails($docu_review_disposition_id,$sec_heads_username,$doc_review_masterlist_id);

				/* Change status*/
				$change_status[]= $common_class->changeStatusByDocReviewMasterlistById($doc_review_masterlist_id,$new_status);

				$response['is_success'] = 'true';
				$response['result_msg'] =  'Save Successfully';
				echo json_encode($response);
				return;
			}

			if($disposition == 2 || $disposition == 3 ){ //Retain & Obsolete
				$array_fields = array('tbl_doc_review_masterlist_id','disposition','reviewed_by','dcc_email','remarks','created_at');
				$array_values = array($doc_review_masterlist_id,$disposition,$username,$dcc_emailed_at,$remarks,$datetime_today);
				$where = 'WHERE id = '.$docu_review_disposition_id.'';
				//Before updating the disposition, clear the doc review disposition table.
				$clear_tbl_disposition[] = $common_class->clearTblDocReviewDispositionColumn($docu_review_disposition_id);
				$update_query = ACDCS::getInstance()->update_query($table,$array_fields,$array_values,$where);
				/* Approval Details	and Send Email to Approvers */
				$email[] = $common_class->createApprovalDetails($docu_review_disposition_id,$sec_heads_username,$doc_review_masterlist_id);
				/* Change status*/
				$change_status[]= $common_class->changeStatusByDocReviewMasterlistById($doc_review_masterlist_id,$new_status);

				$response['is_success'] = 'true';
				$response['result_msg'] =  'Save Successfully';
				echo json_encode($response);
				return;
			}
			
			if($disposition == 4){//Extended
				$fkid_document =  $doc_review_masterlist['fkid_document'];
				$active_docs = ActiveDocument::activeDocsByFkid($fkid_document);
				$date_difference = date_diff(date_create($return['extension_date']),date_create($active_docs['effectivity_date'])); //check the difference between ext date & effectivity date
				$month = $date_difference->format("%m"); //get the month only
				$year = $date_difference->format("%y");//get the year if the month is >= 12

 				// NOTE: Make every special character into underscore ( _ ) to locate the filename prevent problem in downloading of file 			
				$file_memo_tmp = $_FILES['file_memo']['tmp_name'];
				$file_memo_name = $_FILES['file_memo']['name'];				//'/etc#hosts/@Álix Ãxel likes - beer?!.pdf';
				$filtered_filename = '_'.Slug($file_memo_name, '_', '.');	 // _etc_hosts_alix_axel_likes_beer.pdf
 				
				// NOTE: Check if the file uploaded, the process required to upload MEMO
				//Before updating the disposition, clear the doc review disposition table then update the data by id
				$clear_tbl_disposition[] = $common_class->clearTblDocReviewDispositionColumn($docu_review_disposition_id);
				if($file_memo_tmp != ""){
					$is_file_name_existed = $common_class->selectDispositionByFilename($file_memo_name);
					if($is_file_name_existed['is_exist']=='true'){
						$response['is_success'] ='false';
						$response['result_msg'] = 'Filename is already existed!';
						echo json_encode($response);
						return;
					}
					/* saved extended disposition w/ memo file */
					$array_fields = array('tbl_doc_review_masterlist_id','disposition','memo_file_name','memo_file_filtered_name','extension_date','reviewed_by','remarks','dcc_email');
					$array_values = array($doc_review_masterlist_id,$disposition,$file_memo_name,$filtered_filename,$return['extension_date'],$username,$remarks,$dcc_emailed_at);
					$where = 'WHERE id = '.$docu_review_disposition_id.'';
					$update_query = ACDCS::getInstance()->update_query($table,$array_fields,$array_values,$where);
					/* Uploading of Data */
					$tbl_folder_path = $common_class->folderPath('memo'); //memo
					$target_file = $tbl_folder_path['folder_path'].$docu_review_disposition_id.$filtered_filename;
					$request_upload_docu = $common_class->uploadDocument($tbl_folder_path['folder_path'],$file_memo_tmp,$target_file);
					/* Approval Details	and Send Email to Approvers	*/
					$email[] = $common_class->createApprovalDetails($docu_review_disposition_id,$sec_heads_username,$doc_review_masterlist_id);
					/* Change status*/
					$change_status[]= $common_class->changeStatusByDocReviewMasterlistById($doc_review_masterlist_id,$new_status);

					$response['is_success'] = ($request_upload_docu == 1) ? 'true' : 'false';
					$response['result_msg'] = ($response['is_success'] == 'true') ? 'File uploaded successfully !': 'Uploading Failed. Please Try Again!';
					echo json_encode($response);
					return;
				}

				if($file_memo_tmp == ""){
					if($month >= 9 || $year >= 1){
						$response['is_success'] ='false';
						$response['result_msg'] = 'This document is more than 9 months. Please submit a memo!';
						echo json_encode($response);
						return;
					}
					/* saved extended disposition w/o memo file */
					$array_fields = array('tbl_doc_review_masterlist_id','disposition','extension_date','reviewed_by','dcc_email','remarks','created_at');
					$array_values = array($doc_review_masterlist_id,$disposition,$return['extension_date'],$username,$dcc_emailed_at,$remarks,$datetime_today);
					$where = 'WHERE id = '.$docu_review_disposition_id.'';
					$update_query = ACDCS::getInstance()->update_query($table,$array_fields,$array_values,$where);
					/* Approval Details	and Send Email to Approvers	*/
					$email[] = $common_class->createApprovalDetails($docu_review_disposition_id,$sec_heads_username,$doc_review_masterlist_id);
					/* Change status*/
					$change_status[]= $common_class->changeStatusByDocReviewMasterlistById($doc_review_masterlist_id,$new_status);
					
					$response['is_success'] ='true';
					$response['result_msg'] = 'Save successfully !';
					echo json_encode($response);
					return;
				}
			}
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	function save_approver_decision()
	{
		try {
			require_once('../class/oop_acdcs.php');
			$return = $_POST;
			$common_class = new CommonClass;
			if($return['is_approved'] == 'true') {
				$status = 1;
				/* Email from Approver to DCC */
				$common_class->sendEmailToDcc($return['filtered_arr_approval_id']);
			}else{
				$status = 2;
			}
			foreach ($return['filtered_arr_approval_id'] as $key => $arr_approval_id) {
				/* Deactivate the Auto Email For Process Owner */
				$common_class->deactivateAutoEmailToProcessOwner($arr_approval_id);
				$table = 'tbl_doc_review_disposition_approval_details';
				$array_fields = array('status','remarks','updated_at');
				$array_values = array($status, $return['approver_remarks'], date('Y-m-d H:i:s') );
				$where = ' WHERE id = '.$arr_approval_id.' ';
				$query = ACDCS::getInstance()->update_query($table,$array_fields,$array_values,$where);
				$doc_review_masterlist_id = DocReviewDispositionApprovalDetails::getDocReviewDispositionById($arr_approval_id);
				if($status == 1){
					$change_status[]= $common_class->changeStatusByDocReviewMasterlistById($doc_review_masterlist_id['tbl_doc_review_masterlist_id'],3);
				}else if($status == 2){
					$change_status[]= $common_class->changeStatusByDocReviewMasterlistById($doc_review_masterlist_id['tbl_doc_review_masterlist_id'],0);
					/* Email from Approver to Process Owner */
					$common_class->sendScheduledEmailApproverToProcessOwner($arr_approval_id);
				}
			}
			$response['is_success'] = 'true';
			$response['msg'] = 'Saved Successfully';
			echo json_encode($response);
		} catch (\Throwable $th) {
			throw $th;
		}
	}
	function read_doc_review_masterlist()
	{
		try {
			$get_doc_review_disposition_by_id = DocReviewDisposition::getDocReviewDispositionApprovalDetailsById($_POST['doc_review_masterlist_id']);
			echo json_encode($get_doc_review_disposition_by_id);
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	function advanced_search(){
		try {
				$return = $_POST;
				require_once('../class/oop_acdcs.php');
				$request = array();
				$array_fields = array('disposition.dcc_email,disposition.created_at,disposition.reviewed_by,disposition.disposition,masterlist.originator_code, masterlist.id, masterlist.fkid_document,masterlist.document_status,masterlist.is_expiration_date_exist, masterlist.deleted_at,active.date_received, active.model, active.doc_title,active.doc_no, active.rev_no , obsolete.doc_no AS obsolete_doc_no,obsolete.date_received AS obsolete_date_received, obsolete.model AS obsolete_model,obsolete.doc_title AS obsolete_doc_title,obsolete.doc_no AS obsolete_doc_no, obsolete.rev_no AS obsolete_rev_no,active.expiration_date,obsolete.expiration_date AS obsolete_expiration_date');
				$table 	   	 = 'tbl_doc_review_masterlist  masterlist';
				$joins 	   	 = ' ' . 'LEFT JOIN tbl_active_docs active ON active.fkid_document = masterlist.fkid_document';
				$joins 	   	.= ' ' . 'LEFT JOIN tbl_obsolete_docs obsolete ON obsolete.fkid_document = masterlist.fkid_document';
				$joins 	   	.= ' ' . 'LEFT JOIN tbl_doc_review_disposition disposition ON disposition.tbl_doc_review_masterlist_id = masterlist.id';
				/* SQL WHERE */
				$sql_where 	 = ' ' . 'WHERE ( masterlist.`deleted_at` IS NULL AND masterlist.document_status >= 2 )'; //As per DCC Export the data even the status is FOR APPROVAL/status 2
				$sql_where 	.= ' ' . 'AND ( disposition.disposition IS NOT NULL AND disposition.deleted_at IS NULL )';
				/*
				TODO: where is based on dcc email or when the dcc send the document for review,
				TODO: the expiration date is constantly changing so the dcc email date is the basis to export the data
				TODO: The dcc will export the data before and after the email sent to process owner to compare the data w/ or w/o disposition
				*/
				if($return['expiration_date_from_to'] != ''){
					$arr_expiration_date_from_to = explode('-',$return['expiration_date_from_to']);
					$arr_sql_where = ' ' . "AND disposition.`dcc_email` BETWEEN ' " .date('Y-m-d', strtotime($arr_expiration_date_from_to[0])). " ' AND ' " .date('Y-m-d', strtotime($arr_expiration_date_from_to[1])). " '";
				}
				
				/*
				!Where active exp date or obsolete exp date
					if($return['expiration_date_from_to'] != ''){
						$arr_expiration_date_from_to = explode('-',$return['expiration_date_from_to']);
						$arr_sql_where = "AND ((active.`expiration_date` BETWEEN '".date('Y-m-d', strtotime($arr_expiration_date_from_to[0]))."' AND '".date('Y-m-d', strtotime($arr_expiration_date_from_to[1]))."') OR (active.`expiration_date1` BETWEEN '".date('Y-m-d', strtotime($arr_expiration_date_from_to[0]))."' AND '".date('Y-m-d', strtotime($arr_expiration_date_from_to[1]))."') OR (active.`expiration_date2` BETWEEN '".date('Y-m-d', strtotime($arr_expiration_date_from_to[0]))."' AND '".date('Y-m-d', strtotime($arr_expiration_date_from_to[1]))."') OR (active.`expiration_date3` BETWEEN '".date('Y-m-d', strtotime($arr_expiration_date_from_to[0]))."' AND '".date('Y-m-d', strtotime($arr_expiration_date_from_to[1]))."'))";
					}
				*/
				$sql_where 	.= ' ' . ' '.$arr_sql_where.' ';
				$sql_order 	 = ' ' . 'ORDER BY masterlist.id DESC';
				$sql_limit 	 = '';
				$request['script'] = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				echo json_encode($request);
				return;
				/**
				 * TODO: For Query Review data before Exporting the data
					* $query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
					* while($row = mysqli_fetch_array($query)){
					*	$request['tbl_doc_review_masterlist_id'] 	= $row['tbl_doc_review_masterlist_id'];
					* }
				 */
				
			// return $request;
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	function dcc_review_again(){
		require_once('../class/oop_acdcs.php');
		try {
			$return = $_POST;
			foreach ($return['doc_review_masterlist_id'] as $key => $arr_masterlist_id) {
				$table = ' tbl_doc_review_masterlist';
				$array_fields = array('document_status');
				$array_values = array(5);
				$where = ' WHERE id = '.$arr_masterlist_id.' ';
				$script = ACDCS::getInstance()->update_query_script($table,$array_fields,$array_values,$where);
				$query = ACDCS::getInstance()->update_query($table,$array_fields,$array_values,$where);
			}
			$response['is_success'] = 'true';
			$response['msg'] = 'Saved Successfully';
			echo json_encode($response);
		} catch (\Throwable $th) {
			throw $th;
		}
		
	}

	function Slug($string, $slug = '-', $extra = null)
	{
		return strtolower(trim(preg_replace('~[^0-9a-z' . preg_quote($extra, '~') . ']+~i', $slug, Unaccent($string)), $slug));
	}

	function Unaccent($string) // normalizes (romanization) accented chars
	{
		if (strpos($string = htmlentities($string, ENT_QUOTES, 'UTF-8'), '&') !== false)
		{
			$string = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $string), ENT_QUOTES, 'UTF-8');
		}
		return $string;
	}

	class CommonClass{
		public function docReviewMasterlist($doc_review_masterlist_id) 
		{
			try {
				$request = array();
				$array_fields = array('*');
				$table 	   	= 'tbl_doc_review_masterlist';
				$joins 	   	= '';
				$sql_where 	= 'WHERE id = '.$doc_review_masterlist_id.'';
				$sql_order 	= '';
				$sql_limit 	= 'LIMIT 0,1';
				$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				while($row = mysqli_fetch_array($query)){
					$request['created_at'] = $row['created_at'];
					$request['updated_at'] = $row['updated_at'];
					$request['fkid_document'] = $row['fkid_document'];
				}
				return $request;
			} catch (\Throwable $th) {
				throw $th;
			}
		}

		public static function folderPath($folder_name) 
		{
			// tbl_folder_path
			// return $folder_name;
			$request = $_POST;
			$array_fields = array('folder_path');
			$table 	   	= 'tbl_folder_path';
			$joins 	   	= '';
			$sql_where 	= 'WHERE folder_name = "'.$folder_name.'"';
			$sql_order 	= '';
			$sql_limit 	= 'LIMIT 0,1';
			$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			while($row = mysqli_fetch_array($query)){
				$request['folder_path'] = $row['folder_path'];
			}
			return $request;
		}

		public static function uploadDocument($target_directory,$file_memo_tmp,$target_file)
		{
			try {
				if(file_exists($target_file)){
					return 2; //failed file is already exist
				}
				return $value = move_uploaded_file($file_memo_tmp,$target_file); //success
			} catch (\Throwable $th) {
				throw $th;
			}
		}

		public static function selectDispositionByFilename($file_name) 
		{
			try {
				$request = $_POST;
				$array_fields = array('*');
				$table 	   	= 'tbl_doc_review_disposition';
				$joins 	   	= '';
				$sql_where 	= 'WHERE memo_file_name = "'.$file_name.'"';
				$sql_order 	= '';
				$sql_limit 	= 'LIMIT 0,1';
				$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				if($row = mysqli_fetch_array($query)){
					$request['memo_file_name'] = $row['memo_file_name'];
					$request['is_exist'] = 'true';
				}else{
					$request['is_exist'] = 'false';
				}
				return $request;
			}catch (\Throwable $th) {
				//throw $th;
			}
		}

		public static function changeStatusByDocReviewMasterlistById($doc_review_masterlist_id,$new_status)
		{
			try {
				$request = $_POST;
				$table = 'tbl_doc_review_masterlist';
				$array_fields = array('document_status');
				$array_values = array($new_status);
				$sql_where = 'WHERE id = '.$doc_review_masterlist_id.' ';
				$script = ACDCS::getInstance()->update_query_script($table,$array_fields,$array_values,$sql_where);
				$query = ACDCS::getInstance()->update_query($table,$array_fields,$array_values,$sql_where);
				return $query;
			} catch (\Throwable $th) {
				throw $th;
			}
		}

		public static function createApprovalDetails($doc_review_disposition_id,$sec_heads_username,$doc_review_masterlist_id)
		{
			try {
				/* 	Select if the approver is already exist, the soft deleted the details by 
						tbl_doc_review_disposition_id column deleted_at is NULL
				*/
				$request = $_POST;
				$array_fields = array('tbl_doc_review_disposition_id');
				$table = 'tbl_doc_review_disposition_approval_details';
				$joins 	   	= '';
				$sql_where 	= 'WHERE tbl_doc_review_disposition_id = '.$doc_review_disposition_id.' AND deleted_at IS NULL' ;
				$sql_order 	= '';
				$sql_limit 	= 'LIMIT 0,1';
				$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				if($query->num_rows > 0){
					$array_fields = array('deleted_at');
					$array_values = array(date('Y-m-d H:i:s'));
					$where 	= 'WHERE tbl_doc_review_disposition_id = '.$doc_review_disposition_id.' AND deleted_at IS NULL';
					$query = ACDCS::getInstance()->update_query($table,$array_fields,$array_values,$where); 
				}
				/* Create new approval details*/
				$array_fields = array('tbl_doc_review_disposition_id','sec_heads_username','created_at');
				$array_values = array($doc_review_disposition_id,$sec_heads_username,date('Y-m-d H:i:s'));
				$query = ACDCS::getInstance()->insert_query($table,$array_fields,$array_values);
				/* Email to Approver */
				$send_email_to_approver[] = CommonClass::sendEmailToApprover($doc_review_disposition_id,$sec_heads_username,$doc_review_masterlist_id);
				return $send_email_to_approver;
			} catch (\Throwable $th) {
				throw $th;
			}
		}

		public static function sendEmailToApprover($doc_review_disposition_id,$sec_heads_username,$doc_review_masterlist_id)
		{
			try {
				require_once('../class/oop_acdcs.php');

				$get_doc_review_masterlist_id = CommonClass::docReviewMasterlist($doc_review_masterlist_id);
				$active_docs = ActiveDocument::activeDocsByFkid($get_doc_review_masterlist_id['fkid_document']);
				$get_doc_review_disposition_by_id = DocReviewDisposition::getDocReviewDisposition($doc_review_disposition_id);
				$get_disposition_code = DocReviewDisposition::getDispositionCode($get_doc_review_disposition_by_id['disposition']);
				
				// $from = 'cbretusto@pricon.ph';
				// $to = 'mclegaspi@pricon.ph'; 
				// $cc = '';
				$from 	 = RapidEmailer::get_user_email_add_by_username($_SESSION['username']);
				$to 	 = RapidEmailer::get_user_email_add_by_username($sec_heads_username); 
				$cc 	 = RapidEmailer::get_user_email_add_by_username($_SESSION['username']);;
				$subject = 'FOR APPROVAL: ACDCS Document Review'.' | Doc No. : '.$active_docs['doc_no'].' ';
				$message = 'Please be informed that you have ACDCS Document Review for approval <br><br>';
				$message .= 'Request Details	: <br>';
				$message .= 'Disposition		: '.$get_disposition_code.' '.'<br>';
				$message .= 'Date Received		: '.$active_docs['date_received'].'<br>';
				$message .= 'Model				: '.$active_docs['model'].' <br>';
				$message .= 'Originator			: '.$active_docs['originator_code'].'<br>';
				$message .= 'Title				: '.$active_docs['doc_title'].'<br>';
				$message .= 'Document No. 		: '.$active_docs['doc_no'].' <br>';
				$message .= 'Rev No. 			: '.$active_docs['rev_no'].'<br>';
				// $message .= 'Expiration Date 	: '.$active_docs['expiration_date'].'<br>';
				$message .= 'Due Date 			: '.$get_doc_review_disposition_by_id['due_date'].'<br>';
				return EmailDetail::send_email($to, $from, $cc, $subject, $message);
				// print_r($message);
			} catch (\Throwable $th) {
				throw $th;
			}
		}

		public static function sendScheduledEmailToProcessOwner($docu_review_disposition_id,$person_in_charge,$due_date,$username)
		{
			try {
				require_once('../class/oop_acdcs.php');
				$getDocReviewDisposition =  DocReviewDisposition::getDocReviewDisposition($docu_review_disposition_id);
				$get_doc_review_masterlist_id = CommonClass::docReviewMasterlist($getDocReviewDisposition['tbl_doc_review_masterlist_id']);
				$active_docs = ActiveDocument::activeDocsByFkid($get_doc_review_masterlist_id['fkid_document']);
				$email_due_date = date( "Y-m-d", strtotime( "-5 days", strtotime($due_date) ) );
				$tbl_name = 'tbl_doc_review_disposition';
				// $to = 'mclegaspi@pricon.ph';
				// $from = 'cdcasuyon@pricon.ph';
				// $cc = '';
				$to = RapidEmailer::get_user_email_add_by_username($person_in_charge);
				$from = 'issinfoservice@pricon.ph';
				$cc = RapidEmailer::get_user_email_add_by_username($username);
				$subject = 'FOR REVISION: ACDCS Document Review Processs'.' | Doc No. : '.$active_docs['doc_no'].' ';
				$message = 'Please be informed that you have ACDCS Document Review for revision. <br><br>';
				$message .= 'Request Details	: <br>';
				$message .= 'Date Received		: '.$active_docs['date_received'].'<br>';
				$message .= 'Model				: '.$active_docs['model'].' <br>';
				$message .= 'Originator			: '.$active_docs['originator_code'].'<br>';
				$message .= 'Title				: '.$active_docs['doc_title'].'<br>';
				$message .= 'Document No. 		: '.$active_docs['doc_no'].' <br>';
				$message .= 'Rev No. 			: '.$active_docs['rev_no'].'<br>';
				$message .= 'Expiration Date 	: '.$active_docs['expiration_date'].'<br>';
				$date_start = $email_due_date;
				$frequency = 2;
				// return EmailDetail::send_scheduled_email($tbl_name,$docu_review_disposition_id, $to, $from, $cc, $subject, $message , $date_start, $frequency);
			} catch (\Throwable $th) {
				throw $th;
			}
		}
		public static function sendScheduledEmailApproverToProcessOwner($approval_id)
		{
			try {
				require_once('../class/oop_acdcs.php');
				$active_docs = DocReviewDispositionApprovalDetails::getActiveDocDetailsById($approval_id);
				
				// $from = 'cbretusto@pricon.ph';
				// $to = 'mclegaspi@pricon.ph'; 
				// $cc = '';
				
				$from 	 = RapidEmailer::get_user_email_add_by_username($active_docs['sec_heads_username']);
				$to 	 = RapidEmailer::get_user_email_add_by_username($active_docs['reviewed_by']); 
				$cc 	 = RapidEmailer::get_user_email_add_by_username($active_docs['sec_heads_username']);
				$subject = 'DISAPPROVED: ACDCS Document Review'.' | Doc No. : '.$active_docs['doc_no'].' ';
				$message = 'Please be informed that you have ACDCS Document Review has been disapproved <br><br>';
				$message .= 'Request Details	: <br>';
				$message .= 'Disposition		: '.DocReviewDisposition::getDispositionCode($active_docs['disposition']).' '.'<br>';
				$message .= 'Date Received		: '.$active_docs['date_received'].'<br>';
				$message .= 'Model				: '.$active_docs['model'].' <br>';
				$message .= 'Originator			: '.$active_docs['originator_code'].'<br>';
				$message .= 'Title				: '.$active_docs['doc_title'].'<br>';
				$message .= 'Document No. 		: '.$active_docs['doc_no'].' <br>';
				$message .= 'Rev No. 			: '.$active_docs['rev_no'].'<br>';
				$message .= 'Expiration Date 	: '.$active_docs['expiration_date'].'<br>';
				return EmailDetail::send_email($to, $from, $cc, $subject, $message);
				// print_r($message);
			} catch (\Throwable $th) {
				throw $th;
			}
		}
		public static function sendEmailToDcc($filtered_arr_approval_id)
		{
			try {
				$document_details = '';
				$document_details .= '<table class="table table-bordered">';
				$document_details .= '	<thead>';
				$document_details .= '		<tr>';
				$document_details .= '			<th>Item</th>';
				$document_details .= '			<th>Date Received</th>';
				$document_details .= '			<th>Model</th>';
				$document_details .= '			<th>Originator</th>';
				$document_details .= '			<th>Title</th>';
				$document_details .= '			<th>Document No.</th>';
				$document_details .= '			<th>Rev No. </th>';
				$document_details .= '			<th>Expiration</th>';
				$document_details .= '			<th>Document Type</th>';
				$document_details .= '			<th>Disposition</th>';
				$document_details .= '		</tr>';
				$document_details .= '	</thead>';
				$document_details .= '	<tbody>';
				$arr_active_docs = DocReviewDispositionApprovalDetails::getActiveDocById($filtered_arr_approval_id);
				foreach ($arr_active_docs['tbl_doc_review_masterlist_id'] as $key => $active_fkid_document) {	
					$document_details .= '	<tr>';
					$document_details .= '		<td> '.($key+1).' </td>';
					$document_details .= '		<td>'.$arr_active_docs['date_received'][$key].'</td>';
					$document_details .= '		<td>'.$arr_active_docs['model'][$key].'</td>';
					$document_details .= '		<td>'.$arr_active_docs['originator_code'][$key].'</td>';
					$document_details .= '		<td>'.$arr_active_docs['doc_title'][$key].'</td>';
					$document_details .= '		<td>'.$arr_active_docs['doc_no'][$key].'</td>';
					$document_details .= '		<td>'.$arr_active_docs['rev_no'][$key].'</td>';
					$document_details .= '		<td>'.$arr_active_docs['expiration_date'][$key].'</td>';
					$document_details .= '		<td>'.$arr_active_docs['doc_type'][$key].'</td>';
					$document_details .= '		<td> <i style="background:yellow;"> '.DocReviewDisposition::getDispositionCode($arr_active_docs['disposition'][$key]).' </i> </td>';
					$document_details .= '	</tr>';

				}
				$document_details .= '	</tbody>';
				$document_details .= '<table>';

				// $to			= 	implode(',',$return['send_to']);
				// $from		=	this_get_emp_name_by_username($return['username']);
				// $cc			=	implode(',',$return['send_cc']);
				
				/* message: TEST EMAIL*/
				$to			= 	'dmmarmol@pricon.ph,';
				$from		=	'issinfoservice@pricon.ph';
				$cc			=	'';
				$subject	=	"For DCC Conformance";
				return EmailDetail::send_email_with_table($to, $from, $cc, $subject, $document_details);
				// print_r($document_details);
			} catch (\Throwable $th) {
				throw $th;
			}
		}

		public static function clearTblDocReviewDispositionColumn($docu_review_disposition_id){
			try {
				$request = array();
				$table = 'tbl_doc_review_disposition';
				$array_fields = array('person_in_charge','due_date','memo_file_name','memo_file_filtered_name','extension_date','remarks');
				$array_values = array(NULL,NULL,NULL,NULL,NULL,NULL);
				$where = 'WHERE id = '.$docu_review_disposition_id.'';
				$update_query = ACDCS::getInstance()->update_query($table,$array_fields,$array_values,$where); 
			} catch (\Throwable $th) {
				throw $th;
			}
		}

		public function deactivateAutoEmailToProcessOwner($docu_review_disposition_id){
			try {
				require_once('../class/oop_mailer.php');
				$table = 'tbl_auto_mailer';
				$array_fields = array('logdel');
				$array_values = array(1);
				$where = 'WHERE fkid = '.$docu_review_disposition_id.' AND system_name = "ACDCS_DRM"';
				$query = Mailer::getInstance()->update_query_script($table,$array_fields,$array_values,$where);
				return $query = Mailer::getInstance()->update_query($table,$array_fields,$array_values,$where);
			} catch (\Throwable $th) {
				throw $th;
			}
		}
	}

?>