<?php

// handle the facebook related call requests

if(!session_id()) {
    session_start();
}

require_once './classes/Facebook.php';

$fb = new Facebook();

$i = $_REQUEST['i'];

if($i == 'login'){
	
	$response = array(
		"uri"=> $fb->login()
	);

	echo json_encode($response);
}


if($i == 'info'){
	
	$response = array(
		"info"=> $fb->get_user_info()
	);

	echo json_encode($response);
}

if($i == 'albums'){
	
	$response = array(
		"albums"=> $fb->get_user_albums()
	);

	echo json_encode($response);
}


if($i == 'photos'){
	
	$response = array(
		"photos"=> $fb->get_user_photos($_REQUEST['album'])
	);

	echo json_encode($response);
}

if($i == "logged"){
	$_SESSION['fb_access_token'] = $_REQUEST['token'];
	header("location: /user.php");
}