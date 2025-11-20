<?php
    use League\Csv\Reader;
    require_once "vendor/autoload.php";

    if ($_POST) {
        $result = "";
        $vocali = ['a', 'e', 'i', 'o', 'u'];
        $cognome = strtolower($_POST["cognome"]);
        $nome = strtolower($_POST["nome"]);
        $data = $_POST["data"];
        $anno = substr($_POST["data"], 2, 2);
        $mese = str_replace("0", "", substr($_POST["data"], 5, 1)) . substr($_POST["data"], 6, 1);
        $giorno = substr($_POST["data"], 8, 2);
        $sesso = $_POST["sesso"];
        $luogo = $_POST["luogo-nascita"];
        $provincia = $_POST["provincia"];
        $car_alfa_disp = array(
            '0' => 1, '1' => 0, '2' => 5, '3' => 7, '4' => 9, '5' => 13, '6' => 15, '7' => 17, '8' => 19, '9' => 21, 'A' => 1, 'B' => 0, 'C' => 5, 'D' => 7, 'E' => 9, 'F' => 13, 'G' => 15, 'H' => 17, 'I' => 19, 'J' => 21, 'K' => 2, 'L' => 4, 'M' => 18, 'N' => 20, 'O' => 11, 'P' => 3, 'Q' => 6, 'R' => 8, 'S' => 12, 'T' => 14, 'U' => 16, 'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24, 'Z' => 23);
        $car_alfa_pari = array(
            '0' => 0, 'C' => 2, 'O' => 14, '1' => 1, 'D' => 3, 'P' => 15, '2' => 2, 'E' => 4, 'Q' => 16, '3' => 3, 'F' => 5, 'R' => 17, '4' => 4, 'G' => 6, 'S' => 18, '5' => 5, 'H' => 7, 'T' => 19, '6' => 6, 'I' => 8, 'U' => 20, '7' => 7, 'J' => 9, 'V' => 21, '8' => 8, 'K' => 10, 'W' => 22, '9' => 9, 'L' => 11, 'X' => 23, 'A' => 0, 'M' => 12, 'Y' => 24, 'B' => 1, 'N' => 13, 'Z' => 25);
        $car_resto = array(
            0 => 'A', 10 => 'K', 20 => 'U', 1 => 'B', 11 => 'L', 21 => 'V', 2 => 'C', 12 => 'M', 22 => 'W', 3 => 'D', 13 => 'N', 23 => 'X', 4 => 'E', 14 => 'O', 24 => 'Y', 5 => 'F', 15 => 'P', 25 => 'Z', 6 => 'G', 16 => 'Q', 7 => 'H', 17 => 'R', 8 => 'I', 18 => 'S', 9 => 'J', 19 => 'T');


        //-----------------------  Cognome  ------------------------//
        $flag = false;
        foreach (str_split($cognome) as $letter) {
            foreach ($vocali as $vocale) if ($letter == $vocale) $flag = true;
            if (!$flag && strlen($result) < 3) $result .= strtoupper($letter);
            else $flag = false;
        }
        if (strlen($result) < 3) {
            foreach (str_split($cognome) as $letter) {
                foreach ($vocali as $vocale) {
                    if ($letter == $vocale) $result .= strtoupper($vocale);
                    if (strlen($result) == 3) break 2;
                }
            }
        }


        //-----------------------  Nome  ------------------------//
        $flag = false;
        $counter = "";
        foreach (str_split($nome) as $letter) {
            foreach ($vocali as $vocale) if ($letter == $vocale) $flag = true;
            if (!$flag && strlen($result) < 6) {
                $counter .= $letter;
            } else $flag = false;
        }
        if (strlen($counter) < 3) {
            $result .= strtoupper($counter);
            foreach (str_split($nome) as $letter) {
                foreach ($vocali as $vocale) {
                    if ($letter == $vocale) $result .= strtoupper($vocale);
                    if (strlen($result) == 6) break 2;
                }
            }
        }
        if (strlen($counter) == 3) {
            $result .= strtoupper($counter);
        }
        if (strlen($counter) > 3) {
            $result .= strtoupper($counter[0]);
            $result .= strtoupper($counter[2]);
            $result .= strtoupper($counter[3]);
        }


        //-----------------------  Anno di nascita  ------------------------//
        $result .= $anno;


        //-----------------------  Mese di nascita  ------------------------//
        $counter = ["A", "B", "C", "D", "E", "H", "L", "M", "P", "R", "S", "T"];
        $result .= $counter[$mese - 1];


        //-----------------------  Giorno di nascita  ------------------------//
        if ($sesso == "f") $giorno += 40;
        $result .= $giorno;


        //-----------------------  Comune di nascita  ------------------------//
        $csv = Reader::createFromPath("CSV/comuni.csv", 'r');
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(';');
        foreach ($csv->getRecords() as $record)
            if ($record["Denominazione in italiano"] == $luogo && $record["Sigla automobilistica"] == strtoupper($provincia))
                $result .= $record["Codice Catastale del comune"];


        //-----------------------  Lettera di controllo  ------------------------//
        $totale = 0;
        foreach (str_split($result) as $key => $letter) {
            if ($key % 2 == 0) $totale += $car_alfa_disp[$letter];
            else $totale += $car_alfa_pari[$letter];
        }
    }
    if(isset($result)) $result .= $car_resto[$totale %= 26];