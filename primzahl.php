<?php
include './classes/userClass.php';
include './classes/messageClass.php';
include './helperFunctions.php';

countServer();

function countServer() {
    $filepath = "./serverList/";
    $path = opendir($filepath);
    $countServer=0;
    $fp = fopen($filepath . "serverliste.txt", 'r');
    $content = file($filepath . "serverliste.txt");
    $serverArray=unserialize(urldecode($content[0]));
    //Senden
    foreach ($serverArray as $ipsA) {
        if ($ipsA != $_SERVER['SERVER_ADDR']) {
              $countServer = $countServer+1;
//            $server_url = 'http://'.$ipsA.'/AVS3/setServerChatList.php';
//            $send = new HTTP_Request2($server_url, HTTP_Request2::METHOD_GET, array('use_brackets' => true));
//            $url = $send->getUrl();
//            $url->setQueryVariables(array(
//                'message' => json_encode($message),
//                'chatraum' => $chatRaum
//            ));
//            $send->send();
        }
    }
    //$count = count($serverArray);
    echo($countServer);
    
}

