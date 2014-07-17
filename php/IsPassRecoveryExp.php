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

$sql = "SELECT * FROM user WHERE email = '$email' AND lastPassRequest is null";

if($resultdb =  $mysqli->query($sql))
{
    $count = $resultdb->num_rows;
	
    if($count == 1)
    {
	 	$result['success'] = true;
        $result['msg'] = 'The Pass reset link has been expired.';
    }
    else
    {
        $result['success'] = false;
        $result['msg'] = 'ok';
    }
    
}
else
{
	$result['success'] = true;
    $result['msg'] = 'The Pass reset link has been expired.';
	
}
	$mysqli->close();
    echo json_encode($result);
?>