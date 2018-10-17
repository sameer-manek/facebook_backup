<?php
class Drive{

	//variables
	private $fileRequest;
	private $mimeType;
	private $filename;
	private $path;
	private $client;


	public function __construct($client){
        // implementing a simple copy constructor!
		$this->client = $client;
	}


	public function initialize($node){
		//$this->fileRequest = './temp/jpg';
		$refreshToken = $_SESSION['google_access_token']['refresh_token'];
		$this->client->refreshToken($refreshToken);
		$tokens = $this->client->getAccessToken();
		$this->client->setAccessToken($tokens);

		$this->client->setDefer(true);
		// download file
		$url = $node['picture'];

		$curlCh = curl_init();
		curl_setopt($curlCh, CURLOPT_URL, $url);
		curl_setopt($curlCh, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlCh, CURLOPT_SSLVERSION,3);
		$curlData = curl_exec ($curlCh);
		curl_close ($curlCh);

		$downloadPath = __DIR__."/temp/temp.jpg";
		$file = fopen($downloadPath, "w+");
		fputs($file, $curlData);
		fclose($file);
		//
		$this->processFile();
	}

	public function processFile(){
		$fileRequest = $this->fileRequest;
		$this->path = __DIR__."/temp/temp.jpg";
		$this->fileName = "temp.jpg";
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$this->mimeType = "image/jpeg";
		finfo_close($finfo);

		echo "Mime type is " . $this->mimeType . "\n";

		$this->upload();

	}

	public function upload(){
		$client = $this->client;

		$file = new Google_Service_Drive_DriveFile();
		$file->title = $this->fileName;
		$chunkSizeBytes = 1 * 1024 * 1024;

		$fileRequest = $this->fileRequest;
		$mimeType = $this->mimeType;

		$service = new Google_Service_Drive($client);
		$request = $service->files->insert($file);
		// Create a media file upload to represent our upload process.
		$media = new Google_Http_MediaFileUpload(
		  $client,
		  $request,
		  $mimeType,
		  null,
		  true,
		  $chunkSizeBytes
		);
		$media->setFileSize(filesize($fileRequest));
		// Upload the various chunks. $status will be false until the process is
		// complete.
        // maybe I should consider using an async task queue on redis in version 1.1 :P But, rn, I just want the job!
		$status = false;
		$handle = fopen($fileRequest, "rb");

		// start uploading
		echo "Uploading: " . $this->fileName . "\n";

		$filesize = filesize($fileRequest);

		// while not reached the end of file marker keep looping and uploading chunks
		while (!$status && !feof($handle)) {
			$chunk = fread($handle, $chunkSizeBytes);
			$status = $media->nextChunk($chunk);
		}

		// The final value of $status will be the data from the API for the object
		// that has been uploaded.
		$result = false;
		if($status != false) {
		  $result = $status;
		}
		fclose($handle);
		// Reset to the client to execute requests immediately in the future.
		$client->setDefer(false);
	}

}
