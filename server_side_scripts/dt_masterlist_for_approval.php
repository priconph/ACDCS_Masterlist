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
	$system_one_class = '../model/systemone.php';
	$doc_review_disposition_class = '../model/doc_review_disposition.php';
	
	use Model\DocReviewDisposition;
	use Model\Hris;

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	$aColumns = array( 
		// 'obsolete.expiration_date AS obsolete_expiration_date',
		// 'obsolete.date_received AS obsolete_date_received',
		// 'obsolete.model AS obsolete_model',
		// 'obsolete.doc_title AS obsolete_doc_title',
		// 'obsolete.doc_no AS obsolete_doc_no',
		// 'obsolete.doc_type AS obsolete_doc_type',
		// 'obsolete.rev_no AS obsolete_rev_no',
		// 'obsolete.effectivity_date AS obsolete_effectivity_date',

		'approval.id',
		'approval.sec_heads_username',
		'masterlist.id as masterlist_id',
		'masterlist.fkid_document',
		'masterlist.document_status',
		'masterlist.is_expiration_date_exist',
		'masterlist.deleted_at',
		'masterlist.originator_code',
		'disposition.disposition',
	
		'active.date_received',
		'active.model',
		'active.doc_title',
		'active.doc_no',
		'active.doc_type',
		'active.rev_no',
		'active.effectivity_date',
		'active.expiration_date'
	);

	/* used this field for searching data typed in the search box */

	$array_search = array(
		// 'obsolete.date_received',
		// 'obsolete.model',
		// 'obsolete.doc_title',
		// 'obsolete.doc_no',
		// 'obsolete.rev_no',
		// 'obsolete.effectivity_date',
		// 'obsolete.doc_type',
		// 'obsolete.expiration_date',
		'active.date_received',
		'active.model',
	 	'active.doc_title',
	 	'active.doc_no',
	 	'active.rev_no',
	 	'active.doc_type',
	 	'active.effectivity_date',
	 	'active.expiration_date',
	 	'masterlist.originator_code',
	);
							
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "approval.id";
	
	/* DB table to use */
	$sTable = " (
			SELECT id, tbl_doc_review_disposition_id, sec_heads_username
			FROM tbl_doc_review_disposition_approval_details
			WHERE deleted_at IS NULL
			AND sec_heads_username = '".$_GET['username']."'
			AND status = 0
			ORDER BY id DESC  
			LIMIT 0,30
		) approval";
	$sJoin = "";
	$sJoin .= "JOIN tbl_doc_review_disposition disposition
		ON disposition.id = approval.tbl_doc_review_disposition_id 
		AND disposition.deleted_at IS NULL";
	// $sJoin .= " LEFT JOIN tbl_obsolete_docs obsolete ON obsolete.fkid_document = masterlist.fkid_document";
	$sJoin .= " JOIN tbl_doc_review_masterlist masterlist
			ON masterlist.id = disposition.tbl_doc_review_masterlist_id 
		AND masterlist.deleted_at IS NULL 
		AND masterlist.document_status = 2";
	$sJoin .= " JOIN tbl_active_docs active
		ON active.fkid_document = masterlist.fkid_document 
		AND active.view_status = 1 
		AND active.logdel = 0";
	


	if(file_exists($database_config)){
		require_once($database_config);
	} else {
		echo "config file does not exist!";
		exit;
	}

	if(file_exists($system_one_class)){
		require_once($system_one_class);
	} else {
		echo "system_one_class file does not exist!";
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

	/* 
		dito ka mag add ng where mo, una check mo kung may laman na yung $sWhere pag wala append mo yung where mo na meron
		kasama where kapag naman may laman na AND na syempre diba :)
	*/

	if($sWhere == ""){
		$sWhere .= "WHERE  ";
	}else{
		$sWhere .= "AND ";
	}
	$sWhere .= '(masterlist.deleted_at IS NULL AND masterlist.document_status = 2)';
	$sWhere .= ' AND disposition.deleted_at IS NULL';
	$sWhere .= ' AND (approval.deleted_at IS NULL AND approval.sec_heads_username= "'.$_GET['username'].'" AND approval.status = 0 )';
	// $sWhere .= ' AND ( (active.`view_status` = 1 AND active.`logdel` = 0) OR (obsolete.`view_status` = 1 AND obsolete.`logdel` = 0))';
	$sWhere .= ' AND ( (active.`view_status` = 1 AND active.`logdel` = 0) )';


	
    
	// $sWhere .= " `status` = 0 OR `status` = 2 OR `status` = 8 OR `status` = 'A' AND `logdel` = 0";
	/*
	 * SQL queries
	 * Get data to display
	 */
	$script = "
		SELECT ".implode(", ", $aColumns)."
		FROM   $sTable
		$sJoin
		$sOrder
	";
	
	$sQuery = "
		SELECT ".implode(", ", $aColumns)."
		FROM   $sTable
		$sJoin
		$sOrder
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
		$sOrder
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

	
	while ( $aRow = mysql_fetch_array( $rResult ) )
    {
		$row = array();
		unset($row);

		$doc_review_disposition_approval_id = $aRow['id'];
		$doc_review_masterlist_id = $aRow['masterlist_id'];
		$fkid_document = $aRow['fkid_document'];

		/* Disposition*/
		$disposition = DocReviewDisposition::getDispositionCode($aRow['disposition']);
		$row[]= '<center><input data-id="'.$doc_review_disposition_approval_id.'" id="cBoxBatchSelect" class="form-control" type="checkbox"  style="width:130%;"> </center>';
		$row[] = '<center><button data-id="'.$doc_review_masterlist_id.'" id="btnViewDocReviewDisposition" class="btn btn-sm btn-default fa fa-eye" type="button">View</button></center> <br>';
		$row[]= Hris::get_emp_name_by_username_systemone($aRow['sec_heads_username']);
		// $row[]= $aRow['id'];
		// $row[]= $getActiveDocumentById['fkid_document'];
		// $row[]= '<center> '.$status.' </center>';
		$row[]= '<center>  '.$disposition.' </center>';
		$row[]= $aRow['date_received'];
		$row[]= $aRow['model'];
		$row[]= $aRow['doc_title'];
		$row[]= '<a class="fa fa-file fa-sm" href="#" id="btnSearchDocNum" doc-no="' .$aRow['doc_no']. '" doc-type="active" style="font-size:130%;">'.' '.$aRow['doc_no'].'</a>' ;
		$row[]= $aRow['rev_no'];
		$row[]= '<span class="badge highlight-color-green">'.$aRow['originator_code'].'</span>';
		$row[]= $aRow['doc_type'];
		$row[]= $aRow['effectivity_date'];
		$row[]= $aRow['expiration_date'];
		$row[]= 'ACTIVE';
		array_push($output['aaData'],$row);
	}
	echo json_encode( $output );
?>
