<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	if(is_ajax()) {
		if(isset($_POST["action"]) && !empty($_POST["action"])) {
			$action = $_POST["action"];
			switch($action) {
				/* do not edit below */
				case "get_page_link" 								: get_page_link(); break; 
				case "encode" 										: encode(); break; 
				case "decode" 										: decode(); break; 
			}
		}
	}

	function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	function get_page_link(){
		$link_id = $_POST['link_id'];
		switch($link_id){
			case "dashboard"  		: $return['page'] = "dashboard"; break;
			case "temporaryDocu"  	: $return['page'] = "temporaryDocu"; break;
			case "permanentDocu"  	: $return['page'] = "permanentDocu"; break;
			case "forApproval"  	: $return['page'] = "forApproval"; break;
			default					: $return['page'] = "";
		}
		echo json_encode($return);
	}
	
	/* convert an array to encode base64 and serialize */
	function encode(){
		$str = base64_encode(serialize($_POST));
		echo json_encode($str);
	}

	/* revert to normal array, decode str to base64 and unserialize */
	function decode(){
		$array = unserialize(base64_decode($_POST));
		echo json_encode($array);
	}

	function encodeURIComponent($str) {
		$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
		return strtr(rawurlencode($str), $revert);
	}
    
?>