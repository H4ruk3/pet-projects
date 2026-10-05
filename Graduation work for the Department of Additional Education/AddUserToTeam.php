<!-- addUserToTeam.php -->
<?php include 'php/isUser.php'; ?>
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user_to_team'])) { // просто переход на страницу добавления юзера в команду

    $team_id = $_POST['team_id'];
    $query = "Select * from access_type where name != 'OWNER'";
    $result = $connection->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ${$row['name']} = $row['access_type_id'];
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user_button_click'])) { // добавить юзера в команду
    $team_id = $_POST['team_id'];
    $access_type_id = $_POST['access_type'];
    $user_login = $_POST['user_login'];
    $stmt = $connection->prepare("Select user_id from user where login = ?");
    $stmt->bind_param("s", $user_login);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user_id = $result->fetch_assoc()['user_id'];

        $stmt = $connection->prepare("INSERT INTO user_in_team (team_id, user_id, access_type_id) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $team_id, $user_id, $access_type_id);
        $stmt->execute();
        $stmt->close();
        session_write_close();
        header("Location: showSelectedTeam.php?team_id=" . $team_id);
        return;
    } ?>
    <script>
        alert("USER NOT FOUND");
    </script>
<?php
}
session_write_close();
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
        <form class="form_container" action="addUserToTeam.php" method="post">
            <input style="margin-top: 80px;" type="email" name="user_login" id="" placeholder="user login..." required>
            <input type="hidden" name="team_id" id="" value="<?= $team_id ?>">
            <select name="access_type" id="">
                <option style="margin-top: 20px;" value="<?= $DEVELOPER ?>">DEVELOPER</option>
                <option value="<?= $VIEWER ?>">VIEWER</option>
                <option value="<?= $GUEST ?>">GUEST</option>
            </select>
            <button name="add_user_button_click" style="margin-top: 20px;">ADD User</button>
        </form>
    </main>
</body>

</html>