<?php


//if (isset($_GET['ipList'])) {
        $ipListe = $_GET['ipList'];
        $ipListe = json_decode($ipListe);
        $filepath = './serverList/';
        
        $datei = fopen($filepath . "serverliste.txt", "w");
        fwrite($datei,serialize($ipListe));
        //fwrite($datei, "$ipAdress");
        fclose($datei);
        
        
        
        
        
//} else {
//        $ipListe = 'nixListe';
//    }
    
    
    
    
    

    
    
