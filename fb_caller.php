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

	echo json_encode($fb->get_user_info());
}

if($i == 'albums'){

	echo json_encode($fb->get_user_albums());
}


if($i == 'photos'){

	echo json_encode($fb->get_photos($_REQUEST['album']));
}

if($i == "logged"){
	$_SESSION['fb_access_token'] = $_REQUEST['token'];
	header("location: /user.php");
}

if($i == "logout"){
	session_unset($_SESSION['fb_access_token']);
	session_destroy();
	header("location: /");
}