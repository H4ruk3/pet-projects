<!-- addTeam.php -->
<?php include 'php/isUser.php'; ?>

<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_name = $_POST['team_name'];
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO team (name) VALUES (?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $team_name);
    $stmt->execute();
    $stmt->close();
    $team_id = $connection->insert_id;


    $query = "INSERT INTO user_in_team (user_id, team_id, access_type_id) 
    VALUES (?, ?, (SELECT access_type_id FROM access_type WHERE name = 'OWNER'))";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("ii", $user_id, $team_id);
    $stmt->execute();
    $stmt->close();
    session_write_close();
    header("Location: TeamsPage.php");
    // header("Location: showSelectedTeam.php?team_id=" . $team_id); // это на будущее
} else { ?>

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
        <title>Add Team</title>
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
            <form class="form_container" action="AddTeam.php" method="post">
                <input style="margin-top: 80px;" type="" name="team_name" id="" placeholder="Team name" required>
                <button style="margin-top: 30px;">ADD team</button>
            </form>
        </main>
    </body>

    </html>
<?php
}
?>