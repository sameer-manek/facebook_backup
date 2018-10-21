<?php

class Drive{

    private $fileRequest;
    private $mimeType;
    private $filename;
    private $path;
    private $client;


    public function __construct($client){
        // implementing a simple copy constructor!
        $this->client = $client;
    }

    public function init($file){
        $client = $this->client; 
	$client->refreshToken($refreshToken);
	$tokens = $client->getAccessToken();
	$client->setAccessToken($tokens);
	$client->setDefer(true);
	$this->processFile($file);
    }

    public function processFile($fileRequest){
        $path_parts = pathinfo($fileRequest);
	$this->path = $path_parts['dirname'];
	$this->fileName = $path_parts['basename'];
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$this->mimeType = finfo_file($finfo, $fileRequest);
	finfo_close($finfo);
	echo "Mime type is " . $this->mimeType . "\n";
	$this->upload($fileRequest);
    }

    public function upload($fileRequest){
        $client = $this->client;
        $chunksize = 1*1024*1024; // 1 MB
        $file = new Google_Service_Drive_DriveFile(array(
            'name' => $this.filename
        ));
        
        $mimetype = $this->mimeType;
        
        $service = new Google_Service_Drive($client);
        $request = $service->files->insert($file);

        $media = new Google_Http_MediaFileUpload(
	    $client,
	    $request,
	    $mimeType,
	    null,
	    true,
	    $chunkSizeBytes
        );

        $media->setFileSize(filesize($fileRequest));
        $status = false;
        // handling high res images (4k, HDR, etc)
        $handle = fopen($fileRequest, "rb");

        while(!status && !feof($handle)){
            // processing 1MB at a time
            $chunk = fread($handle, $chunksize);
            $status = $media->nextChunk($chunk);
        }

        $result = false;
        if($status != false){
            $result = $status; // Just in case something unexpected shows up.
        }

        fclose($handle);

        $client->setDefer(false);
        return true;
    }

}
