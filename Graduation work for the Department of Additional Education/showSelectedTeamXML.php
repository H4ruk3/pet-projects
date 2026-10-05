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
        
        <section id="tasks" class="scrollable_content">

        </section>
        <script>
            class Task {
                constructor(id, taskName, userName) {
                    this.id = id;
                    this.taskName = taskName;
                    this.userName = userName;
                }
            }
            const scrollableContent = document.getElementById('tasks');
            let offset = 0;
            const limit = 5;
            let loading = false;
            let team_id = <?= $team_id ?>;
            let tabindex = 3;
            let data;
            let task;
            function loadTasks() {
                if (loading) return;
                loading = true;

                const xhr = new XMLHttpRequest();
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Парсим JSON-ответ
                        data = JSON.parse(xhr.responseText);
                        console.log(data); // Логируем данные в консоль
                    } else {
                        console.error('Ошибка:', xhr.status);
                    }
                    let newHTML = '';
                    for (let i = 0; i < data.length; i++) {
                        task = new Task(data[i].team_task_id, data[i].task_name, data[i].user_name);
                        if (<?= $access_type_id ?> == 4) {
                            newHTML +=`<div class="task">
                                <div class="task-info">
                                    <div class="task-name">${task.taskName}</div>
                                    <div class="task-category">For:&nbsp;${task.userName}</div>
                                </div>
                            </div>`
                        } else {
                            newHTML += `<div tabindex="${tabindex++}" class="task" onkeypress="(event.key === 'Enter') && 
                            document.getElementById('${ task.id }').submit();" onclick="document.getElementById('${ task.id }').submit();">
                            <div class="task-info">
                                <div class="task-name">${task.taskName}</div>
                                <div class="task-category">For:&nbsp;${task.userName}</div>
                            </div>
                            <div class="task-checkbox">
                                <form class="task_container" method="post" id="${ task.id }" name="${ task.id }" action="showSelectedTeamTask.php">
                                    <input type="hidden" name="task_id" value="${ task.id }">
                                </form>
                            </div>
                        </div>`;
                        }

                    }
                    scrollableContent.innerHTML += newHTML;

                    loading = false;
                };
                xhr.open("GET", `php/loadTasks.php?team_id=${team_id}&offset=${offset}&limit=${limit}`, true);
                offset += limit;

                xhr.send();
            }

            scrollableContent.addEventListener('scroll', () => {
                if (scrollableContent.scrollTop + scrollableContent.clientHeight >= scrollableContent.scrollHeight) {
                    loadTasks();
                }
            });
            loadTasks(); // Начальная загрузка
        </script>
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