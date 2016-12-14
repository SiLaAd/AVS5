<?php
include './helperFunctions.php';
if (isset($_GET['ipList'])) {
    $semaphore = initSema();
    while (!$semaphore) {
        echo "Failed on sem_get().\n";
    }
    $ipListe = $_GET['ipList'];
    $ipListe = json_decode($ipListe);
    $filepath = './serverList/';

    $datei = fopen($filepath . "serverliste.txt", "w");
    fwrite($datei, serialize($ipListe));
    //fwrite($datei, "$ipAdress");
    fclose($datei);
    sem_release($semaphore);
} else {
    $ipListe = 'nixListe';
}
    
    
    
    
    

    
    
