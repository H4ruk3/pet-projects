<!-- showSelectedTeam.php -->
<?php include 'php/isUser.php';
session_start();
require_once("php/db_connect.php");
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $team_id = $_GET['team_id'];
    $_SESSION['team_id'] = $team_id;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_id = $_POST['team_id'];
    $_SESSION['team_id'] = $team_id;
}
$team_id = $_SESSION['team_id'];
$user_id = $_SESSION['user_id'];
$stmt = $connection->prepare(
    "Select access_type_id from user_in_team where team_id = ? and user_id = ?"
);
$stmt->bind_param("ii", $team_id, $user_id);
$stmt->execute();
$result1 = $stmt->get_result();
$row1 = $result1->fetch_assoc();
$access_type_id = $row1['access_type_id'];
?>
<html class="html_for_main" lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/input.css">
    <link rel="stylesheet" href="css/button.css">
    <title>Main</title>
</head>

<body>
    <header>
        <nav class="logo">
            <img width="50px" src="assets/logo_icon.svg" alt="Dropdown Button">
            <span><a tabindex="1" href="TeamsPage.php">JI.RU</a></span>
        </nav>
        <div class="dropdown">
            <img width="45px" src="assets/user_icon.svg" alt="Dropdown Button">
            <div class="dropdown-content dropdown-content-user">
                <a href="#">Settings</a>
                <a href="index.php">Logout</a>
            </div>
        </div>
    </header>
    <main class="container">
        <input tabindex="2" style="margin-top: 30px;" type="search" name="" id="" placeholder="SEARCH...">
        <hr>

        <section class="scrollable_content">
            <?php


            $stmt = $connection->prepare(
                "SELECT '1' as is_user_in_team
                FROM user_in_team
                WHERE user_id = ? AND team_id = ?
                "
            );

            $stmt->bind_param("ii", $user_id, $team_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                header('Location: TeamsPage.php');
                exit();
            }

            $stmt = $connection->prepare(
                "SELECT team_task_id, team_task.name as task_name, user.name as user_name
                    FROM team_task
                    JOIN user using(user_id)
                    WHERE team_id = ? AND (completed = 0 or completed is null);
                    "
            );

            $stmt->bind_param("i", $team_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($access_type_id == 4) {
                while ($row = $result->fetch_assoc()) {
                    $task_id = $row['team_task_id'];
                    $task_name = $row['task_name'];
                    $user_name = $row['user_name'];
            ?>

                    <div class="task">
                        <div class="task-info">
                            <div class="task-name"><?= $task_name ?></div>
                            <div class="task-category">For:&nbsp;<?= $user_name ?></div>
                        </div>
                        <div class="task-checkbox">
                        </div>

                    </div>

                <?php
                }
            } else {
                $i=3;
                while ($row = $result->fetch_assoc()) {
                    $task_id = $row['team_task_id'];
                    $task_name = $row['task_name'];
                    $user_name = $row['user_name'];
                ?>

                    <div tabindex="<?= $i++ ?>" class="task" onkeypress="(event.key === 'Enter') && document.getElementById('<?= $task_id ?>').submit();" onclick="document.getElementById('<?= $task_id ?>').submit();">
                        <div class="task-info">
                            <div class="task-name"><?= $task_name ?></div>
                            <div class="task-category">For:&nbsp;<?= $user_name ?></div>
                        </div>
                        <div class="task-checkbox">
                            <form class="task_container" method="post" id="<?= $task_id ?>" name="<?= $task_id ?>" action="showSelectedTeamTask.php">
                                <!-- <input type="checkbox" name="finished" placeholder="complete" onclick="event.stopPropagation();" > -->
                                <input type="hidden" name="task_id" value="<?= $task_id ?>">
                            </form>
                        </div>

                    </div>

            <?php
                }
            }
            session_write_close();
            ?>


        </section>
        <?php

        $stmt->close();
        if ($access_type_id == 1) {
        ?>
            <section class="three_button_section">
                <form action="AlterUserInTeam.php" method="post">
                    <button id="alter_user_in_team" name="alter_user_in_team" style="margin-right: 20px; ">Alter user</button>
                    <input type="hidden" name="team_id" value="<?= $team_id ?>">
                </form>
                <form action="AddUserToTeam.php" method="post">
                    <button id="add_user_to_team" name="add_user_to_team" style="margin-right: 20px; ">Add user</button>
                    <input type="hidden" name="team_id" value="<?= $team_id ?>">
                </form>
                <form style="margin-right: 20px;" action="AddTeamTask.php" method="post">
                    <button id="add_team_task" name="add_team_task">Add Task</button>
                    <input type="hidden" name="team_id" value="<?= $team_id ?>">
                </form>
            </section>
            <script>
                document.getElementById('add_user_to_team').addEventListener('click', function() {
                    window.location.href = 'addUserToTeam.php';
                });
                document.getElementById('add_team_task').addEventListener('click', function() {
                    window.location.href = 'AddTeamTask.html';
                });
            </script>
        <?php
        }
        ?>
    </main>
</body>

</html>