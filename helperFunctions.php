<?php

if (isset($_POST['username'])) {
    $username = $_POST['username'];
} else {
    $username = 'username not delivered';
}

if (isset($_POST['password'])) {
    $password = $_POST['password'];
} else {
    $password = 'password not delivered';
}
if (isset($_POST['flag'])) {
    $flag = $_POST['flag'];
} else {
    $flag = 'flag not delivered';
}

switch($flag) {
    case'login':
         checkPerm($username, $password, $flag);
                break;
            default:
        }





function checkPerm($username, $password, $flag) {
    $filepath = './user/';
    $hstring = '_';
    $returnVar = 0;
    if (file_exists($filepath . "$username$hstring$password.txt")) {
        switch ($flag) {
            case 'login':
                $returnVar = 1;
                break;
            default:
        }
    }
    echo json_encode(array(
        'returnVar' => $returnVar
    ));
    return $returnVar;
}
function initSema() {
        $key = 666;
        $max = 1;
        $permissions = 0666;
        $autoRelease = 1;
        return $semaphore = sem_get($key, $max, $permissions, $autoRelease);
}

function countServer() {
    $filepath = "./serverList/";
    $path = opendir($filepath);
    $countServer = 0;
    $fp = fopen($filepath . "serverliste.txt", 'r');
    $content = file($filepath . "serverliste.txt");
    $serverArray = unserialize(urldecode($content[0]));
    //Senden
    foreach ($serverArray as $ipsA) {
        if ($ipsA != $_SERVER['SERVER_ADDR']) {
            $countServer = $countServer + 1;
        }
    }
    return $countServer;
}

/**
 * 
 * @param Array $list
 * @param int $p
 * @return multitype:multitype:
 * @link http://www.php.net/manual/en/function.array-chunk.php#75022
 */
function partition(Array $list, $p) {
    $listlen = count($list);
    $partlen = floor($listlen / $p);
    $partrem = $listlen % $p;
    $partition = array();
    $mark = 0;
    for($px = 0; $px < $p; $px ++) {
        $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
        $partition[$px] = array_slice($list, $mark, $incr);
        $mark += $incr;
    }
    return $partition;
}

function hashMaker($word) {
    $return = hash('md5',$word);
    return $return;
}
