<?php

//ini_set('memory_limit', '-1');


if (isset($_GET['wordSend'])) {
    levenshteins(json_decode($_GET['wordSend']));
}

function levenshteins($words) {

    $handle = fopen("words.txt", "r");


    $wordsArray = array();
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            array_push($wordsArray, $line);
        }
        fclose($handle);
        echo("test");
    } else {
        // error opening the file.
    }
    foreach ($words as $word) {

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