<?php

	require_once('./vendor/autoload.php');
	class Facebook{

		private $fb;

		private $helper;

		public function __construct(){
			$this->fb = new \Facebook\Facebook([
			  'app_id' => '2223553694382717',
			  'app_secret' => 'f96ad3b1cd5a859e15482d042ba0a47c',
			  'default_graph_version' => 'v3.1',
			]);

			$this->helper = $this->fb->getRedirectLoginHelper();
		}

		public function Login(){
			$permissions = ['user_photos'];
			$loginUrl = $this->helper->getLoginUrl('https://fbrtc.sameer-manek.com', $permissions);

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
			  $response = $this->fb->get(
			    '/me/albums?fields=picture,id,name',
			    $_SESSION['fb_access_token']
			  );
			} catch(FacebookExceptionsFacebookResponseException $e) {
			  echo 'Graph returned an error: ' . $e->getMessage();
			  exit;
			} catch(FacebookExceptionsFacebookSDKException $e) {
			  echo 'Facebook SDK returned an error: ' . $e->getMessage();
			  exit;
			}
			$edge = $response->getGraphEdge();
			$nodes = array();
			foreach ($edge as $node) {
				$nodes[] = $node->asArray();
			}

			return $nodes;
		}

		public function get_album_info($album){
			try {
				$res = $this->fb->get("/".$album, $_SESSION['fb_access_token']);
			} catch(Exception $e){
				echo "there was some internal problem";
				exit();
			}

			return $res->getGraphObject()->asArray();
		}

		public function get_photos($album){

			// fetching photos :  {album-id}/photos?fields=picture
			try {
				$res = $this->fb->get("/".$album."/photos?fields=picture", $_SESSION['fb_access_token']);
			} catch(Exception $e){
				echo "there was some internal problem";
				exit();
			}

			$nodes = array();
			$edge = $res->getGraphEdge();

			foreach($edge as $node){
				$nodes[] = $node->asArray();
			}

			return $nodes;
		}

	}
