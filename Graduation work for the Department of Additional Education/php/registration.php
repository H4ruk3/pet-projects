<!-- registration.php -->
<?php
session_start();
require_once("db_connect.php");

if (isset($_SESSION['login'])) {
    header('Location: ../TeamsPage.php');
    session_write_close();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $email = $_POST['login'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $repeat_password = $_POST['rePassword'];

    $checkQuery = "SELECT * FROM user WHERE login = ?";
    $stmt = mysqli_prepare($connection, $checkQuery);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<p>Email already in use</p>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashedRepeatPassword = password_hash($repeat_password, PASSWORD_DEFAULT);
        if (!strcmp($hashedPassword, $hashedRepeatPassword)) {
            echo "<p>password and repeated password don't match</p>";
            return;
        }
        $insertQuery = "INSERT INTO user (login, name, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connection, $insertQuery);

        mysqli_stmt_bind_param($stmt, "sss", $email, $name, $hashedPassword);
        mysqli_stmt_execute($stmt);

        $userId = mysqli_insert_id($connection);

        $_SESSION['user_id'] = $userId;
        $_SESSION['logged_in'] = true;
        $_SESSION['user_name'] = $name;
        header('Location: ../TeamsPage.php');
        session_write_close();
        exit();
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($connection);
session_write_close();
