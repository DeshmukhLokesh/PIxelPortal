<?php

/**
 * @author 
 * @copyright 2014
 */

require("../db.php");
session_start();

$result = array();

$mysqli->query("SET @errNum = 0");
$mysqli->query("SET @errDesc = ''");

$userID = $_SESSION['PR_USER_ID'];

$items = array();
if($resultdb =  $mysqli->query("CALL spGet_UserTransactionHistory('$userID',@errNum,@errDesc)"))
{
    $count = $resultdb->num_rows;
	$x = 0;
    if($count > 0)
    {
	  //H.transaction_state,H.transaction_refrence_no, H.updated_datetime, L.price,L.package_id,H.user_id
	  while($r = $resultdb->fetch_assoc())
	  {
     	$items[$x]['transaction_state'] = $r['transaction_state'];
		$items[$x]['transaction_refrence_no'] = $r['transaction_refrence_no'];
		$items[$x]['updated_datetime'] = $r['updated_datetime'];
		$items[$x]['price'] = $r['price'];
		$items[$x]['package_id'] = $r['package_id'];
		$items[$x]['user_id'] = $r['user_id'];
		$x++;
      }
		$result['success'] = true;
		$result['msg'] = 'txn history found.';	  
    }
	else
	{
		$result['success'] = false;
		$result['msg'] = 'txn history not found.';	  
	}
}

$result['items'] = $items;
$resultdb->close();
echo json_encode($result);

/*
$result = array();
$items = array();
		$items['transaction_state'] = 'transaction_state';
		$items['transaction_refrence_no'] = 'transaction_refrence_no';
	//	$items['updated_datetime'] = $r['updated_datetime'];
		$items['price'] = 1212;
		$items['package_id'] = 1;
		$items['user_id'] = 2;

$result['items'] = $items;
		
echo json_encode($result);
*/
?>