<?php
/*
 *  How this is gonna work:
 *      created a background tasks called downloader and uploader
 *      init calls for download (asynchronous calls for each image URL in the album)
 *      downloader calls for upload (synchronous dispatch to upload the downloaded image and then delete it!)
 *      returns boolean for true or false.
 *
 *      SIMPLE!
 * */
    
require_once "../classes/drive.class.php";

    $worker = new GearmanWorker();
    $worker-> addServer('localhost');

    $worker->addFunction('init', 'downloader');

    $worker->addFunction('upload', 'uploader');

    function downloader($job){
        // downloads the images from facebook
        $data = unserialize($job->workload()); // receives serialized data
        $url = $data->url;
        $file = rand().'.jpg';
        $saveto  = __DIR__.'/tmp/'.$file;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $raw=curl_exec($ch);
        curl_close ($ch);
        if(file_exists($saveto)){
            unlink($saveto);
        }
        $fp = fopen($saveto,'x');
        fwrite($fp, $raw);
        fclose($fp);
        // create a gearman client to upload image to google drive
        $client = new GearmanClient();
        $client->addServer();
        $data['file'] = $saveto;
        return $client->doNormal('upload', serialize($data)); // ensure synchronous dispatch

        // can implement a post request return call, to denote a loading point on a loading bar.
    }

    function uploader($job){
        $data = unserialize($job->workload());
        $file = $data->file;
        $google = $data->google; 
        $drive = new Drive($google);
        return $drive->init($file); // returns boolean
    }
?>
