<?php

/**
 * @author 
 * @copyright 2014
 */


include 'class.VcsApi.php';
 
// create an API object
$api = new VcsApi('169.131.241.195', 'john@pixelriver.com', 'Soccer55', 443);
 
// make a call to the API
$data = $api->clients->editForm(array('id_clients' => 60));

echo json_encode($data);
/* if(isset($data))
 {
      echo '$this is defined ';
      
        print count($data);
     foreach ($data as $key => $val) {
     print "$key = $val\n";
     
     foreach ($val as $k => $va) {
           print "$k = $va\n";
           
        foreach ($va as $l => $v) {
           print "$l = $v\n";
           
            foreach ($v as $m => $ss) {
           print "$m = $ss\n";
      }
           
      }
           
      }
      
     
}
}
      
      
      
      
  Else
  {
     echo '$this is not defined ';
     }
*/
?>