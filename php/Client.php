<?php

/**
 * @author 
 * @copyright 2014
 */

require("db.php");
require("library.php"); // include the library file
require("classes/class.phpmailer.php"); // include the class name
require("mail/mailSender.php"); // include the class name

 Class Client
 {
   //$Group_ID = 1;

	public function createUser($userName,$email,$password,$packageId,$jeraSoft_ID,$Phone)
	{ 
		session_start();
		
		$Picture = "profile_empty.png";
		/*
	 //   $mysqli->query("SET @errNum = 0");
	//	$mysqli->query("SET @UserID = 0");
      //  $mysqli->query("SET @errDesc = ''");
	//	if($resultdb =  $mysqli->query("CALL spSet_User('$jeraSoft_ID','$packageId',@UserID,'$userName','$password','$userName','$email','$Picture','$Phone', @errNum,@errDesc)"))
		
		$server = "127.0.0.1";
		$user = "root";
		$pass = "Soccer55";
		$dbName = "pixel1";

		$mysqli = new mysqli($server,$user,$pass,$dbName);
		*/
		$userName = stripslashes($userName);
		$userName = $mysqli->real_escape_string($userName);
		
		$email = stripslashes($email);
		$email = $mysqli->real_escape_string($email);
		
		$password = stripslashes($password);
		$password = $mysqli->real_escape_string($password);
		
		$packageId = stripslashes($packageId);
		$packageId = $mysqli->real_escape_string($packageId);
		
		$jeraSoft_ID = stripslashes($jeraSoft_ID);
		$jeraSoft_ID = $mysqli->real_escape_string($jeraSoft_ID);
		
		$Phone = stripslashes($Phone);
		$Phone = $mysqli->real_escape_string($Phone);
		
		
		
		$sql = "INSERT INTO user(userName,
							email,
							password,
							picture,
                            group_id,    
							jeraSoft_ID,
                            phone,
							isActive)
							VALUES('$userName','$email','$password','$Picture','1','$jeraSoft_ID','$Phone','0');";
	
		$_SESSION['PR_AUTH'] = true;
        $_SESSION['PR_EMAIL'] = $email;
		$_SESSION['PR_PROFILE_PICTURE'] = $Picture;
		$_SESSION['JS_USER_ID'] = $jeraSoft_ID;
		$_SESSION['JS_PACKAGE_ID'] = $packageId;
		$_SESSION['PP_STATUS'] = 'NA';
		
		$result = array();
		
		$mysqli->query('SET AUTOCOMMIT = 1');
		if($resultdb =  $mysqli->query($sql))
		{
		 
		    $user_id = $mysqli->insert_id;
			$_SESSION['PR_USER_ID'] = $user_id;
			
			$activationLink =  $ResetLink = BASEURL."?esource=".base64_encode($email)."#accountConfirm";
			$array_content[]=array("User_Name", $userName);  
            $array_content[]=array("Link_ActivateAccount", $activationLink);
			if($isMailSent =	$mailsender->send_phpmailer($array_content, "mailTemplate/activateAccount.html","classes/class.phpmailer.php",SMTP_FROMNAME,SMTP_FROM,$email,"Action Required to Activate Membership for Pixel River"))
	        {
			
			}
			
			self::addUserPackage($user_id,$packageId);
	   }
	   else
	   {
	      echo $mysqli->error;
	   }
	   
	}
	
	public static function addUserPackage($userid,$packageId)
	{
		$server = "127.0.0.1";
		$user = "root";
		$pass = "Soccer55";
		$dbName = "pixel1";

		$mysqli = new mysqli($server,$user,$pass,$dbName);
	
		$userid = stripslashes($userid);
		$userid = $mysqli->real_escape_string($userid);
		
		$packageId = stripslashes($packageId);
		$packageId = $mysqli->real_escape_string($packageId);
	
	
		$sql = "INSERT INTO user_package(userId,packageId) VALUES('$userid','$packageId');";
		if($resultdb =  $mysqli->query($sql))
		{
		}
	}
	
 }

?>