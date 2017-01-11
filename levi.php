<?php
ini_set('memory_limit', '-1');

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

levenshteins($wordsArray, json_decode($_GET['word']));

function levenshteins($wordsArray, $words) {
// eingegebenes falsch geschriebenes Wort
    foreach ($words as $word){
        
    
    
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
}


?>