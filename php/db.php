<?php

/**
 * @LD 
 * @copyright 2014
 */

$server = "127.0.0.1";
$user = "root";
$pass = "Soccer55";
$dbName = "pixel1";

$mysqli = new mysqli($server,$user,$pass,$dbName);
/*checking connection */
if($mysqli->connect_errno){printf("Connect failed:%s\n",mysqli_connect_error());
exit();}
 
?>