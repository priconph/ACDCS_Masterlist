<?php
$oop = 'class/oop_user_roles.php';
if(!file_exists($oop)){
	if(file_exists('../../'.$oop)) {
		require_once('../../'.$oop);
	} else {
		echo '<br><br><br>
				<h1><center>OOP does not exist!!!</center>
			  </h1>';
		exit;
	}
}else{
	require_once($oop);
}

return $username = $_SESSION['username'];

?>