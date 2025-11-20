<?php
    require "php/code.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>

    h3 {
        margin: 30px auto !important;
        width: 500px;
    }

    .container {
        width: 500px;
        margin: 0 auto;
    }

    #generatore-cf label{
        display: flex;
        justify-content: space-between;
    }

    .data_list {
        margin: 0 auto;
        width: 500px;
        padding: 0;
    }

    .data_list li{
        display: flex;
        height: 20px;
        align-items: center;
        justify-content: space-between;
    }

</style>
<body>
    <!------------------------------  Controllo se il numero è primo maggiore di 10  ---------------------------------->
    <h3>Generatore codice fiscale</h3>
    <form action="" method="POST" id="generatore-cf" class="container">
        <label for="cognome">Cognome<input type="text" id="cognome" name="cognome" required></label><br>
        <label for="nome">Nome<input type="text" id="nome" name="nome" required></label><br>
        <label for="sesso">Sesso
            <select name="sesso" id="sesso" required>
                <option value="m"  >Maschio</option>
                <option value="f">Femmina</option>
            </select>
        </label><br>
        <label for="luogo-nascita">Luogo di nascita<input type="text" id="luogo-nascita" name="luogo-nascita" required></label><br>
        <label for="provincia">Sigla provincia<input type="text" id="provincia" name="provincia" required></label><br>
        <label for="data">Data di nascita<input type="date" id="data" name="data" required></label><br>
        <input type="submit" name="submit">
    </form>

    <!------------------------------  PHP  ---------------------------------->
    <?php if (isset($result)): ?>
        <h3><?= $result ?></h3>
        <ul class="container data_list">
            <li><span>Nome:</span> <?= ucfirst($nome) ?></li>
            <li><span>Cognome:</span> <?= ucfirst($cognome) ?></li>
            <li><span>Sesso:</span> <?= ucfirst($sesso) ?></li>
            <li><span>Luogo di nascita:</span> <?= $luogo?></li>
            <li><span>Sigla provincia:</span> <?= $provincia?></li>
            <li><span>Data di nascita:</span> <?= $data?></li>
        </ul>
    <?php endif ; ?>
</body>
</html>