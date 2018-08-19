<?php

if(!session_id()) {
    session_start();
}

	require_once("./vendor/autoload.php");

	$fb = new \Facebook\Facebook([
	  'app_id' => '222413885297299',
	  'app_secret' => '85e84df32c06c5801bba06f3710b3e0f',
	  'default_graph_version' => 'v3.1',
	]);

	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email', 'user_photos'];
	$loginUrl = $helper->getLoginUrl('https://facebook.rtc/callback.php', $permissions);

	header("Location: ".$loginUrl);