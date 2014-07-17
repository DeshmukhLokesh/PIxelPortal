<?php

/**
 * @author 
 * @copyright 2014
 */

require("db.php");
session_start();


$Password =$_POST['password'];
$Password = stripslashes($Password);
$Password = $mysqli->real_escape_string($Password);


$email = $_POST['esource']; 
$email = str_replace("esource=","",$email);

$email = base64_decode($email);
$email = stripslashes($email);
$email = $mysqli->real_escape_string($email);

$result = array();

$sql = "SELECT * FROM user WHERE email = '$email' ";

if($resultdb =  $mysqli->query($sql))
{
    $count = $resultdb->num_rows;
	
    if($count == 1)
    {
		 $updateQuery = "UPDATE user SET ";
		 $updateQuery .= "password = '$Password' ,";
		 $updateQuery .= "lastPassRequest = null";
		 
	  	 $updateQuery .= " WHERE email='$email'";

		$resultdb = $mysqli->query($updateQuery);	
		
		$result['success'] = true;
        $result['msg'] = 'Credential changed successfully.';
    }
    else
    {
        $result['success'] = false;
        $result['msg'] = 'Invalid credential Found.';
    }
    
    
    
}
else
{
	$result['success'] = false;
    $result['msg'] = 'Invalid credential Found.';
	
}
	$mysqli->close();
    echo json_encode($result);
?>