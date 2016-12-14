<?php

include './classes/userClass.php';
include './classes/messageClass.php';
include './helperFunctions.php';

if (isset($_POST['username'])) {
    $username = $_POST['username'];
} else {
    $username = 'username not delivered';
}

if (isset($_POST['textMessage'])) {
    $textMessage = $_POST['textMessage'];
} else {
    $textMessage = 'Nachricht not delivered';
}
if (isset($_POST['chatRaum'])) {
    $chatRaum = $_POST['chatRaum'];
} else {
    $chatRaum = 'chatraum not delivered';
}
if (isset($_POST['flag'])) {
    $flag = $_POST['flag'];
} else {
    $flag = 'flag not delivered';
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
} else {
    $password = '';
}


if ($_SERVER['REMOTE_ADDR'] == '::1') {
    $ipAdress = getHostByName(getHostName());
} else {
    $ipAdress = $_SERVER['REMOTE_ADDR'];
}
if (isset($_POST['pcName'])) {
    $pcName = $_POST['pcName'];
} else {
    $pcName = '';
}

switch ($flag) {
    case'sendMessage':
        writeChatData($username, $textMessage, $chatRaum);
        break;
    case 'addUser':
        createFile($username, $ipAdress, $password, $pcName);
        break;
    default:
}

function createFile($username, $ipAdress, $password, $pcName) {
    $filepath = './user/';
    $hstring = "_";
    if (file_exists($filepath . "$username$hstring$password.txt")) {
        echo("Benutzer schon vorhanden.");
    } elseif (glob($filepath . $username . '*.txt')) {
        echo("Benutzer ist schon vorhanden.");
    } else {

        $datei = fopen($filepath . "$username$hstring$password.txt", "w");
        $user = new userClass($username, $ipAdress);
        fwrite($datei, serialize($user));
        //fwrite($datei, "$ipAdress");
        fclose($datei);
    }

    echo json_encode(array(
        'username' => $username,
        'password' => $password,
        'ipAdress' => $ipAdress,
        'pcName' => $pcName,
    ));
}

function writeChatData($username, $nachricht, $chatRaum) {
    $semaphore = initSema();
    while (!$semaphore) {
        echo "Failed on sem_get().\n";
    }
    sem_acquire($semaphore);
    $message = new messageClass($nachricht, $username);
    $filepath = "./chatRooms/$chatRaum/";
    $datei = fopen($filepath . "$chatRaum.txt", "a+");   // Datei öffnen
    $content = file($filepath . "$chatRaum.txt");

    $messageArray = unserialize(urldecode($content[0]));

    $messageArray[] = $message;


    file_put_contents($filepath . "$chatRaum.txt", "");
    fwrite($datei, urlencode(serialize($messageArray)));   // Daten schreiben, Zeilenumbruch
    sem_release($semaphore);
    writeChatServerData($username, $nachricht, $chatRaum);
}

function writeChatServerData($username, $nachricht, $chatRaum) {
    $semaphore = initSema();
    while (!$semaphore) {
        echo "Failed on sem_get().\n";
    }
    sem_acquire($semaphore);
    //Multicast 
    $filepath = "./chatRooms/$chatRaum/";
    $filepathServer = './serverList/';
    $contentServer = file($filepathServer . "serverliste.txt");
    $ipServerArray = unserialize(urldecode($contentServer[0]));
    //GetChatData
    $content = file($filepath . "$chatRaum.txt");
    $message = new messageClass($nachricht, $username);
    $messageArray= unserialize(urldecode($content[0]));
    $messageArray[] = $message;
    $sendObject = serialize($messageArray);
    //Senden
    foreach ($ipServerArray as $ipsA) {
        if ($ipsA != $_SERVER['SERVER_ADDR']) {
            $server_url = 'http://' . $ipsA . '/AVS3/setServerChatList.php';
            $send = new HTTP_Request2($server_url, HTTP_Request2::METHOD_GET, array('use_brackets' => true));
            $url = $send->getUrl();
            $url->setQueryVariables(array(
                'message' => json_encode($sendObject),
                'chatraum' => $chatRaum
            ));
            $send->send();
        }
    }
    sem_release($semaphore);
}
