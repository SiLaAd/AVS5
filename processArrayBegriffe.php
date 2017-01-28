<?php

include './classes/begriffClass.php';

if (isset($_GET['flag'])){
    
    if ($_GET['flag']==0){
        writeBegriffe();
        
    }else{
        
        readBegriff();
        
    }
    
}

function writeBegriffe(){

if(isset($_GET['begriffArray'])){
    $Abegriffarray = json_decode($_GET['begriffArray']); 
  }
    
 $anzahlBegriffe = count($Abegriffarray);
    
for ($i = 0; $i <= $anzahlBegriffe; $i++) {
    for ($a = 1; $a <= 1; $a++){
 
    $index=$Abegriffarray[$i]->index + $a;    
    $begriffB = $Abegriffarray[$i]->begriff;    
       
    
    $temp = new begriffClass($index,$begriffB);
    
        
    array_push($Abegriffarray,$temp);
    
    
}
}

$finalBegriffArray = Array();

foreach($Abegriffarray as $begriffA){
    
$tempa = new begriffClass($begriffA ->index,$begriffA->begriff);

$context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
//$link = json_encode(file_get_contents("https://de.wikipedia.org/w/index.php?title=Computer&printable=yes",false,$context));

$url = "https://de.wikipedia.org/w/index.php?title=". $begriffA->begriff ."&printable=yes";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_USERAGENT, 'MyBot/1.0 (http://www.mysite.com/)');

$result = curl_exec($ch);

if (!$result) {
  exit('cURL Error: '.curl_error($ch));
}

$tempa ->addInhalt($result);
//$tempa ->addInhalt($link);

 array_push($finalBegriffArray,$tempa);

}
    $datei = fopen("./Begriffe.txt", "w");
    file_put_contents("./Begriffe.txt", "");
    fwrite($datei, urlencode(serialize($finalBegriffArray)));
    //fwrite($datei, "$ipAdress");
    fclose($datei);


}

    
   function readBegriff(){
     
    $datei = fopen("./Begriffe.txt", "a+");   // Datei öffnen
    $content = file("./Begriffe.txt");

    $begriffArray = unserialize(urldecode($content[0]));
    
    
    $searchIndex = $_GET['index'];
    $ab=0;
    $i=0;
    
    foreach ($begriffArray as $begriff) {
        if ($begriff->index == $searchIndex){
        $begriff->inhalt;
        $ab = $i;
        
        }
        $i++;
    }
    
    echo $begriffArray[$ab]->inhalt;
    
       
   } 