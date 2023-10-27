<?php
namespace Email;
require_once('../class/oop_mailer.php');

use MAILER as Mailer;
class RapidEmailer{
    /* Scheduled e-mail */
	function send_with_auto_mailer($mail_data){
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

	function get_fields_values($passed_param,$excluded_value){
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
}

class EmailDetail extends RapidEmailer{
	public static function send_email($to, $from, $cc, $subject, $message) {
		$common_function = '../handler/common_function.php';
		if(file_exists($common_function)){
			require_once($common_function);
		}else{
			return "Common function not found";
		}
		$return 	= array();
		$mail_data  = array();
		$message .= "<br><br>
					 For more info, please log-in to your Rapid account. Go to http://rapid/ and Total Quality Tracking System under My Systems 
					 <br><br>Notice of Disclaimer:<br>
                     This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail_data  = array(
			"`to`"				=> $to,
			"cc"				=> $cc,
			// "`to`"			=> 'rdfernandez@pricon.ph',
			// "cc"				=> 'rdfernandez@pricon.ph',
			"bcc"				=> 'mclegaspi@pricon.ph',
			"`from`"			=> $from,
			"from_name"			=> 'TQTSystemNotification@pricon.ph',
			"subject"			=> $subject,
			"message"			=> $message,
			"send_date_time"	=> date('Y-m-d H:i:s'),
			"system_name"		=> 'TQTS',
			"username"			=> $from
		);
		$mail_result = send_with_mailer($mail_data);
		// $mail_result = send_with_auto_mailer($mail_data);
		$return['mail_result'] 		= $mail_result;
		$return['mail_data'] 		= $mail_data;
		return $return;
	}
	
	public static function send_email_with_attachment($to, $from, $cc, $subject, $message, $attachment_filename, $attachment) {
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
					 For more info, please log-in to your Rapid account. Go to http://rapid/ and Total Quality Tracking System under My Systems 
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
			"system_name"			=> 'TQTS',
			"attachment_filename" 	=> $attachment_filename, 
			"attachment" 			=> $attachment, 
			"username"				=> $from
		);
		$mail_result = send_with_mailer_with_attachment($mail_data);
		$return['mail_result'] 		= $mail_result;
		$return['mail_data'] 		= $mail_data;
		return $return;
	}
	
	public static function send_email_detailed($to, $from, $from_name, $cc, $bcc, $subject, $message, $send_date_time, $username) {
		$common_function = '../handler/common_function.php';
		if(file_exists($common_function)){
			require_once($common_function);
		}else{
			return "Common function not found";
		}
		$return 	= array();
		$mail_data  = array();
		$message .= "<br><br>
					 For more info, please log-in to your Rapid account. Go to http://rapid/ and Total Quality Tracking System under My Systems 
					 <br><br>Notice of Disclaimer:<br>
                    This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail_data  = array(
			"`to`"				=> $to,
			"cc"				=> $cc,
			"bcc"				=> $bcc,
			"`from`"			=> $from,
			"from_name"			=> 'TQTSystemNotification@pricon.ph',
			"subject"			=> $subject,
			"message"			=> $message,
			"send_date_time"	=> $send_date_time,
			"system_name"		=> 'TQTS',
			"username"			=> $username
		);
		$mail_result = send_with_mailer($mail_data);
		$return['mail_result'] 		= $mail_result;
		$return['mail_data'] 		= $mail_data;
		return $return;
	}
	
	public static function send_scheduled_email($tbl_name, $fkid, $to, $from, $cc, $subject, $message , $date_start, $frequency) {
		// $common_function = '../handler/common_function.php';
		// if(file_exists($common_function)){
		// 	require_once($common_function);
		// }else{
		// 	return "Common function not found";
		// }
		$return 	= array();
		$mail_data  = array();
		$message .= "<br><br>
					 For more info, please log-in to your Rapid account. Go to http://rapid/ and Total Quality Tracking System under My Systems 
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
			"last_date_time_sent"	=> date('Y-m-d').' 07:30:00',
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
					 For more info, please log-in to your Rapid account. Go to http://rapid/ and Total Quality Tracking System under My Systems 
					 <br><br>Notice of Disclaimer:<br>
                    This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail_data  = array(
			"`table_name`"			=> $tbl_name,
			"`fkid`"				=> $fkid,
			"`to`"					=> $to,
			"cc"					=> $cc,
			"bcc"					=> 'mclegaspi@pricon.ph',
			"`from`"				=> $from,
			"from_name"				=> 'TQTSystemNotification@pricon.ph',
			"subject"				=> $subject,
			"date_start"			=> $date_start,
			"message"				=> $message,
			"frequency"				=> $frequency,
			"system_name"			=> 'TQTS',
			"attachment_filename" 	=> $attachment_filename, 
			"attachment" 			=> $attachment, 
			"created_by"			=> $from
		);
		$mail_result = send_with_auto_mailer($mail_data);
		$return['mail_result'] 		= $mail_result;
		$return['mail_data'] 		= $mail_data;
		return $return;
	}

	public function test_email_details($mail_data) {
		return $mail_data;
	}

}

?>