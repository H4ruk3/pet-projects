<?php
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'jira';

$connection = mysqli_connect($hostname, $username, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (mysqli_connect_errno()) {
    die('Connection error '.mysqli_connect_error());
}   
?>