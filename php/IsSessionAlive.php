<?php

/**
 * @author 
 * @copyright 2014
 */
 session_start();
	$result = array();
	if(empty($_SESSION['PR_AUTH']) == false )
	{
		if($_SESSION['PR_AUTH'])
		{
			$result['success'] =true;
			$result['profilePicture'] = $_SESSION['PR_PROFILE_PICTURE'];
			$result['profileEmail'] = $_SESSION['PR_EMAIL'];
			if(empty($_SESSION['PP_STATUS']) == false )
				$result['PPStatus'] = $_SESSION['PP_STATUS'];
			else
			    $result['PPStatus'] = 'NA';
					
			$result['msg'] = 'Alive';
			
		}
		else
		{
			$result['success'] = false;
			$result['profilePicture'] = 'PR_PROFILE_PICTURE';
			$result['profileEmail'] = 'user-icon.png';
			$result['PPStatus'] = 'NA';
			$result['msg'] = 'No more alive';
		}
	}
	else
    {
		$result['success'] = false;
		$result['profilePicture'] = 'PR_PROFILE_PICTURE';
		$result['profileEmail'] = 'user-icon.png';
		$result['PPStatus'] = 'NA';
		$result['msg'] = 'No more alive';
	}
   
    echo json_encode($result);
	//session_start();
?>