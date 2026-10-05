<!-- isUser.php -->
<?php
session_start();
require_once("db_connect.php");

if (!isset($_SESSION['user_id'] )) {
    header('Location: ../index.php');
    session_write_close();
    exit;
}
session_write_close();
?>
