<?php








/**
 * @LD 
 * @copyright 2014
 1. Get the post parameter from client side and build request and send to jerasoft for the process.
 2. Validate the Jerasoft response and if found duplicate client then stop the further processing and send back the response to UI
 3. If new customer is created then build request for custom fields and send them also in Jerasoft.
    3.1 First get the custom fields details from Jerasoft and generate /update values dynamically on the array and send to jerasoft.
 
 
 */



//Constants
$credit = 0;
$id_companies = 3;
$id_currencies = 26;
$type = 0;
$rates_notify_format = 'csv';
$rates_notify_type = 'all';

$name = $_POST['name'];
$name = stripslashes($name);
//echo $name;

$company = $_POST['company'];
$company = stripslashes($company);
//echo $company;

$email = $_POST['email'];
$email = stripslashes($email);
//echo $email;

$address = $_POST['address'];
$address = stripslashes($address);
//echo $address;

$city = $_POST['city'];
$city = stripslashes($city);
//echo $city;

$state = $_POST['state'];
$state = stripslashes($state);
//echo $state;

$zip = $_POST['zip'];
$zip = stripslashes($zip);
//echo $zip;

$phone = $_POST['phone'];
$phone = stripslashes($phone);
//echo $phone;

$domain = $_POST['domain'];
$domain = stripslashes($domain);
//echo $domain;

$id_packages = $_POST['packageId'];
$id_packages = stripslashes($id_packages);
//echo $id_packages;

$password = $_POST['password'];
$password = stripslashes($password);

$id_accounts ='';


require("../class.VcsApi.php");
require("../../php/Client.php");
 
// create an API object
$api = new VcsApi('169.131.241.195', 'john@pixelriver.com', 'Soccer55', 443);

$cp_modules = array('c_chpass','c_info','c_rates','c_paygw','c_report_balance','c_stats_summary','c_stats_cdrs','cp_password');

$addressDetail = $address."   ".$city."   ".$state."   ".$zip."  ";
// make a call to the API
$data = $api->clients->add(array('apply'=> 1,
                                     'bill_by_time'=> 'disconnect',
                                     'cp_modules'=> $cp_modules,	
                                     'credit'=> $credit,
                                     'id_companies'=> $id_companies, //this needs to be changed so that hard coded value can be used in a proper way.
                                     'id_currencies'=> $id_currencies,
                                     'name'=> $name,
                                     'rates_notify_format'=> $rates_notify_format,
                                     'rates_notify_type'=> $rates_notify_type,
                                     'status'=> 'active',
                                     'type'=> $type,
									 'c_company' =>	$company,
									 'c_email' => $email,
									 'c_address' => $addressDetail,
									// 'cp_login' => $email,
									// 'cp_password' => $password
                                      ));
	
				
	$responseClientAdd = get_object_vars($data);
 if ($responseClientAdd['code'] == true)
 {
     //Make an entry in database
	 
		$client =  new Client;
		$client->createUser($name,$email,$password,$id_packages,$responseClientAdd['data']['id'],$phone);
     
     //Update custom fields
     //echo $responseClientAdd['data']['id'];
     $customFields = $api->clients->fieldsList(array('_preload'=> 1,'id_clients'=>$responseClientAdd['data']['id']));
	
	//Needs to update this part of the work to make it more dynamic 
    	$fields = array();
		$fields['apply'] = true;
		$fields['id_clients'] = $responseClientAdd['data']['id'];
		$fields['fields'][0]['id'] = 1;
		$fields['fields'][0]['value'] = $address;
			
		$fields['fields'][1]['id'] = 2;
		$fields['fields'][1]['value'] = $state;
			
		$fields['fields'][2]['id'] = 3;
		$fields['fields'][2]['value'] = $zip;
			
		$fields['fields'][3]['id'] = 4;
		$fields['fields'][3]['value'] = $phone;
			
		$fields['fields'][4]['id'] = 5;
		$fields['fields'][4]['value'] = $domain;
	           
        $fieldsUpdateResponse = $api->clients->fieldsUpdate($fields);
		
	// Add Packages 
	  //  $packageUpdateResponse = $api->clients->packagesAdd(array('id_clients'=>$responseClientAdd['data']['id'],
	//	                                                         'id_packages'=>$id_packages
		//														  ));
		
	
 }
 
 //Change to asp
echo json_encode($data); 

 
// The complete list of arguments
/*
apply	1
autoinvoice_last	
bill_by_time	disconnect
c_account	
c_address	
c_company	
c_email	
c_email_billing	
c_email_rates	
c_email_tech	
cp_login	
cp_modules[]	c_chpass
cp_modules[]	c_info
cp_modules[]	c_rates
cp_modules[]	c_paygw
cp_modules[]	c_report_balance
cp_modules[]	c_stats_summary
cp_modules[]	c_stats_cdrs
cp_password	
credit	0
id_clients	
id_companies	3
id_currencies	26
id_dr_plans	
id_invoices_templates	
id_payment_terms	
id_taxes_profiles	
locale	
low_balance_athreshold	
low_balance_threshold	
name	Test3
orig_capacity	
orig_rate_table	
rates_notify_format	csv
rates_notify_type	all
reg_id	
status	active
tax_id	
term_capacity	
term_rate_table	
type	0
tz	

*/
 
?>