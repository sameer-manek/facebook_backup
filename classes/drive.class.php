<?php

require_once (__DIR__."/../vendor/autoload.php");
//require_once (__DIR__."/google.class.php");

class Drive{

    private static $instance;
    private $fileRequest;
    private $mimeType;
    private $filename;
    private $path;
    private $client;

    private $clientId = "190091229941-64um6ko0lskj5g0d8jk67e09quo3iljv.apps.googleusercontent.com";
    private $clientSecret = "HFXvf3bYykz2lDpp2OfR2T0M";

    private final function __construct(){
        $this->client = new Google_Client();
    	$this->client->setApplicationName("fb album backup tool");
    	$this->client->setClientId($this->clientId);
    	$this->client->setClientSecret($this->clientSecret);
    	$this->client->setRedirectUri("https://fbrtc.sameer-manek.com/fb_caller.php?i=google_callback");
    	$this->client->setScopes(array('https://www.googleapis.com/auth/drive.file'));
    	$this->client->setAccessType("offline");
    	$this->client->setApprovalPrompt('force');
    }

    public static function getInstance(){
        if(self::$instance == null) {
            self::$instance = new Drive();
        }

        return self::$instance;
    }

    public function getAgent(){
        return $this->client;
    }

    public function uploadAlbum($fb, $album){
            $nodes = $fb->get_photos($album);
            $client = new GearmanClient();
            $client->addServer();
            foreach ($nodes as $node) {
                $client->addTask('init', $node['picture']);
            }
            $client->runTasks();
    }

    public function init($file){
        $at = $_SESSION['google_access_token']['access_token'];
        $this->fileRequest = $file;
        $client = $this->client;
    	$client->refreshToken($refreshToken);
    	$tokens = $client->getAccessToken();
    	$client->setAccessToken($tokens);
    	$client->setDefer(true);
        $this->client->setAccessToken($at);
    	$this->processFile();
    }

    public function processFile(){
        $fileRequest = $this->fileRequest;
        $path_parts = pathinfo($this->fileRequest);
    	$this->path = $path_parts['dirname'];
    	$this->fileName = $path_parts['basename'];
    	$finfo = finfo_open(FILEINFO_MIME_TYPE);
    	$this->mimeType = finfo_file($finfo, $fileRequest);
    	finfo_close($finfo);
    	echo "Mime type is " . $this->mimeType . "\n";
    	$this->upload();
    }

    public function upload(){
        $service = new Google_Service_Drive($client);

        //Insert a file
        $file = new Google_Service_Drive_DriveFile();
        $file->setName(rand().'.jpg');
        $file->setDescription('A test document');
        $file->setMimeType('image/jpeg');

        $data = file_get_contents($this->fileRequest);

        $createdFile = $service->files->create($file, array(
              'data' => $data,
              'mimeType' => 'image/jpeg',
              'uploadType' => 'multipart'
        ));
    }

}
