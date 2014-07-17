<?php

/**
 * @author 
 * @copyright 2014
 */

require("db.php");
session_start();
$userName =$_POST['user'];
$pass = $_POST['password'];

//$userName = "Test_Martin_A@Test.com";
//$pass = "25d55ad283aa400af464c76d713c07ad";

$userName = stripslashes($userName);
$pass = stripslashes($pass);
$userName = $mysqli->real_escape_string($userName);
$pass = $mysqli->real_escape_string($pass);

$result = array();

$mysqli->query("SET @errNum = 0");
$mysqli->query("SET @errDesc = ''");



if($resultdb =  $mysqli->query("CALL spGet_Login('$userName','$pass',@errNum,@errDesc)"))
{

 /*
	if (!($res = $mysqli->query("SELECT @errDesc as _p_out")))
	echo "Fetch failed: (" . $mysqli->errno . ") " . $mysqli->error;

	$row = $res->fetch_assoc();
	echo $row['_p_out'];
 */
 
    $count = $resultdb->num_rows;
    if($count == 1)
    {
	  while($r = $resultdb->fetch_assoc())
	  {
        $_SESSION['PR_AUTH'] = true;
        $_SESSION['PR_EMAIL'] = $userName;
		$_SESSION['PR_PROFILE_PICTURE'] = $r['picture'];
		$_SESSION['JS_USER_ID'] = $r['jeraSoft_ID'];
		$_SESSION['PR_USER_ID'] = $r['id'];
		$_SESSION['PP_STATUS'] = 'NA';
		
        $result['success'] =true;
        $result['msg'] = 'User authenticated';
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
else
{
		$result['success'] = false;
        $result['msg'] = 'Incorrect user or password.';
		$resultdb->close();
        echo json_encode($result);
}
?>