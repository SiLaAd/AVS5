<?php

require_once 'HTTP/Request2.php';
include './helperFunctions.php';
$request = new HTTP_Request2('http://172.22.0.9/avs4/registerServer.php', HTTP_Request2::METHOD_GET, array('use_brackets' => true));

$url = $request->getUrl();
$url->setQueryVariables(array(
    'name' => gethostname(),
    'password' => 'AVS'
));
// This will output a page with open bugs for Net_URL2 and HTTP_Request2
$bodyA = $request->send()->getBody();

if ($bodyA == "Server ist schon vorhanden.") {
    echo $bodyA;
} else {
    echo "erfolgreich registriert";
    $serverlist = json_decode($bodyA, true);
}
