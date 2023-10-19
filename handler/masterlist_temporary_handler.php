<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	class CommonClass{

		public static function docReviewMasterlist($doc_review_masterlist_id) {
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
		public static function activeDocs($fkid_document) {
			require_once('../class/oop_acdcs.php');
			$fkid_document;
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

	function temporary_create_review_docu(){
		try{
			require_once('../class/oop_acdcs.php');
			$return = $_POST ;
			$username = $_SESSION['username'];
			$disposition = $return['disposition'];
			$datetime_today = date('Y-m-d H:i:s');
			$doc_review_masterlist_id = $return['doc_review_masterlist_id'];
			$request = new CommonClass();
			$doc_review_masterlist = $request->docReviewMasterlist($doc_review_masterlist_id);
			
			$table = 'tbl_doc_review_disposition';
			if($disposition == 1){ //Revision
				$array_fields = array('tbl_doc_review_masterlist_id','disposition','person_in_charge','due_date','reviewed_by','dcc_email','created_at');
				$array_values = array($doc_review_masterlist_id,$disposition,$return['person_in_charge'],$return['due_date'],$username,$email_date['created_at'],$datetime_today);
			}
			if($disposition == 2 || $disposition == 3 ){ //Retain & Obsolete
				$array_fields = array('tbl_doc_review_masterlist_id','disposition','reviewed_by','dcc_email','created_at');
				$array_values = array($return['doc_review_masterlist_id'],$disposition,$username,$doc_review_masterlist['created_at'],$datetime_today);
			}
			if($disposition == 4){//Extended
				$fkid_document =  $doc_review_masterlist['fkid_document'];
				$active_docs = $request->activeDocs($fkid_document);
				// echo $active_docs['expiration_date'];
				$date_difference = date_diff(date_create($return['extension_date']),date_create($active_docs['expiration_date']));
				echo $date_difference->format("%m");
				return;
				$file_memo_tmp = $_FILES['file_memo']['tmp_name'];
				$file_memo_name = $_FILES['file_memo']['name'];
/*
	Make every special character into underscore ( _ ) to prevent problem in downloading of file 
*/			
				$filename = $file_memo_name; //'/etc/hosts/@Álix Ãxel likes - beer?!.jpg';
				$filename = Slug($filename, '_', '.'); // etc_hosts_alix_axel_likes_beer.jpg
/*
	Check if the file uploaded, the process required to upload MEMO
*/
				if($file_memo_tmp == ""){
					if($date_difference >= 9){ //>=9 months
						$return['erro_msg'] = "Please upload the Memorandum";
						echo json_encode($return['erro_msg']);
						return;
					}
					$array_fields = array('tbl_doc_review_masterlist_id','disposition','extension_date','reviewed_by','dcc_email','created_at');
				}
				if($file_memo_tmp != ""){
					$array_fields = array('tbl_doc_review_masterlist_id','disposition','memo_file_name','memo_file_name','extension_date','reviewed_by','dcc_email','created_at');
				}
				$array_values = array('Extended');
			}
			// $result = ACDCS::getInstance()->insert_query($table,$array_fields,$array_values);
			// $script = ACDCS::getInstance()->insert_query_script($table,$array_fields,$array_values);
			echo json_encode($array_fields);
			return;
		}catch(\Exception $e){
			echo json_encode($e);
			return;
		}
		
		/*
		return 'true';
		require_once('../class/oop_acdcs.php');
		$date_time_today 	 = date('Y-m-d H:i:s');
		
		$fkid = $return['pkid'];
		$remarks = $return['remarks'];
		$username = $return['username'];
		$table = 'tbl_qfr_sa_for_revision';
		$array_fields = array('created_by','fk_special_acceptance','revision_count','remarks','username','created_at','updated_at');
		foreach($array_fields as $key => $value){
			if(!isset($return[$value]) || $return[$value] == ""){
				$return[$value] = "";
			}
		}
		$array_values = array($username,$fkid,1,$remarks,$username,$date_time_today,'');
		$result = TQTS::getInstance()->insert_query($table,$array_fields,$array_values);
		*/
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


