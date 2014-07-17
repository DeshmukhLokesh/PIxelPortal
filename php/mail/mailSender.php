<?php

include '../library.php'; // include the library file
error_reporting(E_ALL);
class mailsender 
{
	function send_phpmailer ($var_array,$template,$phpmailer,$FromName,$From,$to,$Subject)
	{
	 
		if (!is_array($var_array))
		{
			//echo "first variable should be an array. ITS NOT !";
			exit;
		}
		require_once($phpmailer); // I changed this to require_once because i found that when i trued to look the class for multiple emails, the phpmailer class was recelared and hence caused issue. SO MADE THIS as require_once.

		
		$filename = $template;
		$fd = fopen ($filename, "r");
		$mailcontent = fread ($fd, filesize ($filename));
						
		foreach ($var_array as $key=>$value)
		{
			$mailcontent = str_replace("%%$value[0]%%", $value[1],$mailcontent );
		}
						
		$mailcontent = stripslashes($mailcontent);
					
		fclose ($fd);
		
		
		   $mail	= new PHPMailer; // call the class 
			$mail->IsSMTP(); 
		
			$mail->Host = SMTP_HOST; //Hostname of the mail server
			$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
			$mail->SMTPAuth = true; //Whether to use SMTP authentication
			$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
			$mail->Password = SMTP_PWORD; //Password for SMTP authentication
			$mail->AddReplyTo("riverpixel@gmail.com", "River Pixel - Portal Email Test"); //reply-to address
			$mail->SetFrom("riverpixel@gmail.com", "River Pixel - Portal Email Test"); //From address of the mail
			// put your while loop here like below,
			$mail->Subject = "River Pixel -  Forgot Password"; //Subject od your mail
			$mail->AddAddress($to, "userName"); //To address who will receive this email
			$mail->MsgHTML($mailcontent); 
			$mail->SMTPSecure = 'ssl';
			
			$send = $mail->Send(); //Send the mails
			
			if($send)
			{
			  return true;
			}
			else
			{
			  return false;
			}
			
			
		
		
	}
	
}
?>