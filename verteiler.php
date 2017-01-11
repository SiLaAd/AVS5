<?php
require_once 'HTTP/Request2.php';
include './classes/userClass.php';
include './classes/messageClass.php';
include './helperFunctions.php';
//include './levi.php';

$wordsArray = array();
$words = splitInput($_POST['word']);
$handle = fopen("words.txt", "r");
$i = 1;
$ai = 0;
ini_set('memory_limit', '-1');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        while ($i < 1000) {
            $ai *= $i;
            $i++;
        }
        array_push($wordsArray, $line);
    }
    fclose($handle);
} else {
    // error opening the file.
}
$servers = countServer();
echo("Anzahl Server: " . $servers);
if ($servers == 0) {
    levenshteins($wordsArray, $words);
} else {
     $splitArray = array();
     $splitArray = partition($words, $servers);
     
     
    $filepath = "./user/";
    $path = opendir($filepath);

    while ($file = readdir($path)) {
        if ($file != "." && $file != "..") {
            $tempString = fread(fopen($filepath . $file, 'r'), filesize($filepath . $file));
            $fileWoEx[] = unserialize($tempString)->ip;
        }
    }
    $i=0;
    foreach ($fileWoEx as $ip) {
        if($ip != $_SERVER['SERVER_ADDR']) {
        $server_url = 'http://'.$ip.'/avs4/levi.php';
        $send = new HTTP_Request2($server_url, HTTP_Request2::METHOD_GET, array('use_brackets' => true));
        $url = $send ->getUrl();
        $url->setQueryVariables(array(
            'wordsArray' => json_encode($wordsArray),
            'word' => json_encode($splitArray[$i])
        ));
        //$send ->send();
        $response = $send->send();
        $i++;
        
        }
    }
   
    //GET Antwort
    echo($response->getBody());
}



function splitInput($input){
    $words = explode(",", $input);
    return $words;
}