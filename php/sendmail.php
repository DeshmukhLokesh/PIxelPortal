<?php
include 'library.php'; // include the library file
include "classes/class.phpmailer.php"; // include the class name
if(isset($_POST["send"])){
	$email = $_POST["email"];
	$userid = "2";// When user registered create user_id
	$mail	= new PHPMailer; // call the class 
	$mail->IsSMTP(); 
	//$mail->Mailer = "smtp";
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->SMTPAuth = true; //Whether to use SMTP authentication
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo("riverpixel@gmail.com", "Lokesh"); //reply-to address
	$mail->SetFrom("riverpixel@gmail.com", "Lokesh"); //From address of the mail
	// put your while loop here like below,
	$mail->Subject = "Test SMTP Mail"; //Subject od your mail
	$mail->AddAddress($email, "Pushkar"); //To address who will receive this email
	$mail->MsgHTML("<b>Hi, your first SMTP mail has been received. Great Job!.. <br/>
	<br> http://localhost/pixel1/confirm.php?userid=".base64_encode($userid)."&email_id=".base64_encode($email)."
	<br>
	<br/>by </b>"); //Put your body of the message you can place html code here
	//$mail->AddAttachment("images/asif18-logo.png"); //Attach a file here if any or comment this line, 
	$mail->SMTPSecure = 'ssl';
	//($mail);exit;
//$mail->Host = 'smtp.gmail.com';
	$send = $mail->Send(); //Send the mails
	//print_r($send); exit;
	if($send){
		echo '<center><h3 style="color:#009933;">Mail sent successfully</h3></center>';
	}
	else{
		echo '<center><h3 style="color:#FF3300;">Mail error: </h3></center>'.$mail->ErrorInfo;
	}
}
?>
