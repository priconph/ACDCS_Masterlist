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
    // echo 'true';
    // return;

	// $aColumns = array( 
    //             'id',
    //             'fkid_document',
	// 			'document_status',
    //             'is_expiration_date_exist',
	// 			'deleted_at',
	// 			'created_at',
	// 			'updated_at',
	// 			// "(SELECT `vw_role`.`role` FROM `vw_user_roles` `vw_role` WHERE `vw_role`.`user` = '".$_GET['username']."' AND `vw_role`.`module` = 'Special Acceptance' AND `vw_role`.`subsystem_code` = 'QFR' AND `vw_role`.`logdel` = '0') as role",
	// 			);
	$aColumns = array( 
                'id',
                'fkid_document',
				'document_status',
                'is_expiration_date_exist',
				'deleted_at',
				'created_at',
				'updated_at',
				// "(SELECT `vw_role`.`role` FROM `vw_user_roles` `vw_role` WHERE `vw_role`.`user` = '".$_GET['username']."' AND `vw_role`.`module` = 'Special Acceptance' AND `vw_role`.`subsystem_code` = 'QFR' AND `vw_role`.`logdel` = '0') as role",
				);

	
	/* used this field for searching data typed in the search box */
	// $array_search = array('empno', 'LastName',
							// 'date', 'date_out');
	// $array_search = array('empno', 'LastName');
							
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id";
	
	/* DB table to use */
	$sTable = "tbl_doc_review_masterlist";
	
	$database_config = '../db_config/config_acdcs.php';
	$common_function_handler = '../handler/common_function_handler.php';

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
		$sOrder = " ORDER BY id DESC";
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
	
	// if($sWhere == ""){
		// $sWhere .= "WHERE  ";
	// }else{
		// $sWhere .= "AND ";
	// }

	// $sWhere  .= " (created_by = '$username' OR judged_by_approver = '$username') AND logdel='0'";
	// $username 	= $_GET['username'];
	// $sql_where 	= $_GET['wh'];
	// if($sql_where != ""){
	// 	$sWhere 	= $sql_where;
	// }
	
	if($sWhere == ""){
		$sWhere .= "WHERE  ";
	}else{
		$sWhere .= "AND ";
	}
	$sWhere .= " `deleted_at` IS NULL AND is_expiration_date_exist = 'true'"; //nmodify
    
	// $sWhere .= " `status` = 0 OR `status` = 2 OR `status` = 8 OR `status` = 'A' AND `logdel` = 0";
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
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
	);
	/*
		Class Object
	*/
	$request_doc_review_disposition = new DocuReviewDisposition;

	while ( $aRow = mysql_fetch_array( $rResult ) )
    {
		$row = array();
		unset($row);

		$doc_review_masterlist_id = $aRow['id'];
		$fkid_document = $aRow['fkid_document'];
	/* 
		Button
	*/
        $getActiveDocumentById = getActiveDocumentById($fkid_document);
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
		$arr_doc_review_disposition = $request_doc_review_disposition->getDocReviewDispositionById($doc_review_masterlist_id);
		if($arr_doc_review_disposition['disposition'] == 1){
			$doc_review_disposition = 'Revision';
		}else if($arr_doc_review_disposition['disposition'] == 2){
			$doc_review_disposition = 'Retain';
		}else if($arr_doc_review_disposition['disposition'] == 3){
			$doc_review_disposition = '<span class="">Obsolete';
		}else if($arr_doc_review_disposition['disposition'] == 4){
			$doc_review_disposition = 'Extended';
		}else{
			$doc_review_disposition = '';
		}

		$row[]= '<center><input data-id="'.$doc_review_masterlist_id.'" id="cBoxBatchSelect" class="form-control" type="checkbox"> </center>';
		$row[]= $button;
		$row[]= $getActiveDocumentById['fkid_document'];
		$row[]= '<center> '.$status.' </center>';
		$row[]= '<center> ' .$doc_review_disposition. ' </center>';
		$row[]= $getActiveDocumentById['date_received'];
		$row[]= $getActiveDocumentById['model'];
		$row[]= $getActiveDocumentById['doc_title'];
		$row[]= $getActiveDocumentById['doc_no'];
		$row[]= $getActiveDocumentById['rev_no'];
		array_push($output['aaData'],$row);
	}
	echo json_encode( $output );

	/* 
		Functions 
	*/
    function getActiveDocumentById($fkid_document)
    {
        require_once('../class/oop_acdcs.php');
        $return = $_POST;
		$array_fields = array('	masterlist.id AS doc_review_masterlist_id,masterlist.document_status,
								masterlist.created_at, active. *,docs.pkid AS doc_pkid,docs.section'
		);
		$table 	   	= 'tbl_active_docs active';
		$joins 	   	= 'LEFT JOIN tbl_documents docs ON docs.pkid = active.fkid_document';
		$joins 	   	.=' RIGHT JOIN tbl_doc_review_masterlist masterlist ON masterlist.fkid_document = docs.pkid';
		$sql_where 	=  'WHERE active.view_status = 1';
		$sql_where 	.= ' AND active.logdel = 0';
		$sql_where 	.= ' AND masterlist.fkid_document = '.$fkid_document.'';
		$sql_where 	.= ' AND masterlist.deleted_at IS NULL';
		$sql_order 	= 'ORDER BY active.doc_no DESC';
		$sql_limit 	= '';
		$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        while($row = mysqli_fetch_array($query)){
            $return['fkid_document'] = $row['fkid_document'];
            $return['document_status'] = $row['document_status'];
            $return['date_received'] = $row['date_received'];
            $return['model'] = $row['model'];
            $return['doc_title'] = $row['doc_title'];
            $return['doc_no'] = $row['doc_no'];
            $return['rev_no'] = $row['rev_no'];
            // $return['originator_code'] = $row['originator_code'];
            // $return['doc_type'] = $row['doc_type'];
            // $return['effectivity_date'] = $row['effectivity_date'];
            // $return['created_at'] = $row['created_at'];
        }
        return $return;
    }

/*
====GROUP BY originator code===

SELECT originator_code, COUNT( * )
FROM `tbl_active_docs` active
WHERE originator_code IS NOT NULL
GROUP BY originator_code
ORDER BY `active`.`originator_code` DESC
LIMIT 0 , 100
========================

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

SELECT SQL_CALC_FOUND_ROWS  `masterlist`.id AS `doc_review_masterlist_id` , `masterlist`.document_status,
`masterlist`.created_at, `active`. * , `docs`.`pkid` AS `doc_pkid` , `docs`.`section`
FROM `tbl_active_docs` active LEFT JOIN tbl_documents docs ON docs.pkid = active.fkid_document 
RIGHT JOIN `tbl_doc_review_masterlist` masterlist ON masterlist.fkid_document = docs.pkid 
WHERE active.view_status = 1 AND active.`logdel` = 0 AND masterlist.fkid_document = 142587 AND masterlist.deleted_at IS NULL
ORDER BY `active`.`doc_no` DESC;

SELECT `active`. * , `docs`.`pkid` AS `doc_pkid`, `docs`.`section`
FROM `tbl_active_docs` active
LEFT JOIN tbl_documents docs ON docs.pkid = active.fkid_document
RIGHT JOIN `tbl_doc_review_masterlist` masterlist ON masterlist.fkid_document = docs.pkid
WHERE active.view_status =1
AND active.`logdel` =0
AND docs.doc_status =1
ORDER BY `active`.`doc_no` DESC
LIMIT 0 , 10
    
*/
?>
