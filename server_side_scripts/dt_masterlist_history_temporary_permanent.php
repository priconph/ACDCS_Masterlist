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
		'obsolete.originator_code AS obsolete_originator_code',
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
		'disposition.dcc_email',
		'disposition.review_count',
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
		'obsolete.originator_code',
		'active.date_received',
		'active.model',
	 	'active.doc_title',
	 	'active.doc_no',
	 	'active.rev_no',
	 	'active.effectivity_date',
	 	'active.expiration_date',
	 	'masterlist.originator_code',
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
	//? NOTE: According to QAS/requestor active permanent document has no exp date but obsolete permanent has exp date,
	//? so to check if the document is temporary or permanent document, save to  tbl_doc_review_masterlist - is_expiration_date_exist column BOOLEAN
	$sWhere .= ' masterlist.deleted_at IS NULL';
	$sWhere .= ' AND disposition.tbl_doc_review_masterlist_id ='.$_GET['doc_review_masterlist_id'].' ';
	$sWhere .= ' AND disposition.review_count = 1';

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
	
		
		/* Disposition */
		// $arr_doc_review_disposition = $get_doc_review_disposition->getDocReviewDispositionById($doc_review_masterlist_id);

		$doc_review_disposition = $get_doc_review_disposition->getDispositionCode($aRow['disposition']);

		//? NOTE: If Obsolete or Revision disposition & Obsolete document means DCC Conformed
		$row[]= '<center> ' .$doc_review_disposition. ' </center>';
		// $row[]= '<center> ' .$aRow['disposition']. ' </center>';
		$row[]= '<center> ' .$aRow['dcc_email'] !=NULL ? date('Y-m-d',strtotime($aRow['dcc_email'])) : "" . ' </center>';
		$row[]= '<a class="fa fa-file fa-sm" href="#" id="btnSearchDocNum" doc-no="' .$aRow['obsolete_doc_no']. '" doc-type="obsolete" style="font-size:130%;">'.' '.$aRow['obsolete_doc_no'].'</a>' ;
		array_push($output['aaData'],$row);
	}
	echo json_encode( $output );
	/* 
		Functions 
	*/
?>
