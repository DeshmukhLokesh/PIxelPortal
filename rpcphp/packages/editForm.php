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

 Session_start();

 
// create an API object
$api = new VcsApi('169.131.241.195', 'john@pixelriver.com', 'Soccer55', 443);

//$_SESSION['jeraSoft_ID'] = 75;

 // make a call to the API
 //packages/editForm
$data = $api->packages->editForm(array('id' => 12));

$response = get_object_vars($data);
echo $response['data']['form']['name'];
echo $response['data']['form']['activation_fee'];
echo $response['data']['form']['periodical_fee'];


echo json_encode($data);

/*
echo json_encode(array('firstName'=> $responseClientAdd['data']['client']['name'],
                        'lastName'=> $responseClientAdd['data']['client']['name'],
						'company'=> $responseClientAdd['data']['client']['c_company'],
						'phone'=> $phone,
						'fullName'=> $fullName,
						'email'=> $email,
						'picture'=> $profilePicture
						));

*/
?>