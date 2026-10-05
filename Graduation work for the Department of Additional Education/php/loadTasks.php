<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
require_once("db_connect.php");
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$team_id = $_GET['team_id'];
$offset = $_GET['offset'];
$limit = $_GET['limit'];
class Task {
    public $team_task_id;
    public $task_name;
    public $user_name;

    public function __construct($team_task_id, $task_name, $user_name) {
        $this->team_task_id = $team_task_id;
        $this->task_name = $task_name;
        $this->user_name = $user_name;
    }
}
$stmt = $connection->prepare(
    "SELECT team_task_id, team_task.name as task_name, user.name as user_name
                    FROM team_task
                    JOIN user using(user_id)
                    WHERE team_id = ? AND (completed = 0 or completed is null)
                    LIMIT ? OFFSET ?;
                    "
);
$stmt->bind_param("iii", $team_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = new Task( $row['team_task_id'], $row['task_name'], $row['user_name']);
}
echo json_encode($tasks);
?>