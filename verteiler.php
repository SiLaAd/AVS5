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

    $urlarray = array();
    foreach ($fileWoEx as $ip) {
        if ($ip != $_SERVER['SERVER_ADDR']) {
            array_push($urlarray, 'http://' . $ip . '/avs4/levi.php');
        }
    }



    $node_count = count($urlarray);
    $curl_arr = array();
    $master = curl_multi_init();

    for ($i = 0; $i < $node_count; $i++) {
        $data = array('wordslist' => json_encode($splitArray[$i]));
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= $key . '=' . $value . '&';
        }
        rtrim($fields, '&');

        $url = $urlarray[$i];
        $curl_arr[$i] = curl_init($url);
        curl_setopt($curl_arr[$i], CURLOPT_URL, $url);
        curl_setopt($curl_arr[$i], CURLOPT_POST, count($data));
        curl_setopt($curl_arr[$i], CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, 1);
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
//    foreach ($responses as $response) {
//        echo("Server:" . $response->getBody());
//    }
}

function splitInput($input) {
    $words = explode(",", $input);
    return $words;
}

function post_to_url($url, $data) {
    $fields = '';
    foreach ($data as $key => $value) {
        $fields .= $key . '=' . $value . '&';
    }
    rtrim($fields, '&');


    $post = curl_init();

    curl_setopt($post, CURLOPT_URL, $url);
    curl_setopt($post, CURLOPT_POST, count($data));
    curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($post);

    curl_close($post);
    return $result;
}
