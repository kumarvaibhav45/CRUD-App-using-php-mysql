<?php

if (isset($_POST['cancel'])) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}
session_start();
$salt = 'XyZzy12*_';
$stored_hash = 'a8609e8d62c043243c4e201cbb342862'; //password: meow123

// Check to see if we have some POST data, if we do process it
if (isset($_POST['who']) && isset($_POST['pass'])) {
    unset($_SESSION['who']);
    if (strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        return;
    } elseif (!preg_match("/@/", $_POST['who'])) {
        $_SESSION['error'] = "Email format invalid. Must contain '@'";
        header("Location: login.php");
        return;
    } else {
        $check = hash('md5', $salt . $_POST['pass']);
        if ($check == $stored_hash) {
            $_SESSION['name'] = $_POST['who'];
            $_SESSION['success'] = "Logged In";
            error_log("Login success " . $_POST['who']);

            // Redirect the browser to view.php
            header("Location: view.php");
            return;
        } else {
            error_log("Login fail " . $_POST['who'] . " $check");
            $_SESSION['error'] = "Incorrect Password";
            header("Location: login.php");
            return;
        }
    }
}
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
        if (isset($_SESSION['name']) && strlen($_SESSION['name']) > 0) {
            $_SESSION['success'] = "You are already logged in!";
            header("Location: view.php");
            return;
        } else { ?>
            <h1>Please Log In</h1>
            <?php
            if (isset($_SESSION["error"])) {
                echo ('<small class="text-danger">' . htmlentities($_SESSION['error']) . "</small>\n");
                unset($_SESSION['error']);
            }
            ?>
            <form method="POST" class="py-5">
                <div class="form-group">
                    <label for="who">Email</label>
                    <input class="form-control" type="text" name="who" id="who"><br />
                </div>
                <div class="form-group">
                    <label for="id_1723">Password</label>
                    <input class="form-control" type="text" name="pass" id="id_1723"><br />
                </div>

                <input class="btn btn-primary" type="submit" name="login" value="Log In">
                <input class="btn btn-danger" type="submit" name="cancel" value="Cancel">
            </form>
            <p>
            </p>
        <?php } ?>
    </div>
</body>