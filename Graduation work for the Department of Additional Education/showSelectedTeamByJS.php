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
    <script>
        const MAX_ELEMENTS_PER_PAGE = 3;
        let elementsCount = MAX_ELEMENTS_PER_PAGE;
        let currentPage = 0;
        const pageSwitch = (buttonID) => {
            max_pages = Math.floor(TaskList.length / MAX_ELEMENTS_PER_PAGE - 0.001)
            switch (buttonID) {
                case 'start':
                    if (currentPage == 0) break;
                    currentPage = 0;
                    elementsCount = MAX_ELEMENTS_PER_PAGE;
                    generateTasks();
                    break;
                case 'prev':
                    if (currentPage > 0) {
                        currentPage--;
                        elementsCount -= MAX_ELEMENTS_PER_PAGE; 
                        if (elementsCount < MAX_ELEMENTS_PER_PAGE) elementsCount = MAX_ELEMENTS_PER_PAGE;
                        elementsCount = elementsCount % MAX_ELEMENTS_PER_PAGE == 0 ? elementsCount : Math.ceil(elementsCount / MAX_ELEMENTS_PER_PAGE) * MAX_ELEMENTS_PER_PAGE;
                        generateTasks();
                    }
                    break;
                case 'next':
                    if (currentPage < max_pages && elementsCount < TaskList.length) {
                        currentPage++;
                        elementsCount =  elementsCount + MAX_ELEMENTS_PER_PAGE > TaskList.length ? TaskList.length : elementsCount + MAX_ELEMENTS_PER_PAGE;
                        generateTasks();
                    }
                    break;
                case 'end':
                    if (currentPage == max_pages) break;
                    currentPage = max_pages;
                    elementsCount = TaskList.length;
                    generateTasks();
                    break;
                default:
                    break;
            }
            console.log(currentPage, elementsCount);

        };
        const generateTasks = () => {
            newHTML = "";
            for (let i = currentPage * MAX_ELEMENTS_PER_PAGE; i < elementsCount && i < TaskList.length; i++) {
                let task = TaskList[i];
                if (<?= $access_type_id ?> == 4) {
                    newHTML += `<div class="task">
                                <div class="task-info">
                                    <div class="task-name">${task.taskName}</div>
                                    <div class="task-category">For:&nbsp;${task.userName}</div>
                                </div>
                                <div class="task-checkbox">
                                </div>
                            </div>`
                } else {
                    newHTML += `
                        <div tabindex="${task.tabindex}" class="task" onkeypress="(event.key === 'Enter') && 
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

            document.getElementById('tasks').innerHTML = newHTML
        };
        let tabIndex = 3;
        class Task {
            constructor(tabindex, id, taskName, userName) {
                this.tabindex = tabindex;
                this.id = id;
                this.taskName = taskName;
                this.userName = userName;
            }

        };
        const TaskList = [];
    </script>
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

            while ($row = $result->fetch_assoc()) {
                $task_id = $row['team_task_id'];
                $task_name = $row['task_name'];
                $user_name = $row['user_name'];

            ?>
                <script>
                    TaskList.push(new Task(tabIndex++, '<?= $task_id ?>', '<?= $task_name ?>', '<?= $user_name ?>'));
                    console.log(TaskList);
                </script>
            <?php
            } ?>
            <script>
                generateTasks();
            </script>
            <div>

            </div>
            <?php
            session_write_close();
            ?>
        </section>
        <div class="navigation-container">
            <div class="navigation-buttons">
                <span id="start">&xlarr;</span>
                <span id="prev">&larr;</span>
            </div>
            <div class="navigation-buttons">
                <span id="next">&rarr;</span>
                <span id="end">&xrarr;</span>
            </div>
        </div>
        <script>
            document.getElementById('start').addEventListener('click', function() {
                pageSwitch('start');
            });
            document.getElementById('prev').addEventListener('click', function() {
                pageSwitch('prev');
            });
            document.getElementById('next').addEventListener('click', function() {
                pageSwitch('next');
            });
            document.getElementById('end').addEventListener('click', function() {
                pageSwitch('end');
            });
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