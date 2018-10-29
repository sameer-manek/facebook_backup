<?php
    if(!session_id()){
        session_start();
    }

    require_once __DIR__.'/classes/facebook.class.php';
    require_once __DIR__.'/classes/drive.class.php';

    $query = $_REQUEST['i'];

    switch($query){
        case "login":
            echo "login";
        break;

        case "logged":
            echo "logged";
        break;

        case "logout":
            echo "logout";
        break;

        case "backup_req":
            echo "backup request";
        break;

        case "albums":
            echo "albums";
        break;

        case "album_info":
            echo "album information";
        break;

        case "photos":
            echo "photos";
        break;

        case "info":
            echo "user information";
        break;

        default:
            echo "sorry, wrong request";
    }
?>
