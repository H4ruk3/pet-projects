<!-- completeSelectedTeamTask.php -->
<?php
require_once("db_connect.php");
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    session_write_close();
    exit();
}


function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

$user_id = $_SESSION["user_id"];
$task_report = $_POST["task_report"];
$task_id = $_POST["task_id"];
$team_id = $_POST["team_id"];
debug_to_console($team_id);

$stmt = $connection->prepare(
    "
    UPDATE team_task SET report=?, completed=1 WHERE team_task_id = ?
    "
);
$stmt->bind_param("si", $task_report, $task_id);
$stmt->execute();
$stmt->close();
session_write_close();
header("Location: ../showSelectedTeam.php?team_id=" . $team_id);
