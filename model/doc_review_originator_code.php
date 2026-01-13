<?
namespace Model;

use ACDCS;

class DocReviewOriginatorCode {
    function getDeptIdById($department_id){
        if(file_exists('../class/oop_acdcs.php')) {
            require_once('../class/oop_acdcs.php');
        } else {
            require_once('../../class/oop_acdcs.php');
        }
        
        $return		= array();
        $array_fields 	= array('`originator_code`');
        $table			= 'tbl_doc_review_originator_code';
        $joins			= '';
        $sql_where		= 'WHERE 1=1';
        $sql_where		.= ' AND department_id='.$department_id.' ';
        $sql_where		.= ' AND deleted_at IS NULL';
        if($department_id != 51 && $department_id != 5){ // Administration dept cannot access operation documents
            $sql_where		.= ' OR department_id = 0';
        }
        if($department_id == 51 || $department_id == 5){ // Administration dept has - in systemone
            $sql_where		.= ' OR department_id = 5 OR department_id = 51';
        }
        $sql_order		= '';
        $sql_limit		= '';
        $html_select	= '';
        $script	= ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        $result			= ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        while($row = mysqli_fetch_array( $result)) {
            $return[] = $row['originator_code'];
        }
        return $return;
    }
    function getOriginatorCodeByOriginatorCode($originator_code){
        if(file_exists('../class/oop_acdcs.php')) {
            require_once('../class/oop_acdcs.php');
        } else {
            require_once('../../class/oop_acdcs.php');
        }
        $return		= array();
        $array_fields 	= array('`department_id`');
        $table			= 'tbl_doc_review_originator_code';
        $joins			= '';
        $sql_where		= 'WHERE 1=1';
        $sql_where		.= ' AND originator_code ="'.$originator_code.'" AND deleted_at IS NULL';
        $sql_order		= '';
        $sql_limit		= '';
        $html_select	= '';
        $script			= ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        $result			= ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        while($row = mysqli_fetch_array($result)) {
            $return[] = $row['department_id'];
        }
        return $return;
    }
}