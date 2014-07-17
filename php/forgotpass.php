<?php

/**
 * @author 
 * @copyright 2014
 */

require("library.php"); // include the library file
require("classes/class.phpmailer.php"); // include the class name
require("mail/mailSender.php"); // include the class name
require("db.php");

/**include 'class.VcsApi.php';*/
session_start();
 
 
$length = 8;
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
$randpassword = substr( str_shuffle( $chars ), 0, $length );
 

 
 
//$email =$_POST['email'];
$email = "deshmukh.lokesh@gmail.com";
$email = stripslashes($email);
$email = $mysqli->real_escape_string($email);
 
$sql = "SELECT * FROM user WHERE email = '$email'";

$result = array();

$ResetLink = BASEURL."?esource=".base64_encode($email)."#passrecovery";
/*
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
*/
  $ip = $_SERVER['REMOTE_ADDR'];
$RequestDateTime = date('Y/m/d H:i:s');

$array_content[]=array("User_EmailAddress", $email);  
$array_content[]=array("Link_ForgotPassword", $ResetLink);
$array_content[]=array("Time_Request",  $RequestDateTime);
$array_content[]=array("IP_Requested", $ip);


if($resultdb =  $mysqli->query($sql))
{
    $count = $resultdb->num_rows;
    if($count > 0)
    {
	  while($r = $resultdb->fetch_assoc())
	  {
	    $userID = $r['id'];
	  	$mailsender =  new mailsender;
	   
	   if($isMailSent =	$mailsender->send_phpmailer($array_content, "mailTemplate/resetPassword.html","classes/class.phpmailer.php",SMTP_FROMNAME,SMTP_FROM,$email,"Reset your password"))
	   {
	      //mailsender::send_phpmailer($array_content, "mailTemplate/forgotPassword.html","classes/class.phpmailer.php",SMTP_FROMNAME,SMTP_FROM,$email,"Reset your password");
		  
		 
		    $updateQuery = "UPDATE user SET ";
		    $updateQuery .= "lastPassRequest = '$RequestDateTime' ";
		  
		    $updateQuery .= " WHERE id='$userID'";
		    $mysqli->query($updateQuery);	
		 
			$result['success'] = true;
			$result['msg'] = "Password has been sent successfully";
	  }
	  else
	  {
		 $result['success'] = false;
		 $result['msg'] = "Sorry, we have problem in sending you email with Reset Password link. Please contact support.";
	  }
       }
	}
	else
	{
		$result['success'] = false;
		$result['msg'] = "We are sorry that There is no such user has registered with us";
	}
}
else
{
	$result['success'] = false;
	$result['msg'] = "Sorry, we have problem in identifying you in our system. Please contact support.";

}

echo json_encode($result);


 
?>