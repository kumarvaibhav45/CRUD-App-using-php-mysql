<?php
session_start();
if (isset($_POST['cancel'])) {
    // Redirect the browser to view.php
    header("Location: view.php");
    return;
}


require_once "pdo.php";
$id = 0;
if (isset($_REQUEST['autos_id']) && strlen($_REQUEST['autos_id']) > 0) {
    $id = htmlentities($_REQUEST['autos_id']);
}

if (isset($_POST['delete']) && isset($id)) {
    try {
        $sql = "DELETE FROM autos WHERE autos_id = :autos_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':autos_id' => $id));
        $_SESSION['deleted'] = "Record Deleted";
        header("Location: view.php");
        return;
    } catch (Exception $ex) {
        echo ("Internal Error. Please Contact Support");
        error_log("autos.php, SQL Error:" . $ex->getMessage());
        return;
    }
}

try {
    $stmt = $pdo->prepare("SELECT make, model FROM autos WHERE autos_id = :id");
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $ex) {
    echo ($ex);
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
            die('<p class="p-5">You are not logged in. Please <a href="login.php">Log In</a> to continue.</p>');
        } else {
            echo '<h1 class="pt-3">Deleting Autos for ';
            echo htmlentities($_SESSION['name']);
            echo "</h1>\n";
            if (isset($result['make']) && strlen($result['make']) > 0) {
                ?>
                <form method="post" class="py-5 pl-5">
                    <p>Are you sure you want to delete <strong><?php echo ($result['make'] . " " . $result['model']) ?></strong> ?</p>
                    <input class="btn btn-danger" type="submit" name="delete" value="Delete">
                    <input class="btn btn-secondary" type="submit" name="cancel" value="Cancel">
                </form>
            <?php } else { ?>
                <h2 class="text-danger py-5 pl-5">Record not found!</h2>
            <?php }
    } ?>
    </div>
</body>

</html>