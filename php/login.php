<?php

/**
 * @author 
 * @copyright 2014
 */

require("db.php");
session_start();
$userName =$_POST['user'];
$pass = $_POST['password'];
$userName = stripslashes($userName);
$pass = stripslashes($pass);
$userName = $mysqli->real_escape_string($userName);
$pass = $mysqli->real_escape_string($pass);
$sql = "SELECT * FROM user U INNER JOIN user_package up ON U.id = up.userId WHERE email = '$userName' and Password = '$pass'";
$result = array();
if($resultdb =  $mysqli->query($sql))
{
    $count = $resultdb->num_rows;
    
    if($count == 1)
    {
	  while($r = $resultdb->fetch_assoc())
	  {
	
        $_SESSION['authenticated'] = "yes";
        $_SESSION['username'] = $userName;
        $result['success'] =true;
        $result['msg'] = 'User authenticated';
		$_SESSION['jeraSoft_ID'] = $r['jeraSoft_ID'];
		$_SESSION['jeraSoft_packageId'] = $r['packageId'];
		$_SESSION['user_ID'] = $r['userId'];
        }
    }
    else
    {
        $result['success'] = false;
        $result['msg'] = 'Incorrect user or password.';
    }
    
    $resultdb->close();
    echo json_encode($result);
    
}

?>