<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* call connectio information from text file */
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
// $username = $_SESSION['username'];
$sql  = 'SELECT COUNT(*) as approver_count FROM tbl_doc_review_disposition_approval_details WHERE sec_heads_username = "'.$_SESSION['username'].'"';
$result = mysqli_query($conn,$sql);
while($data = mysqli_fetch_assoc($result)){
    return $data['approver_count'];
}