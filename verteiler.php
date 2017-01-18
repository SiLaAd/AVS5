<?php

require_once 'HTTP/Request2.php';
include './classes/userClass.php';
include './classes/messageClass.php';
include './helperFunctions.php';
include './levi.php';

$wordsArray = array();
$words = splitInput($_POST['word']);
$i = 1;
$ai = 0;
ini_set('memory_limit', '-1');
$servers = countServer();
echo("Anzahl Server: " . $servers);
if ($servers == 0) {
    levenshteins($words);
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
    $i = 0;
    $nodes = array();
    foreach ($fileWoEx as $ip) {
        if ($ip != $_SERVER['SERVER_ADDR']) {
              array_push($nodes, 'http://' . $ip . '/avs4/levi.php');
//            $server_url = 'http://' . $ip . '/avs4/levi.php';
//            $send = new HTTP_Request2($server_url, HTTP_Request2::METHOD_GET, array('use_brackets' => true));
//            $url = $send->getUrl();
//            $url->setQueryVariables(array(
//                'wordSend' => json_encode($splitArray[$i])
//            ));
//            //$send ->send();
//            $responses[] = $send->send();
//            echo(date('h:m:s'));
//            $i++;
        }
    }

    //parrelel curl
    $node_count = count($nodes);
    $curl_arr = array();
    $master = curl_multi_init();

    for ($i = 0; $i < $node_count; $i++) {
        $url = $nodes[$i];
        $curl_arr[$i] = curl_init($url);
        curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($master, $curl_arr[$i]);
    }

    do {
        curl_multi_exec($master, $running);
    } while ($running > 0);


    for ($i = 0; $i < $node_count; $i++) {
        $results[] = curl_multi_getcontent($curl_arr[$i]);
    }
    print_r($results);


    //GET Antwort
    foreach ($responses as $response) {
        echo("Server:" . $response->getBody());
    }
}

function splitInput($input) {
    echo("test");
    $words = explode(",", $input);
    return $words;
}
