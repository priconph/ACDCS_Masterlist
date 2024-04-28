<?php
session_start();
/* changes here must also be applied in handler fn_get_page() */
/* you may use any file from pages as your homepage*/
if( isset($_GET['page']) ){
	$page = $_GET['page'];
	switch($page){
		case "dashboard" 		: $page_link = 'pages/dashboard/index.php'; break;
		case "temporaryDocu" 	: $page_link = 'pages/masterlist_temporary/index.php'; break;
		case "permanentDocu" 	: $page_link = 'pages/masterlist_permanent/index.php'; break;
		case "forApproval" 		: $page_link = 'pages/for_approval_document/index.php'; break;
		default 				: $page_link = 'pages/redirect_page/index.php';
	}
	$selected_menu = $page;
	include($page_link);
}else{
	$page_link 		= 'pages/dashboard/index.php';
	$selected_menu 	= 'dashboard';
	include($page_link);
}

// echo 'test';
// echo ($_GET['page']);
// return;

// $page_link 	= 'pages/masterlist_temporary/index.php';//masterlist_temporary
// $page_link 	= 'pages/dashboard/index.php';//masterlist_temporary
// include($page_link);

?>
