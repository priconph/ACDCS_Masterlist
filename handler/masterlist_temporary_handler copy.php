<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
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
		$return = $_POST ;
		$request = array() ;
		$array_fields = array('disposition,person_in_charge,due_date');
		$array_fields_implode = implode(',',$array_fields);
		$array_fields_explode = explode(',',$array_fields_implode);
		foreach($array_fields_explode as $key => $value){
			if(!isset($return[$value]) || $return[$value] == ""){
				$return[$value] = "";
				$return['required'] = ''.$value.' field is required!';
				echo json_encode($return['required']);
			}
		}
		/**
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

?>


