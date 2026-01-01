<?php
use League\Csv\Reader;
require_once "vendor/autoload.php";

/* ------------------------- Utility Functions ------------------------- */

function extractCharacters(string $input, array $vowels): array
{
    $consonants = str_replace($vowels, "", $input);
    $vowelsOnly = str_replace(str_split($consonants), "", $input);
    return [$consonants, $vowelsOnly];
}

function formatSurname(string $surname, array $vowels): string
{
    [$cons, $vow] = extractCharacters($surname, $vowels);

    if (strlen($cons) >= 3) return substr($cons, 0, 3);
    if (strlen($cons) == 2) return $cons . ($vow[0] ?? 'X');
    if (strlen($cons) == 1) return $cons . substr($vow . "X", 0, 2);
    return substr($vow . "XXX", 0, 3);
}

function formatName(string $name, array $vowels): string
{
    [$cons, $vow] = extractCharacters($name, $vowels);

    if (strlen($cons) > 3) return $cons[0] . $cons[2] . $cons[3];
    if (strlen($cons) == 3) return $cons;
    if (strlen($cons) == 2) return $cons . ($vow[0] ?? 'X');
    if (strlen($cons) == 1) return $cons . substr($vow . "X", 0, 2);
    return substr($vow . "XXX", 0, 3);
}

function formatDate(string $date, string $gender): string
{
    $dt = new DateTime($date);
    $months = ["A", "B", "C", "D", "E", "H", "L", "M", "P", "R", "S", "T"];

    $year = $dt->format("y");
    $monthLetter = $months[(int)$dt->format("n") - 1];
    $day = (int)$dt->format("d");

    if ($gender === "F")
        $day += 40;

    return $year . $monthLetter . str_pad($day, 2, "0", STR_PAD_LEFT);
}

function getCityCode(string $city, string $province): ?string
{
    $csv = Reader::createFromPath($_SERVER['DOCUMENT_ROOT'] . "/CSV/comuni.csv", 'r');
    $csv->setHeaderOffset(0);

    $csv = Reader::createFromPath("CSV/comuni.csv", 'r');
    $csv->setHeaderOffset(0);
    $csv->setDelimiter(';');
        foreach ($csv->getRecords() as $record)
            if ($record["Denominazione in italiano"] == $city && $record["Sigla automobilistica"] == strtoupper($province))
                return $record["Codice Catastale del comune"];

    return null;
}

function calculateControlChar(string $code): string
{
    // Values for characters in odd positions
    $oddValues = [
        '0'=>1, '1'=>0, '2'=>5, '3'=>7, '4'=>9, '5'=>13, '6'=>15, '7'=>17, '8'=>19, '9'=>21,
        'A'=>1, 'B'=>0, 'C'=>5, 'D'=>7, 'E'=>9, 'F'=>13, 'G'=>15, 'H'=>17, 'I'=>19, 'J'=>21,
        'K'=>2, 'L'=>4, 'M'=>18,'N'=>20,'O'=>11,'P'=>3, 'Q'=>6, 'R'=>8, 'S'=>12,'T'=>14,
        'U'=>16,'V'=>10,'W'=>22,'X'=>25,'Y'=>24,'Z'=>23
    ];

    // Values for characters in EVEN positions
    $evenValues = [
        '0'=>0, '1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9,
        'A'=>0, 'B'=>1, 'C'=>2, 'D'=>3, 'E'=>4, 'F'=>5, 'G'=>6, 'H'=>7, 'I'=>8, 'J'=>9,
        'K'=>10,'L'=>11,'M'=>12,'N'=>13,'O'=>14,'P'=>15,'Q'=>16,'R'=>17,'S'=>18,'T'=>19,
        'U'=>20,'V'=>21,'W'=>22,'X'=>23,'Y'=>24,'Z'=>25
    ];

    // Mapping for remainder → control letter
    $controlMap = [
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
    ];

    $sum = 0;
    $chars = str_split($code);

    foreach ($chars as $index => $char) {
        $char = strtoupper($char);

        if ($index % 2 === 0) {
            // Odd position (1st, 3rd, 5th...) → oddValues
            $sum += $oddValues[$char];
        } else {
            // Even position → evenValues
            $sum += $evenValues[$char];
        }
    }

    return $controlMap[$sum % 26];
}


/* ----------------------- Main Computation ----------------------- */

if ($_POST) {
    $vowels = ['a', 'e', 'i', 'o', 'u'];

    $surnameCode = formatSurname(strtolower($_POST['surname']), $vowels);
    $nameCode = formatName(strtolower($_POST['name']), $vowels);
    $dateCode = formatDate($_POST['dateOfBirth'], $_POST['gender']);

    $cityCode = getCityCode($_POST['placeOfBirth'], $_POST['provinceCode']);
    if (!$cityCode) die("City code not found.");

    $partial = $surnameCode . $nameCode . $dateCode . $cityCode;

    $control = calculateControlChar($partial);

    $result = strtoupper($partial . $control);
}
