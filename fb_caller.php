<?php
    session_start();

    require_once __DIR__.'/classes/facebook.class.php';
    require_once __DIR__.'/classes/drive.class.php';

    $fb = new Facebook();
    $google = Drive::getInstance();

    $query = $_REQUEST['i'];

    switch($query){
        case "login":
            $response = array(
                "uri"=> $fb->login()
            );
            echo json_encode($response);
        break;

        case "logged":
            $_SESSION['fb_access_token'] = $_REQUEST['token'];
            header("location: /user.php");
        break;

        case "logout":
            session_unset();
            session_destroy();
        break;

        case "backup_req":
            $agent = $google->getAgent();
            if(!isset($_SESSION['google_access_token'])){
                $_SESSION['album_temp'] = $_REQUEST['album'];
                header("location: ".filter_var($agent->createAuthUrl(), FILTER_SANITIZE_URL));
            } else{
                $google->uploadAlbum($fb, $_REQUEST['album']);
            }
        break;

        case "google_callback":
            $agent = $google->getAgent();
            if(!isset($_SESSION['google_access_token'])){
                $code = $_GET['code']; // just to keep the code neat.. no need to store in other var!
                $res = $google->getAgent()->authenticate($code); // hope this works
                $_SESSION['google_access_token'] = $agent->getAccessToken();
            }

            $google->uploadAlbum($fb, $_SESSION['album_temp']);
        break;

        case "albums":
            echo json_encode($fb->get_user_albums());
        break;

        case "album_info":
            echo json_encode($fb->get_album_info($_REQUEST['id']));
        break;

        case "photos":
            echo json_encode($fb->get_photos($_REQUEST['id']));
        break;

        case "info":
            echo json_encode($fb->get_user_info());
        break;

        default:
            echo "sorry, wrong request";
    }
?>
