<?php

$wordsArray = array();
$handle = fopen("words.txt", "r");
$i = 1;
$ai = 0;
ini_set('memory_limit', '-1');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        while ($i < 1000) {
            $ai *= $i;
            $i++;
        }
        array_push($wordsArray, $line);
    }
    fclose($handle);
} else {
    // error opening the file.
}
$servers = countServer();
echo("Anzahl Server: " . $servers);
if ($servers == 1) {
    levenshteins($wordsArray, $_POST['word']);
} else {
    $count = count($wordsArray);
     printr(partition($wordsArray, $servers));
    
}

function levenshteins($wordsArray, $word) {
// eingegebenes falsch geschriebenes Wort
    $input = $word;
// Wörterarray als Vergleichsquelle
    $words = $wordsArray;

// noch keine kürzeste Distanz gefunden
    $shortest = -1;

// durch die Wortliste gehen, um das ähnlichste Wort zu finden
    foreach ($words as $word) {

        // berechne die Distanz zwischen Inputwort und aktuellem Wort
        $lev = levenshtein($input, $word);

        // auf einen exakten Treffer prüfen
        if ($lev == 0) {

            // das nächste Wort ist das Wort selbst (exakter Treffer)
            $closest = $word;
            $shortest = 0;

            // Schleife beenden, da wir einen exakten Treffer gefunden haben
            break;
        }

        // Wenn die Distanz kleiner ist als die nächste gefundene kleinste Distanz
        // ODER wenn ein nächstkleineres Wort noch nicht gefunden wurde
        if ($lev <= $shortest || $shortest < 0) {
            // setze den nächstliegenden Treffer und die kürzestes Distanz
            $closest = $word;
            $shortest = $lev;
        }
    }

    echo "Eingegebenes Wort: $input\n";
    if ($shortest == 0) {
        echo "Exakter Treffer gefunden: $closest\n";
    } else {
        echo "Meinten Sie: $closest?\n";
    }
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
?>