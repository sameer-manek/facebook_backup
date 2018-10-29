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

    while($worker->work());

    function downloader($job){
        $url = $job->workload();
        $saveto = __DIR__."/tmp/".rand().".jpg";
        $log = fopen("./worker.log", "a");
        $data = file_get_contents($url);
        fwrite($log, "downloaded contents from ".$url."\n");
        $file = fopen($saveto, "w+");
        fwrite($file, $data);
        fclose($file);
        fwrite($log, "stored contents to ".$saveto."\n");
        // upload the file
        fwrite($log, "starting upload to the drive\n");
        $client = Drive::getInstance();
        $client->init($saveto);
        fwrite($log, "uploaded to user's drive\n");
        fclose($log);
    }



?>
