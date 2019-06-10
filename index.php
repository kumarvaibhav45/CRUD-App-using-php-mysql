<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Kumar Vaibhav--Auto Database</title>
    <link rel="stylesheet" href="css\bootstrap.min.css">
    <style>
        .container {
            background-color: wheat;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <?php
        if (!isset($_SESSION['name']) || strlen($_SESSION['name']) < 1) { ?>
            <p>
                <a href="login.php">Please Log In</a>
            </p>
          <?php } else { ?>
            <h5>You are logged in.</h5>
            <p>
                Go to
                <a href="view.php">View</a><br>
                Go to
                <a href="add.php">Add</a>
            </p>
        <?php } ?>
    </div>
</body>