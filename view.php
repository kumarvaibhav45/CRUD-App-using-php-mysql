<?php
session_start();
require_once "pdo.php";

try {
    $stmt = $pdo->query("SELECT `autos_id`, `make`, `model`, `YEAR`, `mileage` FROM `autos` ORDER BY make");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $ex) {
    echo ("Internal Error. Please Contact Support");
    error_log("autos.php, SQL Error:" . $ex->getMessage());
    return;
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Auto Database</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="css\bootstrap.min.css">
    <style>
        .container {
            background-color: wheat;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php

        if (!isset($_SESSION['name']) || strlen($_SESSION['name']) < 1) {
            die('You are not logged in. Please <a href="login.php">Log In</a> to continue.');
        } else {
            echo '<h1 class="pt-3">Tracking Autos for ';
            echo htmlentities($_SESSION['name']);
            echo "</h1>\n";

            echo ('<h3 class="py-2">Automobiles</h3>');

            if (isset($_SESSION['success'])) {
                echo ('<small class="text-success">' . htmlentities($_SESSION['success']) . "</small>\n");
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['deleted'])) {
                echo ('<small class="text-danger">' . htmlentities($_SESSION['deleted']) . "</small>\n");
                unset($_SESSION['deleted']);
            }
            if (!isset($rows[0]['make']) || strlen($rows[0]['make']) < 1) {
                echo ('No Automobile Data found...');
            } else {
                echo '<table class="table">';
                echo '<thead><tr><th scope="col">Make</th><th scope="col">Model</th><th scope="col">Year</th><th scope="col">Mileage</th><th scope="col">Edit/Delete</th></tr></thead>';
                foreach ($rows as $row) {
                    echo '<tr><td>';
                    echo (htmlentities($row['make']));
                    echo '</td><td>';
                    echo (htmlentities($row['model']));
                    echo '</td><td>';
                    echo (htmlentities($row['YEAR']));
                    echo '</td><td>';
                    echo (htmlentities($row['mileage']));
                    echo '</td><td><a href="edit.php?autos_id=' . $row['autos_id'] . '">Edit</a> / <a href="delete.php?autos_id=' . $row['autos_id'] . '">Delete</a></td></tr>';
                }
                echo '</table>';
            }
            ?>

            <hr>
            <div class="py-4">
                <a href="add.php">Add New Automobile</a><br><br> <a href="logout.php">Log Out</a>
            </div>

        <?php } ?>
    </div>

</body>

</html>