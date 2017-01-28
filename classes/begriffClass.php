<?php

class begriffClass {

    public $index = "";
    public $ip = "";
    public $begriff = "";
    public $inhalt = "";

    function __construct($index, $begriff) {
        $this->index = $index;
        $this->begriff = $begriff;
    }

    function __destruct() {
    }
    
    
    
    public function test() {
        echo $this->username;
        echo $this->message;
        echo $this->timestamp;
    }
    
    public function addIp($ip){
        $this->ip=$ip;
    }
    
    public function addInhalt($inhalt){
        $this->inhalt=$inhalt;
    }

}
