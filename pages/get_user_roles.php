<?php
namespace Pages\GetUserRoles;
use SYSTEMONE;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class DocReviewDispositionApprovalDetails{
		/* call connectio information from text file */
	public static function getIsApprover(){
		$oop = 'db_config/config_acdcs.php';
		if(!file_exists($oop)){
			if(file_exists('../../'.$oop)) {
				require_once('../../'.$oop);
			} else {
				echo '<br><br><br>
						<h1><center>'.$oop.' does not exist! Please contact the ISS! </center>
					</h1>';
				exit;
			}
		}else{
			require_once($oop);
		}
	
		/* connect to database  */
		$conn 		= mysqli_connect($server,$username,$password) or die("cannot connect server");
	
		$database 	= mysqli_select_db($conn,$db_name) or die("cannot connect database");
	
	
		/* Query Here  */
		$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
		if(!isset($_SESSION['username'])){
			session_destroy();
			echo '<div id="homeContainer">
					<div class="alert alert-info" style="width:90%;margin:auto;">
						<h2>You have been idle for quite some time, System automatically logout for security purposes.<br></h2>
						<h2>Please Relogin Your Account <h2>
					</div>
				</div>';
			exit;
		}
		$sql  = 'SELECT COUNT(*) as approver_count FROM tbl_doc_review_disposition_approval_details WHERE sec_heads_username = "'.$_SESSION['username'].'"';
		$result = mysqli_query($conn,$sql);
		while($data = mysqli_fetch_array($result)){
			return $data['approver_count'];
		}
	}
}

class Rapid{
	public static function getIsDccStaff(){
		$oop = 'db_config/config_rapid.php';
		if(!file_exists($oop)){
			if(file_exists('../../'.$oop)) {
				require_once('../../'.$oop);
			} else {
				echo '<br><br><br>
						<h1><center>'.$oop.' does not exist! Please contact the ISS! </center>
					</h1>';
				exit;
			}
		}else{
			require_once($oop);
		}
		/* connect to database  */
		$conn 		= mysqli_connect($server,$username,$password) or die("cannot connect server");

		$database 	= mysqli_select_db($conn,$db_name) or die("cannot connect database");


		/* Query Here  */
		// $username = $_SESSION['username'];
		$return =array();
		// $sql  = 'SELECT COUNT(*) as approver_count FROM tbl_doc_review_disposition_approval_details WHERE sec_heads_username = "'.$username.'"';
		$sql  = 'SELECT * FROM `tbl_useraccounts` WHERE `username` = "'.$_SESSION['username'].'" LIMIT 0,1';
		$result = mysqli_query($conn,$sql);
		while($data = mysqli_fetch_array($result)){
			$return['username'][] 	= $_SESSION['username'];
			$return['department'][] 	= $data['department'];
			$return['section'][] 		= $data['section'];
			$return['division'][] 	= $data['division'];
		}
		return $return;
	}
}

class Hris{
	public static function getDeptIdByUserName($user_name){
		$oop = 'db_config/config_hris.php';
		// $oop = 'class/oop_acdcs.php';
		if(!file_exists($oop)){
			if(file_exists('../../'.$oop)) {
				require_once('../../'.$oop);
			} else {
				echo '<br><br><br>
						<h1><center>'.$oop.' does not exist! Please contact the ISS! </center>
					</h1>';
				exit;
			}
		}else{
			require_once($oop);
		}
		
		/* connect to database  */
		$conn 		= mysqli_connect($server,$username,$password) or die("cannot connect server");

		$database 	= mysqli_select_db($conn,$db_name) or die("cannot connect database");


		/* Query Here  */
		$return =array();
		$sql  = 'SELECT * FROM `vw_ACDCS` WHERE `username` = "'.$_SESSION['username'].'" LIMIT 0,1';
		$result = mysqli_query($conn,$sql);
		while($data = mysqli_fetch_array($result)){
			$return['department'] 	= $data['Department'];
			$return['fullname'] 	= $data['firstname'] .' '. $data['lastname'];
		}
		return $return;
	}
}

?>