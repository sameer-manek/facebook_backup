<?php

	require_once('./vendor/autoload.php');

	class Google{
		$__private = '{enter the private key}';
		$__clientID = '{enter the client ID}';

		private $google;
		private $helper;

		public function __construct(){
			$this->google = new Google_Client();
			$this->google->setApplicationName("rtcamp test application");
			$this->google->setDeveloperKey('My developer key');
		}
	}

?>
