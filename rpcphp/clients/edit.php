<?php








/**
 * @LD 
 * @copyright 2014
 1. Get the post parameter from client side and build request and send to jerasoft for the process.
 2. Validate the Jerasoft response and if found duplicate client then stop the further processing and send back the response to UI
 3. If new customer is created then build request for custom fields and send them also in Jerasoft.
    3.1 First get the custom fields details from Jerasoft and generate /update values dynamically on the array and send to jerasoft.
 
 
 */



$credit = 1000;
$id_companies =3;


require("../class.VcsApi.php");
require("../../php/Client.php");
 
// create an API object
$api = new VcsApi('169.131.241.195', 'john@pixelriver.com', 'Soccer55', 443);
// make a call to the API
$data = $api->clients->edit(array('apply'=> 1,
                                 'id_clients' => 83 ,
                                 'credit'=> $credit,
								 'name' => 'Test-Martin12',
								 'id_companies' => $id_companies ,
								 'status'=>	'active'
								 
                                ));
echo json_encode($data); 

?>