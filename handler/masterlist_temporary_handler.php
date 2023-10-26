<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../class/send_email.php');
use Email\EmailDetail as EmailDetail;

	class CommonClass{

		public static function docReviewMasterlist($doc_review_masterlist_id) 
		{
			require_once('../class/oop_acdcs.php');
			$request = $_POST;
			$array_fields = array('created_at,fkid_document');
			$table 	   	= 'tbl_doc_review_masterlist';
			$joins 	   	= '';
			$sql_where 	= 'WHERE id = '.$doc_review_masterlist_id.'';
			$sql_order 	= '';
			$sql_limit 	= 'LIMIT 0,1';
			$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			while($row = mysqli_fetch_array($query)){
				$request['created_at'] = $row['created_at'];
				$request['fkid_document'] = $row['fkid_document'];
			}
			return $request;
		}

		public static function activeDocs($fkid_document) 
		{
			require_once('../class/oop_acdcs.php');
			$request = $_POST;
			$array_fields = array('expiration_date');
			$table 	   	= 'tbl_active_docs';
			$joins 	   	= '';
			$sql_where 	= 'WHERE fkid_document = '.$fkid_document.'';
			$sql_order 	= '';
			$sql_limit 	= 'LIMIT 0,1';
			$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			while($row = mysqli_fetch_array($query)){
				$request['expiration_date'] = $row['expiration_date'];
			}
			return $request;
		}

		public static function folderPath($folder_name) 
		{
			// tbl_folder_path
			require_once('../class/oop_acdcs.php');
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
			if(file_exists($target_file)){
				return 2; //failed file is already exist
			}
			return $value = move_uploaded_file($file_memo_tmp,$target_file); //success
		}

		public static function selectDispositionByFilename($file_name) 
		{
			require_once('../class/oop_acdcs.php');
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
		}

		public static function changeStatusByDocReviewMasterlistId($doc_review_masterlist_id,$new_status)
		{
			try {
				require_once('../class/oop_acdcs.php');
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

	}


	if(is_ajax()) {
		if(isset($_POST["action"]) && !empty($_POST["action"])) {
			$action = $_POST["action"];
			switch($action) {	
				case "read_temporary_review_docu" 					: read_temporary_review_docu(); break; 
				case "temporary_create_review_docu" 				: temporary_create_review_docu(); break; 
			}	
		}
	}

	function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	/* 
		function read_temporary_review_docu() {
			require_once('../class/oop_acdcs.php');
			$return = $_POST;
			$array_fields = array('*');
			$table 	   	= '`tbl_doc_review_masterlist`';
			$joins 	   	= '';
			$sql_where 	=  'WHERE active.view_status = 1';
			$sql_order 	= 'ORDER BY `active`.`doc_no` DESC';
			$sql_limit 	= '';
			$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			while($row = mysqli_fetch_array($query)){
				
			}
			echo json_encode($return);

		}
	*/

	function temporary_create_review_docu()
	{
		try{
			require_once('../class/oop_acdcs.php');
			// require_once('../class/send_email.php');

			$return = $_POST ;
			$response = array();
			$username = $_SESSION['username'];
			$disposition = $return['disposition'];
			$datetime_today = date('Y-m-d H:i:s');
			$doc_review_masterlist_id = $return['doc_review_masterlist_id'];
			$request = new CommonClass;
			$send_email = new EmailDetail;
			$doc_review_masterlist = $request->docReviewMasterlist($doc_review_masterlist_id);
			$dcc_emailed_at = $doc_review_masterlist['created_at'];
			$new_status = 2;
			
			$table = 'tbl_doc_review_disposition';
			if($disposition == 1){ //Revision
				$array_fields = array('tbl_doc_review_masterlist_id','disposition','person_in_charge','due_date','reviewed_by','dcc_email','created_at');
				$array_values = array($doc_review_masterlist_id,$disposition,$return['person_in_charge'],$return['due_date'],$username,$dcc_emailed_at,$datetime_today);
				
			}
			if($disposition == 2 || $disposition == 3 ){ //Retain & Obsolete
				$array_fields = array('tbl_doc_review_masterlist_id','disposition','reviewed_by','dcc_email','created_at');
				$array_values = array($doc_review_masterlist_id,$disposition,$username,$dcc_emailed_at,$datetime_today);
			
				// $docu_review_disposition_id = ACDCS::getInstance()->insert_query_id($table,$array_fields,$array_values); 

				$tbl_name = 'tbl_doc_review_disposition';
				$docu_review_disposition_id = 1;
				$to = 'mclegaspi@pricon.ph';
				$from = 'cdcasuyon@pricon.ph';
				$cc = '';
				$subject = 'Testing Auto Emailer'.date('Y-m-d H:i:s').'';
				$message = 'Testing Auto Emailer';
				$date_start = date('Y-m-d');
				$frequency = 3;
				$testing[] = $send_email->send_scheduled_email($tbl_name, $docu_review_disposition_id, $to, $from, $cc, $subject, $message , $date_start, $frequency);
				echo json_encode($testing);
				// $request->changeStatusByDocReviewMasterlistId($doc_review_masterlist_id,$new_status); //change status
				$response['is_success'] = 'true';
				$response['result_msg'] =  'Save Successfully';
				return;
			}
			if($disposition == 4){//Extended
				$fkid_document =  $doc_review_masterlist['fkid_document'];
				$active_docs = $request->activeDocs($fkid_document);
				
				$date_difference = date_diff(date_create($return['extension_date']),date_create($active_docs['expiration_date']));
				$month = $date_difference->format("%m"); //get the month only
				$year = $date_difference->format("%y");//get only the year if the month is >= 12
 				// NOTE: Make every special character into underscore ( _ ) to locate the filename prevent problem in downloading of file 			
				$file_memo_tmp = $_FILES['file_memo']['tmp_name'];
				$file_memo_name = $_FILES['file_memo']['name'];				//'/etc/hosts/@Álix Ãxel likes - beer?!.pdf';
				$filtered_filename = '_'.Slug($file_memo_name, '_', '.');	 // _etc_hosts_alix_axel_likes_beer.pdf
 				// NOTE: Check if the file uploaded, the process required to upload MEMO
				if($file_memo_tmp == ""){
					if($month >= 9 || $year >= 1){
						$response['is_success'] ='false';
						$response['result_msg'] = 'Please submit the memo!';
						 echo json_encode($return['error_msg']);
						return;
					}
					$array_fields = array('tbl_doc_review_masterlist_id','disposition','extension_date','reviewed_by','dcc_email','created_at');
					$array_values = array($doc_review_masterlist_id,$disposition,$return['extension_date'],$username,$dcc_emailed_at,$datetime_today);
				}
				if($file_memo_tmp != ""){
					$is_file_name_existed = $request->selectDispositionByFilename($file_memo_name);
					if($is_file_name_existed['is_exist']=='true'){
						$response['is_success'] ='false';
						$response['result_msg'] = 'Filename is already existed!';
						echo json_encode($response);
						return;
					}
					$array_fields = array('tbl_doc_review_masterlist_id','disposition','memo_file_name','memo_file_filtered_name','extension_date','reviewed_by','dcc_email');
					$array_values = array($doc_review_masterlist_id,$disposition,$file_memo_name,$filtered_filename,$return['extension_date'],$username,$dcc_emailed_at);
					$doc_review_disposition_id = ACDCS::getInstance()->insert_query_id($table,$array_fields,$array_values);
				/* 
					Uploading of Data 
				*/
					$tbl_folder_path = $request->folderPath('temporary_document');
					$target_file = $tbl_folder_path['folder_path'].$doc_review_disposition_id.$filtered_filename;
					$request_upload_docu = $request->uploadDocument($tbl_folder_path['folder_path'],$file_memo_tmp,$target_file);

					$response['is_success'] = ($request_upload_docu == 1) ? 'true' : 'false';
					$response['result_msg'] = ($response['is_success'] == 'true') ? 'File uploaded successfully !': 'Uploading Failed. Please Try Again!';
					echo json_encode($response);
					return;
				}
			}
			$response['is_success'] = 'true';
			$response['result_msg'] =  'Save Successfully';
			// $script = ACDCS::getInstance()->insert_query($table,$array_fields,$array_values); 
			echo json_encode($response);
			return;
		}catch(\Exception $e){
			echo json_encode($e);
			return;
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

?>


