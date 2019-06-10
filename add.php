<?php
session_start();
if (isset($_POST['cancel'])) {
    // Redirect the browser to view.php
    header("Location: view.php");
    return;
}

require_once "pdo.php";

if (isset($_POST['addNew']) && isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    if (strlen($_POST['make']) < 1) {
        $_SESSION['error'] = "Make must not be empty!";
        header("Location: add.php");
        return;
    } elseif (strlen($_POST['make']) < 1) {
        $_SESSION['error'] = "Model must not be empty!";
        header("Location: add.php");
        return;
    } elseif (!(is_numeric($_POST['year'])) || !(is_numeric($_POST['mileage']))) {
        $_SESSION['error'] = "Mileage and Year must be numeric!";
        header("Location: add.php");
        return;
    } else {
        try {
            $sql = "INSERT INTO autos (make, model, YEAR, mileage) VALUES (:make, :model, :year, :mileage)";
            $stmt = $pdo->prepare($sql);
        } catch (Exception $ex) {
            echo ("Internal Error. Please Contact Support");
            error_log("autos.php, SQL Error:" . $ex->getMessage());
            return;
        }
        $stmt->execute(array(
            ':make' => $_POST['make'],
            ':model' => $_POST['model'],
            ':year' => $_POST['year'],
            ':mileage' => $_POST['mileage']
            
        ));
        $_SESSION['success'] = "Record Inserted";
        header("Location: view.php");
        return;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Kumar Vaibhav--Auto Database</title>
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
            ?>
            <h3>Add a new Automobile</h3>
            <?php
            if (isset($_SESSION['error'])) {
                echo ('<small class="text-danger">' . htmlentities($_SESSION['error']) . "</small>\n");
                unset($_SESSION['error']);
            }
            ?>
            <form method="post" class="mb-5 pb-5">
                <div class="form-group">
                    <label for="make">Make</label>
                    <input class="form-control" type="text" name="make"><br />
                </div>
                <div class="form-group">
                    <label for="model">Model</label>
                    <input class="form-control" type="text" name="model"><br />
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input class="form-control" type="text" name="year"><br />
                </div>
                <div class="form-group">
                    <label for="mileage">Mileage</label>
                    <input class="form-control" type="text" name="mileage"><br />
                </div>
                <input class="btn btn-primary" type="submit" name="addNew" value="Add New">
                <input class="btn btn-secondary" type="submit" name="cancel" value="Cancel">
            </form>
        <?php } ?>
    </div>
</body>

</html>