<?php
session_start();

if (isset($_POST['cancel'])) {
    // Redirect the browser to view.php
    header("Location: view.php");
    return;
}

require_once "pdo.php";
$id = 0;
if(isset($_REQUEST['autos_id']) && strlen( $_REQUEST['autos_id']) >0) {
    $id = htmlentities($_REQUEST['autos_id']);
}
try {
    $stmt = $pdo->prepare("SELECT * FROM autos WHERE autos_id = :id");
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $ex) {
    echo ("Internal Error. Please Contact Support");
    error_log("autos.php, SQL Error:" . $ex->getMessage());
    return;
}

if (isset($_POST['update']) && isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])) {
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
            $sql = "UPDATE autos SET make= :make, model= :model, YEAR= :year, mileage= :mileage WHERE autos_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':make' => $_POST['make'],
                ':model' => $_POST['model'],
                ':year' => $_POST['year'],
                ':mileage' => $_POST['mileage'],
                ':id' => $id    
            ));
            $_SESSION['success'] = "Record Updated";
            header("Location: view.php");
            return;
        } catch (Exception $ex) {
            echo ($ex);
            echo ("Internal Error. Please Contact Support");
            error_log("autos.php, SQL Error:" . $ex->getMessage());
            return;
        }
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
            die('<p class="p-5">You are not logged in. Please <a href="login.php">Log In</a> to continue.</p>');
        } else {
            echo '<h1 class="pt-3">Editing Autos for ';
            echo htmlentities($_SESSION['name']);
            echo "</h1>\n";
            if (isset($_SESSION['error'])) {
                echo ('<small class="text-danger">' . htmlentities($_SESSION['error']) . "</small>\n");
                unset($_SESSION['error']);
            }
            if (isset($result['make']) && strlen($result['make']) > 0) {
                ?>
                <h6>Edit the fields you want to update.</h6>

                <form method="post" class="mb-5 pb-5">
                    <div class="form-group">
                        <label for="make">Make</label>
                        <input class="form-control" type="text" name="make" value="<?php echo ($result['make']); ?>"><br />
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input class="form-control" type="text" name="model" value="<?php echo ($result['model']); ?>"><br />
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input class="form-control" type="text" name="year" value="<?php echo ($result['YEAR']); ?>"><br />
                    </div>
                    <div class="form-group">
                        <label for="mileage">Mileage</label>
                        <input class="form-control" type="text" name="mileage" value="<?php echo ($result['mileage']); ?>"><br />
                    </div>
                    <input class="btn btn-primary" type="submit" name="update" value="Update">
                    <input class="btn btn-secondary" type="submit" name="cancel" value="Cancel">
                </form>
            <?php } else { ?>
                <h2 class="text-danger py-5">Record not found!</h2>
            <?php }
    } ?>
    </div>
</body>

</html>