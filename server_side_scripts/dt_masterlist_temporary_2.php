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
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	/* 
SELECT `masterlist`.id AS `doc_review_masterlist_id` , `masterlist`.document_status, `masterlist`.created_at, `active`. * , `docs`.`pkid` AS `doc_pkid` , `docs`.`section`
FROM `tbl_active_docs` active
LEFT JOIN tbl_documents docs ON docs.pkid = active.fkid_document
RIGHT JOIN `tbl_doc_review_masterlist` masterlist ON masterlist.fkid_document = docs.pkid
WHERE `masterlist`.deleted_at IS NULL
AND masterlist.is_expiration_date_exist = 'true'
ORDER BY masterlist.id DESC
LIMIT 0 , 10
	*/
	$aColumns = array('`masterlist`.id AS `doc_review_masterlist_id`,`masterlist`.document_status,`masterlist`.created_at, `active`. * ,`docs`.`pkid` AS `doc_pkid`,`docs`.`section`');

	/* used this field for searching data typed in the search box */
	// $array_search = array('empno', 'LastName',
							// 'date', 'date_out');
	// $array_search = array('empno', 'LastName');
							
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "`masterlist`.id";
	
	/* DB table to use */
	$sTable = "`tbl_active_docs` active";
	
	$database_config = '../db_config/config_acdcs.php';
	
	if(!file_exists($database_config)){
		echo "config file does not exist!";
		exit;
	} else {
		require_once($database_config);
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
	Ordering
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
    // echo $sOrder;
	// return;
	if($sOrder == ""){
		$sOrder = " ORDER BY `masterlist`.`id` DESC";
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
		//  $sWhere = "WHERE (";
		// for ( $i=0 ; $i<count($aColumns) ; $i++ )
		// {
		// 	if( in_array($aColumns[$i],$array_search) ){
		// 	$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		// 	}
		// }
		// $sWhere = substr_replace( $sWhere, "", -3 );

		$sWhere .= ')'; 
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if( strpos($aColumns[$i]," as ") !== false ){
				$modified_search = explode(" ",$aColumns[$i]);
				$modified_search_reduced = array();
				for($x=0;$x< (count($modified_search) -2); $x++){
					$modified_search_reduced[] = $modified_search[$x];
				}
				$sWhere .= implode(" ",$modified_search_reduced)." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}else{
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
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
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
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
    

	/* 
		dito ka mag add ng where mo, una check mo kung may laman na yung $sWhere pag wala append mo yung where mo na meron
		kasama where kapag naman may laman na AND na syempre diba :)
	*/
    $sJoin = '';
    $sJoin = 'LEFT JOIN tbl_documents docs ON docs.pkid = active.fkid_document';
    $sJoin .= ' RIGHT JOIN `tbl_doc_review_masterlist` masterlist ON masterlist.fkid_document = docs.pkid';

	
	if($sWhere == ""){
		$sWhere .= "WHERE  ";
	}else{
		$sWhere .= "AND ";
	}
	$sWhere .= " `masterlist`.deleted_at IS NULL AND is_expiration_date_exist = 'true'"; //nmodify
    
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
	$scriptQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
        $sJoin
		$sWhere
		$sOrder
		$sLimit
	";

	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array(),
		"script" => $scriptQuery,
	);
	while ( $aRow = mysql_fetch_array( $rResult ) )
    {
		$row = array();
		unset($row);

		// $row[]= $aRow['doc_review_masterlist_id'];
		// $row['fkid_document'] = $aRow['fkid_document'];
        // $row['document_status'] = $aRow['document_status'];
		$row[]= '<center><input class="form-control" type="checkbox"> </center>';
		$row[]= '<center><button class="btn btn-primary btn-sm" type="button">Edit</button></center>';
		$row[]= $aRow['fkid_document'];

        if($aRow['document_status'] == 1){
            $status = '<span class="badge highlight-color-yellow">FOR REVIEW</span>';
        }else{
            $status = '';
        }
		$row[]= '<center> '.$status.' </center>';
        $row[] = $aRow['date_received'];
        $row[] = $aRow['model'];
        $row[] = $aRow['doc_title'];
        $row[] = $aRow['doc_no'];
        $row[] = $aRow['rev_no'];
/* 
    Document Status
*/
		array_push($output['aaData'],$row);
	}
	echo json_encode( $output);

/* 
    Functions 
*/

/*
SELECT `masterlist`.id AS `doc_review_masterlist_id` , `masterlist`.document_status, `masterlist`.created_at, `active`. * , `docs`.`pkid` AS `doc_pkid` , `docs`.`section`
FROM `tbl_active_docs` active
LEFT JOIN tbl_documents docs ON docs.pkid = active.fkid_document
RIGHT JOIN `tbl_doc_review_masterlist` masterlist ON masterlist.fkid_document = docs.pkid
WHERE active.view_status =1
AND active.`logdel` =0
AND docs.doc_status =1
AND masterlist.deleted_at IS NULL
ORDER BY `masterlist`.`created_at`
LIMIT 0 , 10

SELECT SQL_CALC_FOUND_ROWS id, fkid_document, document_status, is_expiration_date_exist, deleted_at, created_at, updated_at 
FROM `tbl_active_docs` active 
LEFT JOIN tbl_documents docs ON docs.pkid = active.fkid_document 
RIGHT JOIN `tbl_doc_review_masterlist` masterlist ON masterlist.fkid_document = docs.pkid 
WHERE `deleted_at` IS NULL AND is_expiration_date_exist = 'true'
ORDER BY deleted_at asc LIMIT 0, 10

    SELECT FROM ACDCS Module
    SELECT `masterlist`.id as `doc_review_masterlist_id`,`masterlist`.document_status,`masterlist`.created_at , `active`. * , `docs`.`pkid` AS `doc_pkid`, `docs`.`section`
    FROM `tbl_active_docs` active
    LEFT JOIN tbl_documents docs ON docs.pkid = active.fkid_document
    RIGHT JOIN `tbl_doc_review_masterlist` masterlist ON masterlist.fkid_document = docs.pkid
    WHERE active.view_status =1
    AND active.`logdel` =0
    AND docs.doc_status =1
    AND masterlist.id = '.$fkid_document.'
    AND masterlist.deleted_at IS NULL
    ORDER BY `active`.`doc_no` DESC
    LIMIT 0 , 10
    '
    document_status
    date_received
    model
    doc_title
    doc_no
    rev_no
    originator_code
    doc_type
    effectivity_date
    expiration_status
    created_at
*/
?>