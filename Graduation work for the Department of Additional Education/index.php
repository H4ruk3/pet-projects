<!-- index.php -->
<?php include 'php/logout.php'; ?>

<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
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
            <span>JI.RU</span>
        </nav>

    </header>
    <main class="container">
        <form class="form_container" action="php/login.php" method="post">
            <input tabindex="1" style="margin-top: 200px;" type="email" name="login" id="login" required placeholder="LOGIN...">
            <input tabindex="2" style="margin-bottom: 10px;" type="password" name="password" id="password" required placeholder="PASSWORD...">
            <div class="input_links">
                <a tabindex="3" style="user-select: none;  text-decoration: underline; cursor: pointer;" id="toggle-password">Show Password</a>
                <button tabindex="6" type = "submit" name = "go_in" id="login" class="button_login">Login</button>
                <a tabindex="4" href="#" onclick="alert('ЛОХ')">Forgot password</a>
                <a tabindex="5" href="Registration.html">Create account</a>
            </div>
        </form>
    </main>
</body>
<script>
    document.getElementById('toggle-password').addEventListener('click', function () {
            var passwordInput = document.getElementById('password');
            var togglePasswordLink = document.getElementById('toggle-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePasswordLink.textContent = 'Hide Password';
            } else {
                passwordInput.type = 'password';
                togglePasswordLink.textContent = 'Show Password';
            }
        });
</script>
</html>