<?php

/**
 * @author 
 * @copyright 2014
 */

require("db.php");
session_start();
$userName =$_GET['user'];
//$userName = "Test_Martin_1";

$userName = stripslashes($userName);

$userName = $mysqli->real_escape_string($userName);

$result = array();

$mysqli->query("SET @errNum = 0");
$mysqli->query("SET @errDesc = ''");

if($resultdb =  $mysqli->query("CALL IsDuplicateUserName('$userName',@errNum,@errDesc)"))
{
    $count = $resultdb->num_rows;
    if($count == 1)
    {
	  while($r = $resultdb->fetch_assoc())
	  {
        $result['success'] =true;
        $result['msg'] = 'User Found';
	    }
    }
    else
    {
        $result['success'] = false;
        $result['msg'] = 'User Not Found.';
    }
    
    $resultdb->close();
    echo json_encode($result);
    
}
else
{
		$result['success'] = false;
        $result['msg'] = 'User Not Found.';
		$resultdb->close();
        echo json_encode($result);
}
?>