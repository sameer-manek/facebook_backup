<?php

    require_once('./vendor/autoload.php');
    //require_once('./vendor/google/apiclient/src/Google/autoload.php');
    //require_once('drive.class.php');

    class Google{
	private $google;
	private $clientId = "190091229941-64um6ko0lskj5g0d8jk67e09quo3iljv.apps.googleusercontent.com";
	private $clientSecret = "HFXvf3bYykz2lDpp2OfR2T0M";

	public function __construct(){
	    // setting up variables
	    $this->google = new Google_Client();
	    $this->google->setApplicationName("rtcamp test application");
	    $this->google->setClientId($this->clientId);
	    $this->google->setClientSecret($this->clientSecret);
	    $this->google->setRedirectUri("https://fbrtc.sameer-manek.com/fb_caller.php?i=google_callback");
	    $this->google->setScopes(array('https://www.googleapis.com/auth/drive.file'));
	    $this->google->setAccessType("offline");
	    $this->google->setApprovalPrompt('force');
	}

	public function getAgent(){
	    // get the google api agent
	    return $this->google;
        }

        

        public function uploadAlbum($fb, $album){
            $nodes = $fb->get_photos($album);
            $client = new GearmanClient();
            $client->addServer();
            foreach ($nodes as $node) {
                echo "processing ".$node['picture'];
                // creating the array here
                $url = $node["picture"];
                $obj = array();
                $obj['url'] = $url;
                $obj['google_client_id'] = $this->clientId;
                $obj['google_client_secret'] = $this->clientSecret;
                $obj['google_access_token'] = $_SESSION['google_access_token'];
                // stack the tasks
                $client->addTask('init', serialize($obj));
            }
            $client->runTasks();
        }
    }

?>
