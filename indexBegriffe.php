<?php

require_once 'HTTP/Request2.php';
include './helperFunctions.php';
include './classes/begriffClass.php';
include './classes/userClass.php';

$ObjektBegriffe = array();
$indexArray = array();
$filepath = "./user/";
$path = opendir($filepath);
array_push($ObjektBegriffe, new begriffClass(0, "Computer"));
array_push($ObjektBegriffe, new begriffClass(50, "Server"));
array_push($ObjektBegriffe, new begriffClass(100, "Tier"));
array_push($ObjektBegriffe, new begriffClass(150, "Bier"));

$serverCount = countServer();
$begriffArray = partition($ObjektBegriffe, $serverCount);

while ($file = readdir($path)) {
    if ($file != "." && $file != "..") {
        $tempString = fread(fopen($filepath . $file, 'r'), filesize($filepath . $file));
        $fileWoEx[] = unserialize($tempString)->ip;
    }
}

$i = 0;
foreach ($fileWoEx as $ip) {
    if ($ip != $_SERVER['SERVER_ADDR']) {
        $server_url = 'http://' . $ip . '/avs4/processArrayBegriffe.php';
        $send = new HTTP_Request2($server_url, HTTP_Request2::METHOD_GET, array('use_brackets' => true));
        $url = $send->getUrl();
        $url->setQueryVariables(array(
            'begriffArray' => json_encode($begriffArray[$i])
        ));
        $responses[] = $send->send();
        foreach ($begriffArray[$i] as $spliter) {
            $spliter -> addIp($ip);
        }
        $i++;
    }
}
 //GET Antwort
    foreach ($responses as $response) {
       echo("Server:". $response->getBody()); 
    }






