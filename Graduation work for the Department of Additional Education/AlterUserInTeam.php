<!-- alterUserInTeam.php -->
<?php include 'php/isUser.php'; ?>
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alter_user_in_team'])) {

    $team_id = $_POST['team_id'];
    $query = "Select * from access_type where name != 'OWNER'";
    $result = $connection->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ${$row['name']} = $row['access_type_id'];
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alter_user_button_click'])) {
    $team_id = $_POST['team_id'];
    $access_type_id = $_POST['access_type'];
    if ($access_type_id == 1) {
        $access_type_id = 4;
    }
    $user_in_team_id = $_POST['user_id'];
    $owner_id = $_SESSION['user_id'];
    $stmt = $connection->prepare(
        "SELECT * from user_in_team where user_id = ? and team_id = ? and access_type_id = 1"
    );
    $stmt->bind_param("ii", $owner_id, $team_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows == 0) {
        header("Location: showSelectedTeam.php?team_id=" . $team_id);
        return;
    }
    $stmt = $connection->prepare(
        "UPDATE user_in_team 
        SET access_type_id = ? 
        WHERE user_id = ? and team_id = ? and access_type_id != 1"
    );
    $stmt->bind_param("iii", $access_type_id, $user_in_team_id, $team_id);
    $stmt->execute();
    $stmt->close();
    session_write_close();
    header("Location: showSelectedTeam.php?team_id=" . $team_id);
    return;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_user_button_click'])) {
    $team_id = $_POST['team_id'];
    $user_in_team_id = $_POST['user_id'];
    $owner_id = $_SESSION['user_id'];
    $stmt = $connection->prepare(
        "SELECT * from user_in_team where user_id = ? and team_id = ? and access_type_id = 1"
    );
    $stmt->bind_param("ii", $owner_id, $team_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows == 0) {
        header("Location: showSelectedTeam.php?team_id=" . $team_id);
        return;
    }
    $stmt = $connection->prepare(
        "DELETE FROM user_in_team 
        where user_id = ? and team_id = ? and access_type_id != 1"
    );
    $stmt->bind_param("ii", $user_in_team_id, $team_id);
    $stmt->execute();
    $stmt->close();
    session_write_close();
    header("Location: showSelectedTeam.php?team_id=" . $team_id);
    return;
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
        <form class="form_container" action="AlterUserInTeam.php" method="post">
            <select style="margin-top: 80px;" name="user_id" id="" required>
                <option value="" selected disabled hidden>SELECT USER</option>
                <?php
                session_start();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $team_id = $_POST['team_id'];
                }
                $stmt = $connection->prepare(
                    "SELECT user.user_id, user.login, user.name
                    FROM user
                    JOIN user_in_team using(user_id)
                    WHERE user_in_team.team_id = ? and user_in_team.access_type_id != 1"
                );
                $stmt->bind_param("i", $team_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['user_id'] . '">' . $row['login'] . ' - ' . $row['name'] . '</option>';
                    }
                }
                ?>
            </select>
            <input type="hidden" name="team_id" id="" value="<?= $team_id ?>">
            <select name="access_type" id="">
                <option style="margin-top: 20px;" value="<?= $DEVELOPER ?>">DEVELOPER</option>
                <option value="<?= $VIEWER ?>">VIEWER</option>
                <option selected value="<?= $GUEST ?>">GUEST</option>
            </select>

            <button name="alter_user_button_click" style="margin-top: 20px;">Alter User</button>
            <button name="remove_user_button_click" style="margin-top: 20px;">Remove User</button>
        </form>
    </main>
</body>

</html>