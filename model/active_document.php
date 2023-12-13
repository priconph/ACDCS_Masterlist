<?
namespace Model;
use ACDCS;

class ActiveDocument {
	public static function getActiveDocumentById($fkid_document)
    {
        try {
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
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public static function activeDocsByFkid($fkid_document) 
	{
		require_once('../class/oop_acdcs.php');
		$request = array();
		$array_fields = array('*');
		$table 	   	= 'tbl_active_docs';
		$joins 	   	= '';
		$sql_where 	= 'WHERE fkid_document = '.$fkid_document.'';
		$sql_order 	= '';
		$sql_limit 	= 'LIMIT 0,1';
		$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($query)){
			$request['date_received'] 			= $row['date_received'];
			$request['model'] 					= $row['model'];
			$request['originator_code'] 		= $row['originator_code'];
			$request['doc_title'] 				= $row['doc_title'];
			$request['doc_no'] 					= $row['doc_no'];
			$request['rev_no'] 					= $row['rev_no'];
			$request['page_no'] 		        = $row['page_no'];
			$request['expiration_date'] 		= $row['expiration_date'];
			$request['effectivity_date'] 		= $row['effectivity_date'];
		}
		return $request;
	}
}

?>