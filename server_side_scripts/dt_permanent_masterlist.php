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
	// echo 
	/* Model */
	$database_config = '../db_config/config_acdcs.php';
	$active_document_class = '../model/active_document.php';
	$doc_review_disposition_class = '../model/doc_review_disposition.php';


	use Model\DocReviewDisposition;
	use Model\ActiveDocument;

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
    
	$aColumns = array(
		'obsolete.expiration_date AS obsolete_expiration_date',
		'obsolete.date_received AS obsolete_date_received',
		'obsolete.model AS obsolete_model',
		'obsolete.doc_title AS obsolete_doc_title',
		'obsolete.doc_no AS obsolete_doc_no',
		'obsolete.rev_no AS obsolete_rev_no',
		'obsolete.effectivity_date AS obsolete_effectivity_date',
		'active.expiration_date',
		'masterlist.id',
		'masterlist.fkid_document',
		'masterlist.document_status',
		'masterlist.is_expiration_date_exist',
		'masterlist.deleted_at',
		'active.date_received',
		'active.model',
		'active.doc_title',
		'active.doc_no',
		'active.rev_no',
		'active.effectivity_date',
		'masterlist.originator_code',
		'disposition.reviewed_by',
		'disposition.disposition',
	);
	/* used this field for searching data typed in the search box */

	$array_search = array(
		'obsolete.date_received',
		'obsolete.model',
		'obsolete.doc_title',
		'obsolete.doc_no',
		'obsolete.rev_no',
		'obsolete.effectivity_date',
		'obsolete.expiration_date',
		'active.date_received',
		'active.model',
	 	'active.doc_title',
	 	'active.doc_no',
	 	'active.rev_no',
	 	'active.effectivity_date',
	 	'active.expiration_date',
	 	'masterlist.originator_code'
	);
							
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "masterlist.id";
	
	/* DB table to use */
	$sTable = "tbl_doc_review_masterlist masterlist";
	$sJoin = "LEFT JOIN tbl_active_docs active ON active.fkid_document = masterlist.fkid_document";
	$sJoin .= " LEFT JOIN tbl_obsolete_docs obsolete ON obsolete.fkid_document = masterlist.fkid_document";
	$sJoin .= " LEFT JOIN tbl_doc_review_disposition disposition ON disposition.tbl_doc_review_masterlist_id = masterlist.id";

	if(file_exists($database_config)){
		require_once($database_config);
	} else {
		echo "config file does not exist!";
		exit;
	}

	if(file_exists($active_document_class)){
		require_once($active_document_class);
	} else {
		echo "active_document_class file does not exist!";
		exit;
	}

	if(file_exists($doc_review_disposition_class)){
		require_once($doc_review_disposition_class);
	} else {
		echo "doc_review_disposition_class file does not exist!";
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
		// $sOrder = "ORDER BY masterlist.id ASC";
		$sOrder = "ORDER BY masterlist.document_status ASC";
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
		for ( $i=0 ; $i<count($array_search) ; $i++ )
		{
			$sWhere .= $array_search[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
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
	// for ( $i=0 ; $i<count($array_search) ; $i++ )
	// {
	// 	//NOTE: $_GET['bSearchable_'.$i] accept only 11  $array_search
	// 	if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
	// 	{
	// 		if ( $sWhere == "" )
	// 		{
	// 			$sWhere = "WHERE ";
	// 		}
	// 		else
	// 		{
	// 			$sWhere .= " AND ";
	// 		}
	// 		$sWhere .= $array_search[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			
	// 	}
	// }
	
	/* 
		dito ka mag add ng where mo, una check mo kung may laman na yung $sWhere pag wala append mo yung where mo na meron
		kasama where kapag naman may laman na AND na syempre diba :)
	*/

	if($sWhere == ""){
		$sWhere .= "WHERE  ";
	}else{
		$sWhere .= "AND ";
	}
	/* Default WHERE query without search inputs */
	$sWhere .= '( masterlist.deleted_at IS NULL AND masterlist.document_status < 3 )';
	$sWhere .= ' AND disposition.deleted_at IS NULL';
	$sWhere .= ' AND ( (active.`view_status` = 1 AND active.`logdel` = 0 AND active.expiration_date ="") OR (obsolete.`view_status` = 1 AND obsolete.`logdel` = 0 AND obsolete.expiration_date =""))';

	/* NOTE: Based on the situation, filtered data without searching will activate this WHERE QUERY, but if the sSearch is not NULL show the specific data based on searching inputs*/
	if ( $_GET['sSearch'] == "" ){
		// ilagay dito kung pwede based sa date, or customized advance search
	}
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$script = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sJoin
		$sWhere
		$sOrder
		$sLimit
	";
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
		"script_permanent" => $script,
	);
	/*
		Class Object
	*/
	$get_doc_review_disposition = new DocReviewDisposition;
	$active_documents = new ActiveDocument;
	
	while ( $aRow = mysql_fetch_array( $rResult ) )
    {
		$row = array();
		unset($row);

		$doc_review_masterlist_id = $aRow['id'];
		$fkid_document = $aRow['fkid_document'];
	
		/* Document Status */
		switch ($aRow['document_status']) {
			case 0:
				if($_GET['username'] == $aRow['reviewed_by']){
					$button = '<center><button btn-type="edit" data-id="'.$doc_review_masterlist_id.'" id="btnEditDocReviewDisposition" class="btn btn-sm fa fa-edit" type="button" style="background-color:skyblue;color:black;"> Edit Review </button></center><br>';
				}else{
					$button = '';
				}
				$button .= '<center><button btn-type="view" data-id="'.$doc_review_masterlist_id.'" id="btnViewDocReviewDisposition" class="btn btn-sm btn-default fa fa-eye" type="button"> View</button></center> <br>';
				$status = '<span class="badge highlight-color-red">DISAPPROVED</span>';
				break;
			case 1:
				$button = '<center><button data-id="'.$doc_review_masterlist_id.'" id="btnReviewDocu" class="btn btn-sm fa fa-plus" type="button" style="background-color:yellow;color:black;"> Add Review</button></center>';
				$status = '<span class="badge highlight-color-yellow">FOR REVIEW</span>';
				break;
			case 2:
				if($_GET['username'] == $aRow['reviewed_by']){
					$button = '<center><button btn-type="edit" data-id="'.$doc_review_masterlist_id.'" id="btnEditDocReviewDisposition" class="btn btn-sm fa fa-edit" type="button" style="background-color:skyblue;color:black;"> Edit Review </button></center><br>';
				}else{
					$button = '';
				}
				$button .= '<center><button btn-type="view" data-id="'.$doc_review_masterlist_id.'" id="btnViewDocReviewDisposition" class="btn btn-sm btn-default fa fa-eye" type="button"> View</button></center> <br>';
				$status = '<span class="badge highlight-color-lime">FOR APPROVAL</span>';
				break;
			case 3:
            	$button = '<center><button btn-type="view" data-id="'.$doc_review_masterlist_id.'" id="btnViewDocReviewDisposition" class="btn btn-sm btn-default fa fa-eye" type="button"> View</button></center>';
				$status = '<span class="badge highlight-color-blue">DCC CONFORMANCE</span>';
				break;
			default:
				$button = '';
				$status = '<span class="badge highlight-color-gray">Unknown Status</span>';
				break;
		}
		/* Disposition */
		$arr_doc_review_disposition = $get_doc_review_disposition->getDocReviewDispositionById($doc_review_masterlist_id);

		$doc_review_disposition = $get_doc_review_disposition->getDispositionCode($arr_doc_review_disposition['disposition']);

		/**
		 * TODO: check if dcc need to have checked box to approved then transfer to REVIEWED TABLE
		*/
		// $row[]= '<center><input data-id="'.$doc_review_masterlist_id.'" id="cBoxBatchSelect" class="form-control" type="checkbox"> </center>';
		$row[]= $button;
		// $row[]= $aRow['fkid_document'];
		$row[]= '<center> '.$status.' </center>';
		$row[]= '<center> ' .$doc_review_disposition. ' </center>';
		$row[]= ( $aRow['date_received'] != NULL ) ? $aRow['date_received'] : $aRow['obsolete_date_received'];
		$row[]= ( $aRow['model'] != NULL ) ? $aRow['model'] : $aRow['obsolete_model'];
		$row[]= ( $aRow['doc_title'] != NULL ) ? $aRow['doc_title'] : $aRow['obsolete_doc_title'];
		$row[]= ( $aRow['doc_no'] != NULL ) ? 
					'<a class="fa fa-file fa-sm" href="#" id="btnSearchDocNum" doc-no="' .$aRow['doc_no']. '" doc-type="active" style="font-size:130%;">'.' '.$aRow['doc_no'].'</a>'  :
					'<a class="fa fa-file fa-sm" href="#" id="btnSearchDocNum" doc-no="' .$aRow['obsolete_doc_no']. '" doc-type="obsolete" style="font-size:130%;">'.' '.$aRow['obsolete_doc_no'].'</a>' ;
		$row[]= ( $aRow['rev_no'] != NULL ) ? $aRow['rev_no'] : $aRow['obsolete_rev_no'];
		$row[]= ( $aRow['originator_code'] != NULL ) ? 
					'<span class="badge highlight-color-green">'.$aRow['originator_code'].'</span>' : 
					'<span class="badge highlight-color-green">'.$aRow['obsolete_originator_code'].'</span>';
		$row[]= ( $aRow['effectivity_date'] != NULL ) ? $aRow['effectivity_date'] : $aRow['obsolete_effectivity_date'];
		$row[]= ( $aRow['expiration_date'] != NULL ) ? $aRow['expiration_date'] : $aRow['obsolete_expiration_date'];
		$row[]= ( $aRow['date_received'] != NULL ) ? 'ACTIVE' : 'OBSOLETE';
		array_push($output['aaData'],$row);
	}
	echo json_encode( $output );
	/* 
		Functions 
	*/
?>
