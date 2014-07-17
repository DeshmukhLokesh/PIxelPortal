<?php

/**
 * @author 
 * @copyright 2014
 */

require("db.php");
session_start();

$newPassword =$_POST['confirmPassword'];
$newPassword = stripslashes($newPassword);
$newPassword = $mysqli->real_escape_string($newPassword);

$oldPassword =$_POST['oldPassword'];
$oldPassword = stripslashes($oldPassword);
$oldPassword = $mysqli->real_escape_string($oldPassword);

$userId = $_SESSION['PR_USER_ID']; 
$userId = stripslashes($userId);
$userId = $mysqli->real_escape_string($userId);

$result = array();

$sql = "SELECT * FROM user WHERE password = '$oldPassword' AND id = '$userId' ";

if($resultdb =  $mysqli->query($sql))
{
    $count = $resultdb->num_rows;
	
    if($count == 1)
    {
		 $updateQuery = "UPDATE user SET ";
		 $updateQuery .= "password = '$newPassword' ";
	  	 $updateQuery .= " WHERE id='$userId'";

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