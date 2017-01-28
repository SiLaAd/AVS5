<?php

require_once 'HTTP/Request2.php';
include './helperFunctions.php';
include './classes/begriffClass.php';
include './classes/userClass.php';

if (isset($_POST['word'])) {

    $datei = fopen("objektbegriffe.txt", "a+");   // Datei öffnen
    $content = file("objektbegriffe.txt");

    $ObjektBegriffe = unserialize(urldecode($content[0]));



    $eingegebenesWort = $_POST['word'];
    $hash = hashMaker($eingegebenesWort);
    $index;
    $ip = "";
    foreach ($ObjektBegriffe as $begriffe) {
        if ($hash == $begriffe->begriff) {
            $index = $begriffe->index;
            $ip = $begriffe->ip;
        }
    }
    $server_url = 'http://' . $ip . '/avs4/processArrayBegriffe.php';
    $send = new HTTP_Request2($server_url, HTTP_Request2::METHOD_GET, array('use_brackets' => true));
    $url = $send->getUrl();
    $url->setQueryVariables(array(
        'index' => json_encode($index),
        'flag' => 1
    ));
    $response = $send->send();
    $datei = fopen("responseData.html", "a+");
    file_put_contents("responseData.html", "");
    fwrite($datei, $response->getBody());
    
    
    echo '<a href="http://172.22.0.4/AVS5/responseData.html" target="_blank">Click Me!</a>';
    //GET Antwort
    
        
} else {
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
                'begriffArray' => json_encode($begriffArray[$i]),
                'flag' => 0
            ));
            $responses[] = $send->send();
            foreach ($begriffArray[$i] as $spliter) {
                $spliter->addIp($ip);
            }
            $i++;
        }
    }
    //GET Antwort
    foreach ($responses as $response) {
        echo($response->getBody());
    }

    foreach ($ObjektBegriffe as $objekte) {
        $hash = hashMaker($objekte->begriff);
        $objekte->begriff = $hash;
    }

    $datei = fopen("objektbegriffe.txt", "a+");
    file_put_contents("objektbegriffe.txt", "");
    fwrite($datei, urlencode(serialize($ObjektBegriffe)));
}
?>


