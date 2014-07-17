<?php

/**
 * @author 
 * @copyright 2014
 */

require("db.php");
session_start();
$userEmail =$_GET['email'];
//$userName = "Test_Martin_1";

$userEmail = stripslashes($userEmail);

$userEmail = $mysqli->real_escape_string($userEmail);

$result = array();

$mysqli->query("SET @errNum = 0");
$mysqli->query("SET @errDesc = ''");

if($resultdb =  $mysqli->query("CALL IsDuplicateEmail('$userEmail',@errNum,@errDesc)"))
{
    $count = $resultdb->num_rows;
    if($count == 1)
    {
	  while($r = $resultdb->fetch_assoc())
	  {
        $result['success'] =true;
        $result['msg'] = 'User Email Found';
	    }
    }
    else
    {
        $result['success'] = false;
        $result['msg'] = 'User Email Not Found.';
    }
    
    $resultdb->close();
    echo json_encode($result);
    
}
else
{
		$result['success'] = false;
        $result['msg'] = 'User Email Not Found.';
		$resultdb->close();
        echo json_encode($result);
}
?>