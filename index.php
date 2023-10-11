<?php
// /* you may use any file from pages as your homepage */
// if( isset($_GET['page']) ){
// 	$page = $_GET['page'];
// 	/* changes here must also be applied in handler fn_get_page() */
// 	switch($page){
// 		case "dashboard" 	: $page_link = 'pages/dashboard/index.php'; break;
// 		case "iqc" 			: $page_link = 'pages/iqc/index.php'; break;
// 	}
// 	$selected_menu = $page;
// 	include($page_link);
// }else{

// }
// $page_link = 'pages/iqc/index.php';
// $query_from_acdcs = $_GET['query'];


// echo $_GET['query'];
// echo $_GET['page'];
// $page_link 		= 'pages/iqc/index.php';
// include($page_link);

// $page_link = 'pages/masterlist/index.php';
$page_link = 'pages/masterlist_temporary/index.php';
include($page_link);
?>


