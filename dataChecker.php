
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//TODO
$filename = './wordsToCheck.txt';

if(file_exists($filename)){
    echo "Datei existiert";
} else {
    echo "Datei existiert nicht";
}