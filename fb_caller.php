<?php

// handle the facebook related call requests

if(!session_id()) {
    session_start();
}

require_once __DIR__.'/classes/facebook.class.php';
require_once __DIR__.'/classes/drive.class.php';

$fb = new Facebook();
$google = Drive::getInstance();

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

if($i == "album_info"){

	echo json_encode($fb->get_album_info($_REQUEST['id']));
}

if($i == 'photos'){

	echo json_encode($fb->get_photos($_REQUEST['id']));
}

if($i == "backup_req"){
    //$agent = $google->getAgent();
    if(!isset($_SESSION['google_access_token'])){
        echo "redirecting you to google login";
        // $_SESSION['album_temp'] = $_REQUEST['album'];
        // header("location: ".filter_var($agent->createAuthUrl(), FILTER_SANITIZE_URL));
    } else{
        $google->uploadAlbum($fb, $_REQUEST['album']);
    }
}

if($i == "google_callback"){
    //validate client
    if(!isset($_SESSION['google_access_token'])){
        $code = $_GET['code']; // just to keep the code neat.. no need to store in other var!
        $res = $google->getAgent()->authenticate($code); // hope this works
        $_SESSION['google_access_token'] = $google->getAgent()->getAccessToken();
    }
    //$google->uploadAlbum($fb, $_SESSION['album_temp']);
    echo json_encode([status => 1]);
}

if($i == "logged"){
	$_SESSION['fb_access_token'] = $_REQUEST['token'];
	header("location: /user.php");
}

if($i == "logout"){
	session_unset($_SESSION['fb_access_token']);
  session_unset($_SESSION['google_access_token']);
	session_destroy();
	header("location: /");
}
