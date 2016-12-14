<?php


//if (isset($_GET['ipList'])) {
        $chatRaum = $_GET['chatraum'];
        $message = $_GET['message'];
        $messageListe = json_decode($message);
        $filepath = './chatRooms/'.$chatRaum;
        
        $datei = fopen($filepath . $chatRaum.".txt", "w");
        fwrite($datei,serialize($messageListe));
        //fwrite($datei, "$ipAdress");
        fclose($datei);
        
        
        
        
        
//} else {
//    