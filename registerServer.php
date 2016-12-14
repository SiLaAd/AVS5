<?php

include './classes/userClass.php';
require_once 'HTTP/Request2.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
registerServer();

function registerServer() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_GET['name'])) {
        $server_name = $_GET['name'];
    } else {
        $server_name = 'nix_servername';
    }
    if (isset($_GET['password'])) {
        $server_password = $_GET['password'];
    } else {
        $server_password = 'nixpassword';
    }

    $filepath = './user/';
    $hstring = "_";
    if (file_exists($filepath . "$ip$hstring$server_name.txt")) {
        echo("Server ist schon vorhanden.");
    } elseif (glob($filepath . $ip . '*.txt')) {
        echo("Server ist schon vorhanden.");
    } else {
        $datei = fopen($filepath . "$ip$hstring$server_name.txt", "w");
        $user = new userClass($server_name, $ip);
        fwrite($datei, serialize($user));
        //fwrite($datei, "$ipAdress");
        fclose($datei);
        sendeServer();
    }
}

function sendeServer() {
    $filepath = "./user/";
    $path = opendir($filepath);

    while ($file = readdir($path)) {
        if ($file != "." && $file != "..") {
            $tempString = fread(fopen($filepath . $file, 'r'), filesize($filepath . $file));
            $fileWoEx[] = unserialize($tempString)->ip;
        }
    }

    foreach ($filewoex as $ip) {

        $send = new HTTP_Request2('http://.$ip./AVS3/setServerList.php', HTTP_Request2::METHOD_GET, array('use_brackets' => true));

        $url = $send->getUrl();
        $url->setQueryVariables(array(
            'ipList' => $fileWoEx,
        ));
    }

    //Rückgabe des Dateinamen und des Inhalts
    echo json_encode(array(
        'files' => $fileWoEx
    ));
}