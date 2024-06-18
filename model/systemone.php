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
		$script			= SYSTEMONE::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$result			= SYSTEMONE::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)) {
			$section = $row['Dept_id'];
		}else{
			$section = 'Username Not Found !';
		}
		return $section;
	}
	function getDepartmentByDeptId($arr_dept_id){
		if(file_exists('../class/oop_acdcs.php')) {
			require_once('../class/oop_acdcs.php');
		} else {
			require_once('../../class/oop_acdcs.php');
		}
		$section	=  array();
		$arr_sql_or = array();
		$array_fields 	= array('`pkid`,`Department`');
		$table			= 'tbl_Department';
		$joins			= '';
		$sql_where		= 'WHERE 1=1';
		foreach ($arr_dept_id as $key => $value) {
			$arr_sql_or[] = ' `pkid` = "'.$arr_dept_id[$key].'" ';
		}
		if(count($arr_dept_id) == 1){
			$sql_where 	.= ' AND `pkid` = "'.$arr_dept_id[0].'" ';
		}else if(count($arr_dept_id) > 1){
			$sql_where 	.= ' AND '.implode(' OR',$arr_sql_or).'';
		}
		$sql_order		= '';
		$sql_limit		= '';
		$html_select	= '';
		$script			= SYSTEMONE::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$result			= SYSTEMONE::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($result)) {
			$section[] = $row['Department'];
		}
		return $section;
	}
	function getArrDepartmentByDeptId($arr_dept_id){
		if(file_exists('../class/oop_acdcs.php')) {
			require_once('../class/oop_acdcs.php');
		} else {
			require_once('../../class/oop_acdcs.php');
		}
		$arr_sql_or = array();
		$array_fields 	= array('`pkid`,`Department`');
		$table			= 'tbl_Department';
		$joins			= '';
		$sql_where		= 'WHERE 1=1';
		$sql_where		.= ' AND `pkid` = "'.$arr_dept_id.'" ';
		$sql_order		= '';
		$sql_limit		= '';
		$html_select	= '';
		$script			= SYSTEMONE::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$result			= SYSTEMONE::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($result)) {
			$section = $row['Department'];
		}
		return $section;
	}
}