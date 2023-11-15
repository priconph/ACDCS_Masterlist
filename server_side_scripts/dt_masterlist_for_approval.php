<?php
	/*
	 * Script:    DataTables server-side script for PHP and MySQL
	 * Copyright: 2010 - Allan Jardine
	 * License:   GPL v2 or BSD (3-point)
	 */
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	/* Model */
	$database_config = '../db_config/config_acdcs.php';
	$active_document_class = '../model/active_document.php';
	$common_function_handler = '../handler/common_function_handler.php';


	use Handler\DocuReviewDisposition;
	use Model\ActiveDocument;

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	$aColumns = array( 
		'disposition.disposition',
		'approval.id',
		'approval.sec_heads_username',
		'masterlist.fkid_document',
		'masterlist.document_status',
		'masterlist.is_expiration_date_exist',
		'masterlist.deleted_at',
		'active.date_received',
		'active.model',
		'active.doc_title',
		'active.doc_no',
		'active.rev_no',
		'masterlist.originator_code',
	);

	/* used this field for searching data typed in the search box */

	$array_search = array(
		'active.date_received',
		'active.model',
	 	'active.doc_title',
	 	'active.doc_no',
	 	'active.rev_no',
	 	'masterlist.originator_code',
	);
							
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "approval.id";
	
	/* DB table to use */
	$sTable = "tbl_doc_review_masterlist masterlist";
	$sJoin = "LEFT JOIN tbl_active_docs active ON active.fkid_document = masterlist.fkid_document";
	$sJoin .= " RIGHT JOIN tbl_doc_review_disposition disposition ON disposition.tbl_doc_review_masterlist_id = masterlist.id";
	$sJoin .= " RIGHT JOIN tbl_doc_review_disposition_approval_details approval ON approval.tbl_doc_review_disposition_id = disposition.id";
	


	if(file_exists($database_config)){
		require_once($database_config);
	} else {
		echo "config file does not exist!";
		exit;
	}

	if(file_exists($common_function_handler)){
		require_once($common_function_handler);
	} else {
		echo "common_function_handler file does not exist!";
		exit;
	}

	if(file_exists($active_document_class)){
		require_once($active_document_class);
	} else {
		echo "common_function_handler file does not exist!";
		exit;
	}

	/* Database connection information */
	$gaSql['user']       = $username;
	$gaSql['password']   = $password;
	$gaSql['db']         = $db_name;
	$gaSql['server']     = $server;
	
	
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * MySQL connection
	 */
	$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
		die( 'Could not open connection to server' );
	
	mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
		die( 'Could not select database '. $gaSql['db'] );
    
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}
    

/*
	Ordering/Sorting
    aColumns is array of column a DB
*/
    $sOrder = '';
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	if($sOrder == ""){
		$sOrder = "ORDER BY approval.id DESC";
	}

	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	 
	 /* wag gagalawin yung sWhere default to para sa searchbox ng datatables check mo sa baba kung pano gagawin mo :) */
	$sWhere = "";
	if ( $_GET['sSearch'] != "" )
	{
		// $sWhere .= ')'; 
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if( in_array($aColumns[$i],$array_search) ){
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
    
    /* The number of UI column is required to be equal to table column
        Ex. 5 db table column == 5 UI column. Uncomment the script below to show.
        echo json_encode($aColumns);
        return;
    */

	/* Individual column filtering */
	for ( $i=0 ; $i<count($array_search) ; $i++ )
	{
		//NOTE: $_GET['bSearchable_'.$i] accept only 11  $array_search
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $array_search[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	


	/* 
		dito ka mag add ng where mo, una check mo kung may laman na yung $sWhere pag wala append mo yung where mo na meron
		kasama where kapag naman may laman na AND na syempre diba :)
	*/

	if($sWhere == ""){
		$sWhere .= "WHERE  ";
	}else{
		$sWhere .= "AND ";
	}
	// $sWhere .= '(active.view_status =1 AND active.`logdel` =0) AND (masterlist.deleted_at IS NULL AND masterlist.document_status = 2) AND disposition.deleted_at IS NULL AND (approval.deleted_at IS NULL AND approval.sec_heads_username= "'.$_GET['username'].'") ';
	$sWhere .= '(active.view_status =1 AND active.`logdel` =0)';
	$sWhere .= ' AND (masterlist.deleted_at IS NULL AND masterlist.document_status = 2)';
	$sWhere .= ' AND disposition.deleted_at IS NULL';
	$sWhere .= ' AND (approval.deleted_at IS NULL AND approval.sec_heads_username= "'.$_GET['username'].'" AND approval.status = 0 )';
    
	// $sWhere .= " `status` = 0 OR `status` = 2 OR `status` = 8 OR `status` = 'A' AND `logdel` = 0";
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sJoin
		$sWhere
		$sOrder
		$sLimit
	";
	
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
		$sJoin
		$sWhere
		$sOrder
		$sLimit
	";
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array(),
	);
	/*
		Class Object
	*/
	$request_doc_review_disposition = new DocuReviewDisposition;
	$active_documents = new ActiveDocument;
	
	while ( $aRow = mysql_fetch_array( $rResult ) )
    {
		$row = array();
		unset($row);

		$doc_review_masterlist_id = $aRow['id'];
		$fkid_document = $aRow['fkid_document'];
	/* 
		Button
	*/
        $getActiveDocumentById = $active_documents->getActiveDocumentById($fkid_document);
		if($getActiveDocumentById['document_status'] == 1){
			$button = '<center><button  data-id="'.$doc_review_masterlist_id.'" id="btnReviewDocu" class="btn btn-sm fa fa-edit" type="button" style="background-color:yellow;color:black;"> Review</button></center>';
        }else if($getActiveDocumentById['document_status'] == 2){
            $button = '<center><button data-id="'.$doc_review_masterlist_id.'" id="btnViewDetails" class="btn btn-sm btn-default fa fa-eye" type="button"> View</button></center>';
        }
	/* 
		Document Status
	*/
        if($getActiveDocumentById['document_status'] == 1){
            $status = '<span class="badge highlight-color-yellow">FOR REVIEW</span>';
        }else if($getActiveDocumentById['document_status'] == 2){
            $status = '<span class="badge highlight-color-lime">FOR APPROVAL</span>';
        }
	/* 
		Disposition
	*/
		// $arr_doc_review_disposition = $request_doc_review_disposition->getDocReviewDispositionById($doc_review_masterlist_id);
		// switch ($variable) {
		// 	case 'value':
		// 		# code...
		// 		break;
			
		// 	default:
		// 		# code...
		// 		break;
		// }
		if($aRow['disposition'] == 1){
			$doc_review_disposition = 'Revision';
		}else if($aRow['disposition'] == 2){
			$doc_review_disposition = 'Retain';
		}else if($aRow['disposition'] == 3){
			$doc_review_disposition = '<span class="">Obsolete';
		}else if($aRow['disposition'] == 4){
			$doc_review_disposition = 'Extended';
		}else{
			$doc_review_disposition = '';
		}

		$row[]= '<center><input data-id="'.$doc_review_masterlist_id.'" id="cBoxBatchSelect" class="form-control" type="checkbox"> </center>';
		$row[]= $aRow['sec_heads_username'];
		$row[]= $aRow['id'];
		$row[]= $getActiveDocumentById['fkid_document'];
		$row[]= '<center> '.$status.' </center>';
		$row[]= '<center> ' .$doc_review_disposition. ' </center>';
		$row[]= $aRow['date_received'];
		$row[]= $aRow['model'];
		$row[]= $aRow['doc_title'];
		$row[]= $aRow['doc_no'];
		$row[]= $aRow['rev_no'];
		$row[]= $aRow['originator_code'];
		array_push($output['aaData'],$row);
	}
	echo json_encode( $output );
	/* 
		Functions 
	*/
?>
