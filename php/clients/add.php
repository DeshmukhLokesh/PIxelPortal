<?php

/**
 * @author 
 * @copyright 2014
 */

require("db.php");

 Class Client
 {
   $Group_ID = 1;

	public function createUser($userName,$email,$password)
	{ 
		$sql = "INSERT INTO User(userName,email,Password,Group_ID) VALUES('$userName','$email','$password')";
		$result = array();
		if($resultdb =  $mysqli->query($sql))
		{
			$count = $resultdb->num_rows;
			if($count == 1)
			{
				$result['success'] =true;
			}
			else
			{
				$result['success'] = false;
			}
		 $resultdb->close();
			
	   }
	}
 }

?>