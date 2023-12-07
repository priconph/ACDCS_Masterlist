<?php
namespace Model;
require_once('../class/oop_mailer.php');

use MAILER as Mailer;
use RAPID;
use SYSTEMONE;

class RapidEmailer
{
	function get_fields_values($passed_param,$excluded_value)
	{
		$array_fields = array();
		$array_values = array();
		foreach($passed_param as $key => $value){
		   if(in_array($key,$excluded_value)){
			   continue;
		   }
		   $array_fields[] = $key;
		   if(is_array($value)){
			   $array_values[] = implode(',',$value);
		   }else{
			   $array_values[] = $value;
		   }
	   }
	   $return = array();
	   $return['array_fields'] = $array_fields;
	   $return['array_values'] = $array_values;
	   return $return;
   	}

    /*Save Scheduled e-mail in tbl_auto_mailer */
	function send_with_auto_mailer($mail_data)
	{
		$result = "";
		/* 
			Instructions for data values
			Array should contain the following data:
			1. to			= separator is comma
			2. cc			= separator is comma
			3. bcc			= separator is comma
			4. from			
			5. from_name
			6. subject
			7. message
			8. system_name
			9. send_date_time
		*/
		$table				 = 'tbl_auto_mailer';
		$array_fields_values = $this->get_fields_values($mail_data,array('username'));
		$array_fields		 = $array_fields_values['array_fields']; 
		$array_values		 = $array_fields_values['array_values']; 
		$array_fields[]		 = 'date_created'; $array_values[] = date('Y-m-d H:i:s');
		$result = Mailer::getInstance()->insert_query($table,$array_fields,$array_values);
		$script = Mailer::getInstance()->insert_query_script($table,$array_fields,$array_values);
		return $script;
	}
	
	/*Save E-mail in tbl_mailer */
	function send_with_mailer($mail_data)
	{
		require_once('../class/oop_mailer.php');
		$result = "";
		/* 
			Instructions for data values
			Array should contain the following data:
			1. to			= separator is comma
			2. cc			= separator is comma
			3. bcc			= separator is comma
			4. from			
			5. from_name
			6. subject
			7. message
			8. system_name
			9. send_date_time
		*/
		$table				 = 'tbl_mailer';
		$array_fields_values = $this->get_fields_values($mail_data,array('username'));
		$array_fields		 = $array_fields_values['array_fields']; 
		$array_values		 = $array_fields_values['array_values']; 
		$array_fields[]		 = 'date_created'; $array_values[] = date('Y-m-d H:i:s');
		$array_fields[]		 = 'created_by'; $array_values[] = $mail_data['username'];
		$result = MAILER::getInstance()->insert_query($table,$array_fields,$array_values);
		$script = MAILER::getInstance()->insert_query_script($table,$array_fields,$array_values);
		return $script;
	}

	function get_user_email_add_by_username($username) {
		require_once('../class/oop_acdcs.php');
		/* Check first if employee has email address to Rapid database. If none, get the email address to SystemOne. Else, display empty. */
		$result = "";
		$array_fields = array('email_add','emp_type');
		$table 	   	= 'db_rapid.tbl_useraccounts';
		$joins 	   	= '';
		$sql_where 	= 'WHERE username="'.$username.'"';
		$sql_order 	= '';
		$sql_limit 	= 'LIMIT 0,1';
		$result = RAPID::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($result->num_rows != 0) {
			if($row = mysqli_fetch_array($result)){
				$email_add = $row['email_add'];
				$emp_type  = $row['emp_type'];
				if($emp_type == 'Pricon Employee' && $email_add == '') {
					/* Get the email address to SystemOne and update the record to Rapid database */
					$array_fields2 = array('email_add');
					$table2 	   	= 'db_acl.tbl_useraccnt';
					$joins2 	   	= '';
					$sql_where2 	= 'WHERE username="'.$username.'"';
					$sql_order2 	= '';
					$sql_limit2 	= 'LIMIT 0,1';
					$result2 = SYSTEMONE::getInstance()->select_query($array_fields2,$table2,$joins2,$sql_where2,$sql_order2,$sql_limit2);
					if($row2=mysqli_fetch_array($result2)) {
						$email_add = $row2['email_add'];
					} else {
						$email_add = 'NONE';
					}
				} else if($emp_type == 'Pricon Employee' && $email_add != '') {
					$email_add = $row['email_add'];
				} else if($emp_type == 'Subcon Hired' && $email_add == '') {
					$email_add = 'NONE';
				} else {
					$email_add = $row['email_add'];
				}
			}
		} else {
			$email_add = 'NONE';
		}
		return $email_add;
	}
}

class EmailDetail extends RapidEmailer 
{
	public static function send_email($to, $from, $cc, $subject, $message) 
	{
		// return $subject;
		$return 	= array();
		$mail_data  = array();
		$message .= "<br><br>
					 For more info, please log-in to your Rapid account. Go to http://rapid/ and click http://rapid/ACDCS_Masterlist/index.php ACDCS Document Review Process
					 <br><br>Notice of Disclaimer:<br>
                     This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail_data  = array(
			"`to`"				=> $to,
			"cc"				=> $cc,
			"bcc"				=> 'mclegaspi@pricon.ph',
			"`from`"			=> $from,
			"from_name"			=> 'ACDCSystemNotification@pricon.ph',
			"subject"			=> $subject,
			"message"			=> $message,
			"send_date_time"	=> date('Y-m-d H:i:s'),
			"system_name"		=> 'ACDCS_DRM',
			"username"			=> $from
		);
		$rapid_mailer = new RapidEmailer;
		$mail_result = $rapid_mailer->send_with_mailer($mail_data);
		// $mail_result = send_with_auto_mailer($mail_data);
		$return['mail_result'] 		= $mail_result;
		$return['mail_data'] 		= $mail_data;
		return $return;
	}

	public static function send_email_with_attachment($to, $from, $cc, $subject, $message, $attachment_filename, $attachment) 
	{
		/* 
			NOTE: PLEASE READ!!
			Attachment should be delimited by comma
			Example: 
			TQTS_edited/uploaded_file/test_file.jpg,TQTS_edited/uploaded_file/test_file_2.jpg
		*/
		$common_function = '../handler/common_function.php';
		if(file_exists($common_function)){
			require_once($common_function);
		}else{
			return "Common function not found";
		}
		
		// if(trim($from) == 'delaman@pricon.ph') {
			// $from_name = 'Dell Amandoron';
		// } else {
			$from_name = get_emp_name_by_email_add_systemone($from);
		// }
		
		$return 	= array();
		$mail_data  = array();
		$message .= "<br><br>
					 For more info, please log-in to your Rapid account. Go to http://rapid/ and click http://rapid/ACDCS_Masterlist/index.php ACDCS Document Review Process
					 <br><br>Notice of Disclaimer:<br>
                     This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail_data  = array(
			"`to`"					=> $to,
			"cc"					=> $cc,
			// "`to`"				=> 'rdfernandez@pricon.ph',
			// "cc"					=> 'rdfernandez@pricon.ph',
			"bcc"					=> 'mclegaspi@pricon.ph,'.$from,
			// "bcc"					=> 'mclegaspi@pricon.ph,',
			"`from`"				=> $from,
			"from_name"				=> $from_name,
			"subject"				=> $subject,
			"message"				=> $message,
			"send_date_time"		=> date('Y-m-d H:i:s'),
			"system_name"			=> 'ACDCS_DRM',
			"attachment_filename" 	=> $attachment_filename, 
			"attachment" 			=> $attachment, 
			"username"				=> $from
		);
		$mail_result = send_with_mailer_with_attachment($mail_data);
		$return['mail_result'] 		= $mail_result;
		$return['mail_data'] 		= $mail_data;
		return $return;
	}
	
	public static function send_email_detailed($to, $from, $from_name, $cc, $bcc, $subject, $message, $send_date_time, $username) 
	{
		$common_function = '../handler/common_function.php';
		if(file_exists($common_function)){
			require_once($common_function);
		}else{
			return "Common function not found";
		}
		$return 	= array();
		$mail_data  = array();
		$message .= "<br><br>
					 For more info, please log-in to your Rapid account. Go to http://rapid/ and click http://rapid/ACDCS_Masterlist/index.php ACDCS Document Review Process
					 <br><br>Notice of Disclaimer:<br>
                    This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail_data  = array(
			"`to`"				=> $to,
			"cc"				=> $cc,
			"bcc"				=> $bcc,
			"`from`"			=> $from,
			"from_name"			=> 'ACDCSystemNotification@pricon.ph',
			"subject"			=> $subject,
			"message"			=> $message,
			"send_date_time"	=> $send_date_time,
			"system_name"		=> 'ACDCS_DRM',
			"username"			=> $username
		);
		$mail_result = send_with_mailer($mail_data);
		$return['mail_result'] 		= $mail_result;
		$return['mail_data'] 		= $mail_data;
		return $return;
	}

	public static function send_scheduled_email($tbl_name, $fkid, $to, $from, $cc, $subject, $message , $date_start, $frequency)
	{
		$return 	= array();
		$mail_data  = array();
		$message .= "<br><br>
					 For more info, please log-in to your Rapid account. Go to http://rapid/ and click http://rapid/ACDCS_Masterlist/index.php ACDCS Document Review Process
					 <br><br>Notice of Disclaimer:<br>
                    This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail_data  = array(
			"`table_name`"			=> $tbl_name,
			"`fkid`"				=> $fkid,
			"`to`"					=> $to,
			"cc"					=> $cc,
			"bcc"					=> 'mclegaspi@pricon.ph',
			"`from`"				=> $from,
			"from_name"				=> 'ACDCSystemNotification@pricon.ph',
			"subject"				=> $subject,
			"date_start"			=> $date_start,
			// "last_date_time_sent"	=> date('Y-m-d').' 07:30:00',
			"message"				=> $message,
			"frequency"				=> $frequency,
			"system_name"			=> 'ACDCS_DRM',
			"created_by"			=> $from
		);
		$rapid_mailer = new RapidEmailer;
		$mail_result = $rapid_mailer->send_with_auto_mailer($mail_data);
		$return['mail_result'] 		= $mail_result;
		$return['mail_data'] 		= $mail_data;
		return $return;
	}
	
	public static function send_scheduled_email_with_attachment($tbl_name, $fkid, $to, $from, $cc, $subject, $message, $attachment_filename, $attachment, $date_start, $frequency) {
		$common_function = '../handler/common_function.php';
		if(file_exists($common_function)){
			require_once($common_function);
		}else{
			return "Common function not found";
		}
		$return 	= array();
		$mail_data  = array();
		$message .= "<br><br>
					 For more info, please log-in to your Rapid account. Go to http://rapid/ and click http://rapid/ACDCS_Masterlist/index.php ACDCS Document Review Process
					 <br><br>Notice of Disclaimer:<br>
                    This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail_data  = array(
			"`table_name`"			=> $tbl_name,
			"`fkid`"				=> $fkid,
			"`to`"					=> $to,
			"cc"					=> $cc,
			"bcc"					=> 'mclegaspi@pricon.ph',
			"`from`"				=> $from,
			"from_name"				=> 'ACDCSystemNotification@pricon.ph',
			"subject"				=> $subject,
			"date_start"			=> $date_start,
			"message"				=> $message,
			"frequency"				=> $frequency,
			"system_name"			=> 'ACDCS_DRM',
			"attachment_filename" 	=> $attachment_filename, 
			"attachment" 			=> $attachment, 
			"created_by"			=> $from
		);
		$mail_result = send_with_auto_mailer($mail_data);
		$return['mail_result'] 		= $mail_result;
		$return['mail_data'] 		= $mail_data;
		return $return;
	}


}

?>