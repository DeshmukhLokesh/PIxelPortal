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

//All Packages
$data = $api->packages->list();
$pkgAll = get_object_vars($data);
$totalPkg = Count($pkgAll['data']['rows']);


//Only Selected Pkgs
$data1 = $api->clients->packagesList(array('id_clients' => $_SESSION['JS_USER_ID']));
$pkgSelected = get_object_vars($data1);
$totalPkgSelected = Count($pkgSelected['data']['rows']);

//echo json_encode($pkgAll['data']['rows']);

//Remove selected pkg from list of all pakacges


for($x=0; $x < Count($pkgAll['data']['rows']); $x++)
{
    for($y = 0; $y < $totalPkgSelected; $y++)
	{	
	    if(isset($pkgAll['data']['rows'][$x]))
		{
			if($pkgAll['data']['rows'][$x]['id'] == $pkgSelected['data']['rows'][$y]['id_packages'])
			{
				//unset($pkgAll['data']['rows'][$x]);
				array_splice($pkgAll['data']['rows'], $x, 1);
				break;
			}
		}
	}
	
	//$pkgAll['data']['rows'][$x]['period']  =  str_replace("mon","sun",$pkgAll['data']['rows'][$x]['period']);
}
/*
$items = array();
$totalPkg = count($pkgAll['data']['rows']);
for($z=0; $z < $totalPkg; $z++)
{
	if (isset($pkgAll['data']['rows'][$z]))
		$items[$z] = $pkgAll['data']['rows'][$z];
}
*/
$result = array();

//$items = $pkgAll['data']['rows'];

$result['success'] = true;
$result['total'] = count($pkgAll['data']['rows']);
$result['items'] = $items = $pkgAll['data']['rows'];//$items;

echo json_encode($result);

 
?>