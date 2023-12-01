<?php

namespace Model;
class Hris {
    function get_emp_name_by_username_systemone($username) {
		if(file_exists('../class/oop_acdcs.php')) {
			require_once('../class/oop_acdcs.php');
		} else {
			require_once('../../class/oop_acdcs.php');
		}

		$emp_name		= 'Not found!';
		$array_fields 	= array('CONCAT(`firstName`," ",`lastname`) as emp_name');
		$table			= 'vw_EmpInfo_Rapid';
		$joins			= '';
		$sql_where		= 'WHERE username="'.$username.'"';
		$sql_order		= '';
		$sql_limit		= 'LIMIT 0,1';
		$html_select	= '';
		$result			= SYSTEMONE::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)) {
			$emp_name = $row['emp_name'];
		} else {
			$array_fields 	= array('`name` as emp_name');
			$table			= 'db_rapid.tbl_useraccounts';
			$joins			= '';
			$sql_where		= 'WHERE username="'.$username.'"';
			$sql_order		= '';
			$sql_limit		= 'LIMIT 0,1';
			$html_select	= '';
			$result			= RAPID::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			// $emp_name			= TQTS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			if($row = mysqli_fetch_array($result)) {
				$emp_name = $row['emp_name'];
			}
		}
		return $emp_name;
	}
}