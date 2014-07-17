<?php

/**
 * @author 
 * @copyright 2014
 */

require("db.php");
session_start();


$email = $_GET['esource']; 
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
		 $updateQuery .= " isActive =  1";
		 
	  	 $updateQuery .= " WHERE email = '$email'";

		$mysqli->query($updateQuery);	
		
		$result['success'] = true;
        $result['msg'] = 'Account has been activated successfully.';
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