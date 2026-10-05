<!-- login.php -->
<?php
session_start();
require_once("db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['go_in'])) {
    $email = $_POST['login'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE login = ?";
    $stmt = mysqli_prepare($connection, $query);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name = $row["name"];
        if (password_verify($password, $row['password'])) {
            $userId = $row['user_id'];
            $_SESSION['user_id'] = $userId;
            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = $name;
            session_write_close();
            header('Location: ../TeamsPage.php');
            exit();
        } else {
            echo "<p>incorrect password or login</p>";
        }
    } else {
        echo "<p>incorrect password or login</p>";
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($connection);
session_write_close();

?>