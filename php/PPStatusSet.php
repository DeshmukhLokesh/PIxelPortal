<?php

/**
 * @author 
 * @copyright 2014
 */
 session_start();
	$result = array();
	$_SESSION['PP_STATUS'] = 'NA';
	
	$result['success'] = true;
	$result['msg'] = 'PP no more alive';
	
    echo json_encode($result);
	
?>