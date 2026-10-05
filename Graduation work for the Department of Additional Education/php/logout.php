<!-- logout.php -->
<?php
session_start();
$_SESSION['user_id'] = null;
$_SESSION['logged_in'] = false;
unset ($_SESSION['user_id']);
unset ($_SESSION['logged_in']);
session_unset();
session_destroy();
?>