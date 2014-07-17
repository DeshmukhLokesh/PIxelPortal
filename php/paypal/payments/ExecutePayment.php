<?php
// #Execute Payment Sample
// This sample shows how you can complete
// a payment that has been approved by
// the buyer by logging into paypal site.
// You can optionally update transaction
// information by passing in one or more transactions.
// API used: POST '/v1/payments/payment/<payment-id>/execute'.
/*
echo "<html><body><pre>";
  
  echo "<link rel='stylesheet' href='../../../bootstrap.css'>";
  echo  "<script src='../../../ext/ext-dev.js'></script>";
  echo  "<script type='text/javascript' src='../../../app/view/MyViewportPaypalResponse.js'></script>";
  echo  "<script type='text/javascript'>Ext.create('widget.myviewportpaypalresponse');</script>";

  
  //echo "Your payment was made successfully! Thanks.";
 echo "</pre><a href='http://localhost/PixelCustomerPortal/extCustomerPortal/index.html'>Back</a></body></html>";

exit;
*/


require __DIR__ . '/../bootstrap.php';
require("../../../rpcphp/class.VcsApi.php");
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
session_start();
if(isset($_GET['success']) && $_GET['success'] == 'true') {
	
	// Get the payment Object by passing paymentId
	// payment id was previously stored in session in
	// CreatePaymentUsingPayPal.php
	$paymentId = $_SESSION['paymentId'];
	$payment = Payment::get($paymentId, $apiContext);
	
	// PaymentExecution object includes information necessary 
	// to execute a PayPal account payment. 
	// The payer_id is added to the request query parameters
	// when the user is redirected from paypal back to your site
	$execution = new PaymentExecution();
	$execution->setPayerId($_GET['PayerID']);
	
	//Execute the payment
	// (See bootstrap.php for more on `ApiContext`)
	$result = $payment->execute($execution, $apiContext);
    
	// Making an entry in sql database
	$array = $result->toJSON();
	$obj = json_decode($array);
	
	$server = "127.0.0.1";
	$user = "root";
	$pass = "Soccer55";
	$dbName = "pixel1";

	$mysqli = new mysqli($server,$user,$pass,$dbName);

	//insert header information
	$date = $obj->update_time;
	$my_date = date('Y-m-d H:i:s', strtotime($date));
	$usrID = $_SESSION['PR_USER_ID'];
	$sql = "INSERT INTO transaction_hdr(user_id,payer_id,transaction_state,transaction_type,updated_datetime,transaction_refrence_no)
                                	VALUES('$usrID','$obj->id', '$obj->state','$obj->intent','$my_date','$obj->id');";
	
	$resultdb =  $mysqli->query($sql);		
	
	
	
	
	
	
	//insert line information
	$transactionHdrID = $mysqli->insert_id;
	
	$address = $obj->payer->payer_info->shipping_address->line1;
	$address .= " " + $obj->payer->payer_info->shipping_address->line2;
	$address .= " " + $obj->payer->payer_info->shipping_address->city;
	$address .= " " + $obj->payer->payer_info->shipping_address->state;
	$address .= " " + $obj->payer->payer_info->shipping_address->postal_code;
	$address .= " " + $obj->payer->payer_info->shipping_address->country_code;
		
	$payAmount = 0.0;
	foreach($obj->transactions as $varobj)
	{
		$payAmount = $varobj->amount->total;
	}
	$jeraSoft_packageId = $_SESSION['JS_PKG_ID'];
	$sql1 = "INSERT INTO transaction_line(transaction_hdr_id,package_id,tax,shipping,price) VALUES($transactionHdrID,'$jeraSoft_packageId',0,'$address',$payAmount);";
	$resultdb =  $mysqli->query($sql1);
	
	//update if the selected pkg is subscribed
	$sqlUpdate = " UPDATE user_package SET  IsSubscribed = 1 WHERE userId = '$usrID' AND IsSubscribed = 0 ";
	$resultdb =  $mysqli->query($sqlUpdate);
	
	// Jerasoft call - Add Packages 
	 $id_companies =3;
	 $api = new VcsApi('169.131.241.195', 'john@pixelriver.com', 'Soccer55', 443);
	//0 First get the customer detail from Jerasoft
	 $data = $api->clients->editForm(array('id_clients' => $_SESSION['JS_USER_ID']));
     $responseClient = get_object_vars($data);
	 $customerName = $responseClient['data']['client']['name'];
	 $customerEmail = $responseClient['data']['client']['c_email'];
	 $customerCompany = $responseClient['data']['client']['c_company'];
	 $customerAddress = $responseClient['data']['client']['c_address'];
	 $customerBalance = (double)$responseClient['data']['client']['credit'] + $payAmount;
	
	//1 First add credit to Jerasoft customer 
	 
     // create an API object
    
    // make a call to the API
    $data = $api->clients->edit(array('apply'=> 1,
                                 'id_clients' => $_SESSION['JS_USER_ID'] ,
                                 'credit'=> $customerBalance, 
								 'name' => $customerName,
								 'id_companies' => $id_companies ,
								 'status'=>	'active',
								 'c_email'=>$customerEmail,
								 'c_company'=>$customerCompany,
								 'c_address'=>$customerAddress
	                           ));
	
    //2 	Add Packages to Customer in Jearsoft  
     $api = new VcsApi('169.131.241.195', 'john@pixelriver.com', 'Soccer55', 443);
	 
	 $fieldsUpdateResponse = $api->clients->packagesAdd(array('id_clients'=> $_SESSION['JS_USER_ID'],
		                                                      'id_packages'=> $_SESSION['JS_PKG_ID']));
				
	//echo json_encode($fieldsUpdateResponse);
	
	 //Making Entry in Jerasoft for custom fields with shipping address
	 
	 $street = $obj->payer->payer_info->shipping_address->line1;
	 $street .= " " + $obj->payer->payer_info->shipping_address->line2;
	
       $fields = array();
		$fields['apply'] = true;
		$fields['id_clients'] = $_SESSION['JS_USER_ID'];
		$fields['fields'][0]['id'] = 1;
		$fields['fields'][0]['value'] = $street;
			
		$fields['fields'][1]['id'] = 2;
		$fields['fields'][1]['value'] = $obj->payer->payer_info->shipping_address->state;
			
		$fields['fields'][2]['id'] = 3;
		$fields['fields'][2]['value'] = $obj->payer->payer_info->shipping_address->postal_code;
			
		$fields['fields'][3]['id'] = 4;
		$fields['fields'][3]['value'] = "";
			
		$fields['fields'][4]['id'] = 5;
		$fields['fields'][4]['value'] = "";
	           
        $fieldsUpdateResponse = $api->clients->fieldsUpdate($fields);
	 
	    $_SESSION['PP_STATUS'] = "PP_SUCCESS";
	
	   //alert('Your payment was completed with Pixel River. thanks for the business.');
	  // header("Location:http://localhost/PixelCustomerPortal/extCustomerPortal/index.html");
	  // echo  "<script type='text/javascript'>alert('Your payment was completed with Pixel River. thanks for the business.');</script>";

	   //header("Location:../../../index.html?response=success");
	   header("Location:http://localhost/PixelPortal/index.html?#myplan");
       exit;
	
	echo "<html><body><pre>";
    echo "Your payment was made successfully! Thanks.";
	echo "</pre><a href='http://localhost/PixelCustomerPortal/extCustomerPortal/index.html'>Back</a></body></html>";
	
	
		
} else {
     $_SESSION['PP_STATUS'] = "PP_CANCEL";
	 header("Location:http://localhost/PixelPortal/index.html?#myplan");
	//echo "User cancelled payment.";
}
