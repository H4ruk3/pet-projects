<!-- addTeamTask.php -->
<?php include 'php/isUser.php'; ?>
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_team_task'])) {
    $team_id = $_POST['team_id'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task_button_click'])) {
    $team_id = $_POST['team_id'];
    $user_id = $_POST['task_for_user_id'];
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    if ($start_date != '') {
        $start_date = date('Y-m-d H:i:s', strtotime($start_date));
    } else {
        $start_date = null;
    }
    if ($end_date != '') {
        $end_date = date('Y-m-d H:i:s', strtotime($end_date));
    } else {
        $end_date = null;
    }

    $stmt = $connection->prepare("INSERT INTO team_task (team_id, user_id, name, description, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $team_id, $user_id, $task_name, $task_description, $start_date, $end_date);
    $stmt->execute();
    if (!$stmt->error) {
        $stmt->close();
        session_write_close();
        header("Location: showSelectedTeam.php?team_id=" . $team_id);
        return;
    } else {
?>
        <script>
            alert("<?php echo $stmt->error; ?>");
        </script>
<?php
        echo "Error: " . $stmt->error;
        $stmt->close();
        session_write_close();
    }
}

?>
<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/input.css">
    <link rel="stylesheet" href="css/button.css">
    <title>Add task</title>
</head>

<body>
    <header>
        <nav class="logo">
            <img width="50px" src="assets/logo_icon.svg" alt="Dropdown Button">
            <span><a href="TeamsPage.php">JI.RU</a></span>
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
        <form class="form_container" action="addTeamTask.php" method="post">
            <input type="hidden" name="team_id" id="" value="<?= $team_id ?>">
            <input style="margin-top: 80px;" type="" name="task_name" id="" placeholder="task name..." required>
            <textarea name="task_description" id="" placeholder="task Description..." maxlength="256" required></textarea>
            <!-- <input type="" name="category_name" id="" placeholder="category..."> -->
            <input style="color: #999;" type="datetime-local" name="start_date" id="start_date" placeholder="date of start..." required>
            <input style="color: #999;" type="datetime-local" name="end_date" id="end_date" placeholder="date of end...">
            <select name="task_for_user_id" id="users" required>
                <option value="" disabled selected hidden>CHOOSE USER...</option>
                <script>
                    const selectElement = document.getElementById('users');

                    selectElement.addEventListener('change', function() {
                        if (selectElement.value !== '') {
                            selectElement.style.color = 'black';
                        } else {
                            selectElement.style.color = '#999';
                        }
                    });
                </script>


                <?php
                $query = "SELECT user_id, user.name as name, login
                FROM user_in_team
                join user using(user_id)
                where team_id = ? and (access_type_id = 2 or access_type_id = 1)";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("i", $team_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) { ?>
                    <option style="margin-top: 20px;" value="<?= $row['user_id'] ?>"><?= $row['name'], ': ', $row['login'] ?></option>
                <?php
                }
                ?>
            </select>
            <button name="add_task_button_click" style="margin-top: 20px;">ADD TASK</button>
        </form>
    </main>
</body>

</html>