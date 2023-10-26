<?php 
class DocuReviewDisposition{

    public function __construct(){

    }
    public static function getDocReviewDispositionById($doc_review_masterlist_id)
    {
        require_once('../class/oop_acdcs.php');
        $request = $_POST;
		$array_fields = array('disposition');
		$table 	   	= 'tbl_doc_review_disposition';
		$joins 	   	= '';
		$sql_where 	=  'WHERE tbl_doc_review_masterlist_id = '.$doc_review_masterlist_id.' AND deleted_at IS NULL';
		$sql_order 	= '';
		$sql_limit 	= '';
		$script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        if($query->num_rows > 0){
			while($row = mysqli_fetch_array($query)){
				$return['disposition'] = $row['disposition'];
			}
		}else{
			$return['disposition'] = 0; //no disposition
		}
		return $return;
    }
}

?>