<?php

require("../db.php");
require("../../rpcphp/class.VcsApi.php");
session_start();

$id = $_SESSION['PR_USER_ID']; 
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];

$userName = $firstName ." ".$lastName;
$compnay = $_POST['company'];
$phone = $_POST['phone'];


$uploads_dir = '../../resources/ProfileImages';

if ($id == ""){
	$id = 0;
}

if(isset($_FILES)){

	$tmpName = $_FILES['fileUser']['tmp_name'];
	$fileName = $_FILES['fileUser']['name'];
    
	if(empty($fileName) == false)
		$_SESSION['PR_PROFILE_PICTURE'] = $fileName;
	
	move_uploaded_file($tmpName, "$uploads_dir/$fileName");
}	

	$updateQuery = "UPDATE user SET ";
	$updateQuery .= "userName = '$userName', ";
	
	if ($fileName != null) { // only update it if file upload
		$updateQuery .= "picture = '$fileName', ";
	}
	$updateQuery .= "phone = '$phone' ";
	$updateQuery .= " WHERE id='$id'";

	$resultdb = $mysqli->query($updateQuery);	
	
	////////Jerasoft update
	
	$id_companies =3;
	 $api = new VcsApi('169.131.241.195', 'john@pixelriver.com', 'Soccer55', 443);
	//0 First get the customer detail from Jerasoft
	 $data = $api->clients->editForm(array('id_clients' => $_SESSION['JS_USER_ID']));
     $responseClient = get_object_vars($data);
	 $customerName = $responseClient['data']['client']['name'];
	 $customerEmail = $responseClient['data']['client']['c_email'];
	 $customerCompany = $responseClient['data']['client']['c_company'];
	 $customerAddress = $responseClient['data']['client']['c_address'];
	 $customerBalance = (double)$responseClient['data']['client']['credit'];
	
    // make a call to the API
    $data = $api->clients->edit(array('apply'=> 1,
                                 'id_clients' => $_SESSION['JS_USER_ID'] ,
                                 'credit'=> $customerBalance, 
								 'name' => $userName,
								 'id_companies' => $id_companies ,
								 'status'=>	'active',
								 'c_email'=>$customerEmail,
								 'c_company'=>$compnay,
								 'c_address'=>$customerAddress
	                           ));
	
	/////////////////////////////////////
	
	


header('Content-type: text/html');


if(empty($fileName) == false)
	$profilePicture = $fileName;
else
	$profilePicture = $_SESSION['PR_PROFILE_PICTURE'];

echo json_encode(array(
	"success" => $mysqli->error == '',
	"msg" => $mysqli->error,
	"id" => $id,
	"profilePicture" => $profilePicture
));

/* close connection */
$mysqli->close();
?>