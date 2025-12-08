<?php
    require "src/TaxCodeGenerator.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="panel">
            <form action="" method="POST" id="generatore-cf">
                <h3>Tax Code Generator</h3>
                <div class="formLine">
                    <label for="surname">
                        Surname
                        <input type="text" id="surname" name="surname" required>
                    </label>
                    <label for="name">
                        Name
                        <input type="text" id="name" name="name" required>
                    </label>
                </div>
                <div class="formLine">
                    <label for="provinceCode">Province Code
                        <input type="text" id="provinceCode" name="provinceCode" required>
                    </label>
                    <label for="placeOfBirth">
                        Place of Birth
                        <input type="text" id="placeOfBirth" name="placeOfBirth" required>
                    </label>
                </div>
                <div class="formLine">
                    <label for="gender">
                        Gender
                        <select name="gender" id="gender" required>
                            <option value="m" >Male</option>
                            <option value="f">Female</option>
                        </select>
                    </label>
                    <label for="dateOfBirth">Date of Birth
                        <input type="date" id="dateOfBirth" name="dateOfBirth" required>
                    </label>
                </div>

                <button class="submitButton" type="submit" name="submit" >
                    <div class="contentButton">
                        Generate
                    </div>
                    <div class="iconButton">
                        <svg width="10" height="7" viewBox="0 0 10 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.5 3.5H9.5M9.5 3.5L6.5 0.5M9.5 3.5L6.5 6.5" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </button>
            </form>

            <!------------------------------  PHP  ---------------------------------->
            <?php if (isset($result)): ?>
            <div class="resultPanel">
                <div class="resultElement"><?= $result ?></div>
                <div class="dataList">
                    <div>
                        <div><span>Name</span> <?= ucfirst($_POST['name']) ?></div>
                        <div><span>Surname</span> <?= ucfirst($_POST['surname']) ?></div>
                        <div><span>Gender</span> <?= ucfirst($_POST['gender']) ?></div>
                    </div>
                    <div>
                        <div><span>Place of Birth</span> <?= $_POST['placeOfBirth']?></div>
                        <div><span>Province Code</span> <?= $_POST['provinceCode']?></div>
                        <div><span>Date of Birth</span> <?= $_POST['dateOfBirth']?></div>
                    </div>
                </div>
            </div>
            <?php endif ; ?>
        </div>
    </div>
</body>
</html>