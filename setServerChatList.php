<?php


if (isset($_GET['chatraum'])) {
    
    $semaphore = initSema();
    while (!$semaphore) {
        echo "Failed on sem_get().\n";
    }
        $chatRaum = $_GET['chatraum'];
        $messageListe = $_GET['message'];
        $messageListe = json_decode($messageListe);
        $filepath = './chatRooms/'.$chatRaum.'/';
        
        $datei = fopen($filepath . $chatRaum.".txt", "w");
        file_put_contents($filepath . "$chatRaum.txt", "");
        fwrite($datei,urlencode(serialize($messageListe)));
        //fwrite($datei, "$ipAdress");
        fclose($datei);
         sem_release($semaphore);
        
        
        
        
} else {
    echo "Nix Raum!";
}