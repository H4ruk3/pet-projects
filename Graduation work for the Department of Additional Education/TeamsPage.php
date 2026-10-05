<!-- teamsPage.php -->
<?php
include 'php/isUser.php';
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
    <title>Teams</title>
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
            <?php include 'php/viewTeams.php'; ?>
        </section>

        <button style="width: 200px" id="add_team">ADD team</button>
        <script>
            document.getElementById('add_team').addEventListener('click', function() {
                window.location.href = 'AddTeam.php';
            });
        </script>
    </main>
</body>

</html>