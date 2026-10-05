<!-- viewTeams.php -->
<?php include 'isUser.php'; ?>

<?php
session_start();
require_once("db_connect.php");


$user_id = $_SESSION["user_id"];


$stmt = $connection->prepare(
    "SELECT team.team_id as team_id, team.name as team_name, access_type.name as access_type
    from user_in_team
    join team using(team_id)
    join access_type using(access_type_id)
    where user_id = ?
    "
);

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    return;
}
$i = 3;
while ($row = $result->fetch_assoc()) {
    $team_id = $row["team_id"];
    $team_name = $row["team_name"];
    $access_type = $row["access_type"]; ?>

    <div tabindex="<?= $i++ ?>" class="task" onkeypress="(event.key === 'Enter') && document.getElementById('<?= $team_id ?>').submit();" onclick="document.getElementById('<?= $team_id ?>').submit();">
        <div class="task-info">
            <div class="task-name"><?= $team_name ?></div>
            <div class="task-category">access:&nbsp;<b><?= $access_type ?></b></div>
        </div>
        <form method="get" id="<?= $team_id ?>" name="<?= $team_id ?>" action="showSelectedTeam.php">
            <input type="hidden" name="team_id" value="<?= $team_id ?>">
        </form>
    </div>

<?php
}
session_write_close();
?>