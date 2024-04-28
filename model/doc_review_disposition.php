<?php
namespace Model;
// require_once('../model/systemone.php');

use Model\Hris;
use ACDCS;
class DocReviewDisposition{

    public function getDocReviewDisposition($doc_review_disposition_id)
    {
        try {
            // require_once('../class/oop_acdcs.php'); //if error to email uncomment then look for excel_download
            $request = array();
            $array_fields = array('*');
            $table 	   	= 'tbl_doc_review_disposition';
            $joins 	   	= '';
            $sql_where 	= ' WHERE id = '.$doc_review_disposition_id.' ';
            $sql_order 	= '';
            $sql_limit 	= '';
            $script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
            $query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
            while($row = mysqli_fetch_array($query)){
                $request['tbl_doc_review_masterlist_id'] 	= $row['tbl_doc_review_masterlist_id'];
                $request['disposition'] 	                = $row['disposition'];
                $request['memo_file_name'] 	                = $row['memo_file_name'];
                $request['memo_file_filtered_name'] 	    = $row['memo_file_filtered_name'];
                $request['due_date'] 	                    = $row['due_date'];
            }
            return $request;
        }catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getDocReviewDispositionDescending($doc_review_masterlist_id)
    {
        try {
            require_once('../class/oop_acdcs.php'); //if error to email uncomment then look for excel_download
            $request = array();
            $array_fields = array('*');
            $table 	   	= 'tbl_doc_review_disposition';
            $joins 	   	= '';
            $sql_where 	= 'WHERE tbl_doc_review_masterlist_id = '.$doc_review_masterlist_id.' ';
            $sql_order 	= 'ORDER BY id DESC';
            $sql_limit 	= 'LIMIT 0,1';
            $script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
            $query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
            if($row = mysqli_fetch_array($query)){
                $request['id'] 	= $row['id'];
                $request['is_exist'] = 1; //true
            }else{
                $request['is_exist'] = 0; //false
            }
            return $request;
        }catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getDocReviewMasterlist($doc_review_disposition_id)
    {
        try {
            // require_once('../class/oop_acdcs.php'); //if error to email uncomment then look for excel_download
            $request = array();
            $array_fields = array('*');
            $table 	   	= 'tbl_doc_review_disposition';
            $joins 	   	= '';
            $sql_where 	= ' WHERE id = '.$doc_review_disposition_id.' ';
            $sql_order 	= '';
            $sql_limit 	= '';
            $script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
            $query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
            while($row = mysqli_fetch_array($query)){
                $request['tbl_doc_review_masterlist_id'] 	= $row['tbl_doc_review_masterlist_id'];
                $request['disposition'] 	                = $row['disposition'];
                $request['memo_file_name'] 	                = $row['memo_file_name'];
                $request['memo_file_filtered_name'] 	    = $row['memo_file_filtered_name'];
            }
            return $request;
        }catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function getDocReviewDispositionById($doc_review_masterlist_id)
    {
        try {
            require_once('../class/oop_acdcs.php');
            $request = $_POST;
            $array_fields = array('*');
            $table 	   	= 'tbl_doc_review_disposition';
            $joins 	   	= '';
            $sql_where 	=  'WHERE tbl_doc_review_masterlist_id = '.$doc_review_masterlist_id.' AND deleted_at IS NULL';
            $sql_order 	= '';
            $sql_limit 	= '';
            $script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
            $query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
            if($query->num_rows > 0){
                while($row = mysqli_fetch_array($query)){
                    $return['id'] = $row['id'];
                    $return['disposition'] = $row['disposition'];
                }
            }else{
                $return['disposition'] = 0; //no disposition
            }
            return $return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getDocReviewDispositionApprovalDetailsById($doc_review_masterlist_id)
    {
        require_once('../class/oop_acdcs.php');
        require_once('../model/systemone.php');

        $request = $_POST;
        $array_fields = array('disposition.*,approval.sec_heads_username,approval.tbl_doc_review_disposition_id,approval.remarks,disposition.remarks as process_owner_remarks');
        $table 	   	= 'tbl_doc_review_disposition disposition';
        $joins 	   	= 'LEFT JOIN tbl_doc_review_disposition_approval_details approval ON approval.tbl_doc_review_disposition_id = disposition.id';
        $sql_where 	=  'WHERE disposition.tbl_doc_review_masterlist_id = '.$doc_review_masterlist_id.' AND disposition.deleted_at IS NULL';
        $sql_where 	.=  ' AND approval.deleted_at IS NULL';
        $sql_order 	= '';
        $sql_limit 	= '';
        $script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        $query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        if($query->num_rows > 0){
            while($row = mysqli_fetch_array($query)){
                $return['id'] = $row['id'];
                $return['disposition'] = $row['disposition'];
                $return['due_date'] = $row['due_date'];
                $return['extension_date'] = $row['extension_date'];
                $return['remarks'] = $row['remarks'];
                $return['process_owner_remarks'] = $row['process_owner_remarks'];
                $reviewed_by[] = $row['reviewed_by'];
                $person_in_charge[] = $row['person_in_charge'];
                $sec_heads_username[] = $row['sec_heads_username'];
            }
            foreach ($person_in_charge as $key => $value) {
                $array_data_app 				= array();
                $array_data_app['id'] 			= $value;
                $array_data_app['text'] 		= Hris::get_emp_name_by_username_systemone($value);
                $return['person_in_charge'][]	= $array_data_app;
            }
            foreach ($sec_heads_username as $key => $value) {
                $array_data_app 				= array();
                $array_data_app['id'] 			= $value;
                $array_data_app['text'] 		= Hris::get_emp_name_by_username_systemone($value);
                $return['sec_heads_username'][]	= $array_data_app;
            }
            foreach ($reviewed_by as $key => $value) {
                $array_data_app 				= array();
                $array_data_app['id'] 			= $value;
                $array_data_app['text'] 		= Hris::get_emp_name_by_username_systemone($value);
                $return['reviewed_by'][]	= $array_data_app;
            }

        }else{
            $return['result'] = 'Data not found ';
            $return['disposition'] = 0; //no disposition
        }
        return $return;
    }

    public function getDispositionCode($disposition_code){
        try {
            switch ($disposition_code) {
                case 1:
                    return $doc_review_disposition = '<span class="badge highlight-color-red">Revision</span>';
                    break;
                case 2:
                    return $doc_review_disposition = 'Retain';
                    break;
                case 3:
                    return $doc_review_disposition = '<span class="badge highlight-color-red">Obsolete</span>';
                    break;
                case 4:
                    return $doc_review_disposition = 'Extended';
                    break;
                default:
                    return $doc_review_disposition = '';
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


}