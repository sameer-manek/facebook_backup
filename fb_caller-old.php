<?php
session_start();
require_once __DIR__.'/classes/facebook.class.php';
require_once __DIR__.'/classes/drive.class.php';

$fb = new Facebook();
$google = Drive::getInstance();

//var_dump($_SESSION['google_access_token']);
$at = $_SESSION['google_access_token']['access_token'];
$google->getAgent()->setAccessToken($at);
var_dump($google->getAgent()->getAccessToken());
?>
