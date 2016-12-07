<?php



require_once 'HTTP/Request2.php';
$request = new HTTP_Request2('http://172.22.0.12/AVS3/registerServer.php',
                             HTTP_Request2::METHOD_GET, array('use_brackets' => true));

$url = $request->getUrl();
$url->setQueryVariables(array(
    'name' => 'BOR',
    'password' => 'AVS'
));
// This will output a page with open bugs for Net_URL2 and HTTP_Request2
$bodyA = $request->send()->getBody();


//echo is_string($bodyA);

$serverlist = json_decode($bodyA,true);


foreach ($serverlist as $server){
        
    foreach ($server as $s){
        echo $s;
}
}