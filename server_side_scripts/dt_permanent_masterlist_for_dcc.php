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
		'active.originator_code as active_originator_code',
		'disposition.reviewed_by',
		'disposition.disposition',
		'disposition.dcc_email',
		'disposition.due_date',
	);
	/* used this field for searching data typed in the search box */

	$array_search = array(
		'active.date_received',
		'active.model',
	 	'active.doc_title',
	 	'active.doc_no',
	 	'active.rev_no',
	 	'active.effectivity_date',
	 	'active.expiration_date',
		'disposition.dcc_email',
	);
							
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "masterlist.id";
	
	/* DB table to use */
	$sTable = "tbl_doc_review_masterlist masterlist";
	$sJoin = "LEFT JOIN tbl_active_docs active ON active.fkid_document = masterlist.fkid_document";
	$sJoin .= " LEFT JOIN tbl_obsolete_docs obsolete ON obsolete.fkid_document = masterlist.fkid_document";
	// $sJoin .= " LEFT JOIN tbl_doc_review_disposition disposition ON disposition.tbl_doc_review_masterlist_id = masterlist.id";
	//-- 1. Find ONLY the latest non-deleted disposition ID per masterlist row
	$sJoin .= " LEFT JOIN (
    SELECT tbl_doc_review_masterlist_id, MAX(id) AS max_disp_id
    FROM tbl_doc_review_disposition
    WHERE deleted_at IS NULL
    GROUP BY tbl_doc_review_masterlist_id
	) latest_disp 
    ON latest_disp.tbl_doc_review_masterlist_id = masterlist.id";
	//-- 2. Join the actual disposition data using that max ID
	$sJoin .= " LEFT JOIN tbl_doc_review_disposition disposition 
    ON disposition.id = latest_disp.max_disp_id";

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
		$sOrder = "ORDER BY masterlist.id ASC";
		// $sOrder = "ORDER BY disposition.disposition ASC";
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
	//? NOTE: According to QAS/requestor active permanent document has no exp date but obsolete permanent has exp date,
	//? so to check if the document is temporary or permanent document, save to  tbl_doc_review_masterlist - is_expiration_date_exist column BOOLEAN
	$sWhere .= '( masterlist.deleted_at IS NULL AND masterlist.document_status = 3 AND masterlist.is_expiration_date_exist = "false")';
	// $sWhere .= ' AND disposition.deleted_at IS NULL'; //
	$sWhere .= ' AND active.`view_status` = 1 AND active.`logdel` = 0 AND active.expiration_date =""';

	/* NOTE: Based on the situation, filtered data without searching will activate this WHERE QUERY, but if the sSearch is not NULL show the specific data based on searching inputs*/
	if ( $_GET['sSearch'] == "" ){
		// ilagay dito kung pwede based sa date, or customized advance search
	}
	$sGroupBy = ' GROUP BY active.doc_no';
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$script = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sJoin
		$sWhere
		$sGroupBy
		$sOrder
		$sLimit
	";
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sJoin
		$sWhere
		$sGroupBy
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
		"script" => $script,
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
				$button = '';
            	$button .= '<center><button btn-type="view" data-id="'.$doc_review_masterlist_id.'" id="btnReviewAgain" class="btn btn-sm btn-primary fa fa-edit" type="button">Review Again</button></center> <br>';
            	$button .= '<center><button btn-type="view" data-id="'.$doc_review_masterlist_id.'" id="btnViewDocReviewDisposition" class="btn btn-sm btn-default fa fa-eye" type="button"> View</button></center>';
				$status = '<span class="badge highlight-color-blue">FOR DCC CONFORMANCE</span>';
				break;
			default:
				$button = '';
				$status = '<span class="badge highlight-color-gray">Unknown Status</span>';
				break;
		}
		
		/* Disposition */
		$arr_doc_review_disposition = $get_doc_review_disposition->getDocReviewDispositionById($doc_review_masterlist_id);
		$doc_review_disposition = $get_doc_review_disposition->getDispositionCode($arr_doc_review_disposition['disposition']);
		
		$button .= '<br><center><button btn-type="view" doc-review-masterlist-id="'.$doc_review_masterlist_id.'" id="btnViewDispositionHistory" class="btn btn-sm btn-default fa fa-file" type="button"> View History</button></center>';
		$row[]= $button;
		$row[]= '<center> '.$status.' </center>';
		$due_date = ($aRow['due_date'] != "0000-00-00") ? $aRow['due_date'] : "";
		$row[]= '<center> ' . $due_date . $doc_review_disposition .  ' </center>';
		$row[]= '<center> ' .$aRow['dcc_email'] !=NULL ? date('Y-m-d',strtotime($aRow['dcc_email'])) : "" . ' </center>';
		$row[]= $aRow['date_received'];
		$row[]= $aRow['model'] ;
		$row[]= $aRow['doc_title'];
		$row[]= '<a class="fa fa-file fa-sm" href="#" id="btnSearchDocNum" doc-no="' .$aRow['doc_no']. '" doc-type="active" style="font-size:130%;">'.' '.$aRow['doc_no'].'</a>';
		$row[]= $aRow['rev_no'];
		$row[]= '<span class="badge highlight-color-green">'.$aRow['active_originator_code'].'</span>';
		$row[]= $aRow['effectivity_date'];
		$row[]= $aRow['expiration_date'];
		$row[]= ( $aRow['date_received'] != NULL ) ? 'ACTIVE' : 'OBSOLETE';
		array_push($output['aaData'],$row);
	}
	echo json_encode( $output );
?>
