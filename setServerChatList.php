<?php


//if (isset($_GET['ipList'])) {
        $chatRaum = $_GET['chatraum'];
        $messageListe = $_GET['message'];
        $messageListe = json_decode($messageListe);
        $filepath = './chatRooms/';
        
        $datei = fopen($filepath . $chatRaum.".txt", "w");
        file_put_contents($filepath . "$chatRaum.txt", "");
        fwrite($datei,urlencode(serialize($messageListe)));
        //fwrite($datei, "$ipAdress");
        fclose($datei);
        
        
        
        
        
//} else {
//    