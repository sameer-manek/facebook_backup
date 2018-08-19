<?php

	require_once('./vendor/autoload.php');
	class Facebook{

		private $fb;

		private $helper;

		public function __construct(){
			$this->fb = new \Facebook\Facebook([
			  'app_id' => '222413885297299',
			  'app_secret' => '85e84df32c06c5801bba06f3710b3e0f',
			  'default_graph_version' => 'v3.1',
			]);

			$this->helper = $this->fb->getRedirectLoginHelper();
		}

		public function Login(){
			$permissions = ['user_photos'];
			$loginUrl = $this->helper->getLoginUrl('https://facebook.rtc/callback.php', $permissions);

			return $loginUrl;
		}

		// Todo : objectify callback

		public function get_user_info(){
			try {
				$res = $this->fb->get("/me", $_SESSION['fb_access_token']);
			} catch(Exception $e){
				echo "there was some internal problem";
				exit();
			}

			return $res->getGraphObject()->asArray();
		}

		public function get_user_albums(){
			try {
				$res = $this->fb->get("/me?fields=albums", $_SESSION['fb_access_token']);
			} catch(Exception $e){
				echo "there was some internal problem";
				exit();
			}

			return $res->getGraphObject()->asArray();
		}

		public function get_photos($album){
			try {
				$res = $this->fb->get("/".$album."/photos", $_SESSION['fb_access_token']);
			} catch(Exception $e){
				echo "there was some internal problem";
				exit();
			}

			return $res->getGraphObject()->asArray();
		}

	}	