<?php

/**
 * @author 
 * @copyright 2014
 */

   require("db.php");
	
		session_start();
		
		$Picture = "profile_empty.png";
	
		$server = "127.0.0.1";
		$user = "root";
		$pass = "Soccer55";
		$dbName = "pixel1";

		$mysqli = new mysqli($server,$user,$pass,$dbName);
		
		
		
		$email = "Test_user@Test.com";
		$password = "12345";
		$packageId =1;
		$jeraSoft_ID = 0;
		$Phone = "12345";
				
		
		$userName = "Test_user";
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
                            phone)
							VALUES('$userName','$email','$password','$Picture','1','$jeraSoft_ID','$Phone');";
	
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
		   echo "Success- New user created with ID: ";
		 
		    $user_id = $mysqli->insert_id;
			
			echo $user_id;
			
			$_SESSION['PR_USER_ID'] = $user_id;
			
			
	   }
	   else
	   {
	      echo "Failed";
	      echo $mysqli->error;
	   }
	   

	


?>