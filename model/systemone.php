<?php

namespace Model;
use SYSTEMONE;
use RAPID;
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
    function get_section_by_username_systemone($username) {
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

	function getDeptIdByUserName($user_name){
		if(file_exists('../class/oop_acdcs.php')) {
			require_once('../class/oop_acdcs.php');
		} else {
			require_once('../../class/oop_acdcs.php');
		}

		$section		= 'Not found!';
		$array_fields 	= array('`Dept_id`,`Department`');
		$table			= 'vw_ACDCS';
		$joins			= '';
		$sql_where		= 'WHERE username="'.$user_name.'"';
		$sql_order		= '';
		$sql_limit		= 'LIMIT 0,1';
		$html_select	= '';
		$result			= SYSTEMONE::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)) {
			$section = $row['Dept_id'];
		}else{
			$section = 'Username Not Found !';

		}
		return $section;
	}
}