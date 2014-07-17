<?php

/**
 * @author 
 * @copyright 2014
 */

require("../class.VcsApi.php");
/**include 'class.VcsApi.php';*/
 
// create an API object
$api = new VcsApi('169.131.241.195', 'john@pixelriver.com', 'Soccer55', 443);


// make a call to the API
$data = $api->packages->list();

$result = array();
$items = array();

 $counter = 0;
  foreach ($data as $key => $val) 
  {
    //print "$key = $val\n";
    if($key == 'data')
    {
    foreach ($val as $key1 => $val1)
    {
     /* print "$key1 = $val1\n"; */    
        if($key1 == 'rows')
       {
         foreach ($val1 as $key2 => $val2)
         {
    /*     print "$key2 = $val2\n"; */      
           foreach ($val2 as $key3 => $val3)
           {
      /*       //print "$key3 = $val3\n"; */
             
             if($key3 == 'id')
             {
              $items[$counter]['id'] = $val3;
             }
             if($key3 == 'name')
             {
			
              //  $item['name'] = $val3;
                $items[$counter]['name'] = $val3;
             }
             if( $key3 == 'name' )
             {
                $counter++;
                
             }
			 
			 
           }
         }
       }
        
      }
    }
    
  }
   
   $result['success'] =true;
   $result['total'] = $counter++;;
   $result['items'] = $items;
   
  // print "---------------My New array -------------";
echo json_encode($result);
 
 
 /*
 apply	1
fields[0][id]	1
fields[0][value]	Street
fields[1][id]	2
fields[1][value]	State
fields[2][id]	3
fields[2][value]	Zip
fields[3][id]	4
fields[3][value]	Phone
fields[4][id]	5
fields[4][value]	Domain
id_clients	35
 */
 
?>