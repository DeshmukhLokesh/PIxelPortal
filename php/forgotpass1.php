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
 

 
 
$email =$_POST['email'];
$email = stripslashes($email);
$email = $mysqli->real_escape_string($email);
 
$sql = "SELECT * FROM user WHERE email = '$email'";

$result = array();

$array_content[]=array("User_EmailAddress", $email);  
$array_content[]=array("Link_ForgotPassword", "http://localhost/pixelportal/passwordrecovery");
$array_content[]=array("Time_Request", "123PM");
$array_content[]=array("IP_Requested", "1213.123..123");


mailsender::send_phpmailer($array_content, "mailTemplate/forgotPassword.htm","classes/class.phpmailer.php",SMTP_FROMNAME,SMTP_FROM,$email,"Reset your password");

//$mailsender =  new mailsender;
//$mailsender->send_phpmailer($array_content, "mailTemplate/forgotPassword.htm","classes/class.phpmailer.php",SMTP_FROMNAME,SMTP_FROM,$email,"Reset your password");
	   

$result['success'] = false;
$result['msg'] = "Query failed to execute";


echo json_encode($result);


 
?>