<?php
namespace Model;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class Rapid{
	public static function getIsDccStaffValid($user_name){
		$oop = 'db_config/config_rapid.php';
		if(!file_exists($oop)){
			if(file_exists('../'.$oop)){
				require_once('../'.$oop);
			}
			else if(file_exists('../../'.$oop)) {
				require_once('../../'.$oop);
			} else if(file_exists('../../../'.$oop)){
                require_once('../../../'.$oop);
            }else {
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
		// $sql  = 'SELECT COUNT(*) as approver_count FROM tbl_doc_review_disposition_approval_details WHERE sec_heads_username = "'.$username.'"';
		$sql  = 'SELECT * FROM `tbl_useraccounts` WHERE `username` = "'.$user_name.'" LIMIT 0,1';
		$result = mysqli_query($conn,$sql);
		if($data = mysqli_fetch_array($result)){
			$return['username'][] 	= $user_name;
			$return['department'][] 	= $data['department'];
			$return['section'][] 		= $data['section'];
			$return['division'][] 	= $data['division'];
		}
		return $return;
	}
}



?>