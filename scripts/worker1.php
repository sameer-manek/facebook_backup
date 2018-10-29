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

        $data = file_get_contents($url);

        $file = fopen($saveto, "w+");
        fwrite($file, $data);
        fclose($file);

        // upload the file

        $client = Drive::getObj();

    }



?>
