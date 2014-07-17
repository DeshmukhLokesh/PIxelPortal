<?php

/**
 * @author 
 * @copyright 2014
 */

require("../class.VcsApi.php");
/**include 'class.VcsApi.php';*/
 session_start();
// create an API object
$api = new VcsApi('169.131.241.195', 'john@pixelriver.com', 'Soccer55', 443);

// make a call to the API
$data = $api->clients->packagesList(array('id_clients' => $_SESSION['JS_USER_ID']));

$responseData = get_object_vars($data);

//Get the selected but unsubscribed packages for the user
$server = "127.0.0.1";
$user = "root";
$pass = "Soccer55";
$dbName = "pixel1";
$mysqli = new mysqli($server,$user,$pass,$dbName);
		
$userID = $_SESSION['PR_USER_ID'];
$sql = "SELECT * FROM user_package WHERE userId = '$userID' AND IsSubscribed = 0";
$items = array();

if($resultdb =  $mysqli->query($sql))
{
    $count = $resultdb->num_rows;
    if($count > 0)
    {
	  while($r = $resultdb->fetch_assoc())
	  {
		$dataAPkg = $api->packages->editForm(array('id' => $r['packageId']));

		$dataPkg = get_object_vars($dataAPkg);
		$items['id'] =  $dataPkg['data']['info']['id'];
		$items['package_name'] =  $dataPkg['data']['info']['name'];
		$items['activation_fee'] =  $dataPkg['data']['info']['activation_fee'];
		$items['periodical_fee'] =  $dataPkg['data']['info']['periodical_fee'];
		$items['period'] =  $dataPkg['data']['info']['period'];
		$items['IsSubscribed'] =  true	;
		
		//echo $response['data']['form']['name'];
		//echo $response['data']['form']['activation_fee'];
		//echo $response['data']['form']['periodical_fee'];

        
	  }
    }
}


$result = array();


$result['success'] = true;
$result['total'] = count($responseData['data']['rows']) + $count;
if(Count($responseData['data']['rows']) > 0)
	$result['items'] = $responseData['data']['rows'];
else
   $result['items'] = $items;

echo json_encode($result);


 
?>