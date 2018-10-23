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
    
require_once __DIR__."/../classes/drive.class.php";

    $worker = new GearmanWorker();
    $worker-> addServer('localhost');

    $worker->addFunction('init', 'downloader');

    $worker->addFunction('upload', 'uploader');

    while($worker->work());

    function downloader($job){
        // downloads the images from facebook
        $data = unserialize($job->workload()); // receives serialized data
        $url = $data['url'];
        $log = __DIR__.'/worker.log';
        $file = rand().'.jpg';
        $saveto  = __DIR__.'/tmp/'.$file;
        $ch = curl_init($url);
        $handleLog = fopen($log, 'a');
        fwrite($handleLog, "started downloading from source: ".$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $raw=curl_exec($ch);
        curl_close ($ch);
        fwrite($handleLog, "downloaded ".$url." as ".$saveto);
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
        fclose($handleLog);
        // can implement a post request return call, to denote a loading point on a loading bar.
    }

    function uploader($job){
        $log = __DIR__.'/worker.log';
        $handleLog = fopen($log, 'a');
        fwrite($handleLog, "unloading data");
        $data = unserialize($job->workload());
        $file = $data->file;
        $google = $data->google; 
        $drive = new Drive($data);
        fwrite($handleLog, "create drive obj, moving your files");
        fclose($handleLog);
        return $drive->init($file); // returns boolean
    }
?>
