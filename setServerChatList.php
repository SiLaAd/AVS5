<?php
include './helperFunctions.php';

if (isset($_GET['chatraum'])) {
    
    $semaphore = initSema();
    while (!$semaphore) {
        echo "Failed on sem_get().\n";
    }
        $chatRaum = $_GET['chatraum'];
        $message = $_GET['message'];
        $message = json_decode($message);
        
    $filepath = "./chatRooms/$chatRaum/";
    $datei = fopen($filepath . "$chatRaum.txt", "a+");   // Datei öffnen
    $content = file($filepath . "$chatRaum.txt");

    $messageArray = unserialize(urldecode($content[0]));

    $messageArray[] = $message;


    file_put_contents($filepath . "$chatRaum.txt", "");
    fwrite($datei, urlencode(serialize($messageArray)));
        
        
        //fwrite($datei, "$ipAdress");
        fclose($datei);
         sem_release($semaphore);
        
        
        
        
} else {
    echo "Nix Raum!";
}