<?php

/**
 * @LD 
 * @copyright 2014
 1. Get the post parameter from client side and build request and send to jerasoft for the process.
 2. Validate the Jerasoft response and if found duplicate client then stop the further processing and send back the response to UI
 3. If new customer is created then build request for custom fields and send them also in Jerasoft.
    3.1 First get the custom fields details from Jerasoft and generate /update values dynamically on the array and send to jerasoft.
 
 
 */
require("../class.VcsApi.php");

 $server = "127.0.0.1";
 $user = "root";
 $pass = "Soccer55";
 $dbName = "pixel1";
 $mysqli = new mysqli($server,$user,$pass,$dbName);
 
$phone = "xxxx" ;
$profilePicture = "profile_empty.png" ;
session_start();
$userID = $_SESSION['PR_USER_ID'];
$userID = stripslashes($userID);
$userID = $mysqli->real_escape_string($userID);

$sql = "SELECT * FROM user U WHERE U.id = '$userID'";

if($resultdb =  $mysqli->query($sql))
{
    $count = $resultdb->num_rows;
    if($count == 1)
    {
	  while($r = $resultdb->fetch_assoc())
	  {
 		$profilePicture = $r['picture'];
		$phone = $r['phone'];
     }
  }
}

 
//echo json_encode(array('firstName'=> 'Lokesh','lastName'=> 'Deshmukh','company'=> 'comp','phone'=> '879797987979' ));

 
// create an API object
$api = new VcsApi('169.131.241.195', 'john@pixelriver.com', 'Soccer55', 443);

//$_SESSION['jeraSoft_ID'] = 75;

 // make a call to the API
 
$data = $api->clients->editForm(array('id_clients' => $_SESSION['JS_USER_ID']));

//$dataSelectedPkg = $api->packages->editForm(array('id' => $_SESSION['JS_PACKAGE_ID']));

//$SelectedPkg = get_object_vars($dataSelectedPkg);
$responseClientAdd = get_object_vars($data);

//$_SESSION['jeraSoft_pkg_name'] = $SelectedPkg['data']['info']['name'];
//$_SESSION['jeraSoft_periodical_fee'] = $SelectedPkg['data']['info']['periodical_fee'];
//$_SESSION['jeraSoft_activation_fee'] = $SelectedPkg['data']['info']['activation_fee'];

//$packageDetail = " UnSubscribed Package - ".$SelectedPkg['data']['info']['name']."  |  Activation Fees- ".$SelectedPkg['data']['info']['activation_fee']."$  |  Subscription Fees- ".$SelectedPkg['data']['info']['periodical_fee']."$ ";

$fullName = "Welcome  ".$responseClientAdd['data']['client']['name']." ,";
$email = "Your current login email account: ".$responseClientAdd['data']['client']['c_email']."";

$pieces = explode(" ", $responseClientAdd['data']['client']['name']);
 $lastName =  "";
if(count($pieces) > 1)
{
  $firstName = $pieces[0];
  $lastName =  $pieces[1];
}
else
{
  $firstName  = $responseClientAdd['data']['client']['name'];
}


echo json_encode(array('firstName'=> $firstName,
                        'lastName'=> $lastName,
						'company'=> $responseClientAdd['data']['client']['c_company'],
						'phone'=> $phone,
						'fullName'=> $fullName,
						'email'=> $email,
						'picture'=> $profilePicture
						));
						//'packageDetail'=> $packageDetail

?>