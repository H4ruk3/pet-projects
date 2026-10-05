<!-- showSelectedTeamTask.php -->
<?php include 'php/isUser.php'; ?>
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
    <style>
        /* Основные стили для чата */
        #chat-container {
            position: fixed;
            bottom: 10px;
            left: 10px;
            width: 500px;
            max-height: 400px;
            min-height: 200px;
            display: none;
            flex-direction: column;
            background: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #chat-header {
            background: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        #chat-box {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background: #fff;
        }

        #chat-input-container {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
            background: #f9f9f9;
        }

        #message-input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        button {
            margin-left: 10px;
            padding: 8px 12px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover {
            background: #0056b3;
        }

        #chat-toggle {
            position: fixed;
            bottom: 10px;
            left: 10px;
            background: #007bff;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 18px;
            line-height: 50px;
            text-align: center;
            cursor: pointer;
        }
    </style>
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
    <main style="display: flex; justify-content: flex-start; padding-top: 80px; margin-left: auto;">
        <?php
        session_start();
        $user_id = $_SESSION['user_id'];
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $task_id = $_GET['task_id']; // team_task_id
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task_id = $_POST['task_id'];
        }


        $stmt = $connection->prepare(
            "SELECT team_task.user_id as user_id_for_task, team_task.team_id as team_id, 
                    team_task.name as task_name, 
                    user.name as user_name, description, start_date, end_date, access_type_id
                FROM team_task
                join user using(user_id)
                join user_in_team 
                    on user_in_team.team_id = team_task.team_id and user_in_team.user_id = team_task.user_id
                where team_task_id = ?
                "
        );

        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            echo "No tasks found";
            return;
        }
        $row = $result->fetch_assoc();
        $user_id_for_task = $row['user_id_for_task'];
        $team_id = $row['team_id'];
        $task_name = $row['task_name'];
        $user_name = $row['user_name'];
        $description = $row['description'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
        // $access_type_id = $row['access_type_id'];

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
            "SELECT access_type_id
            FROM user_in_team
            WHERE user_id = ? AND team_id = ?
            "
        );
        $stmt->bind_param("ii", $user_id, $team_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $access_type_id = $row['access_type_id'];

        if ($start_date == "" or is_null($start_date)) {
            $start_date = "Not stated";
        }
        if ($end_date == "" or is_null($end_date)) {
            $end_date = "Not stated";
        }
        if ($access_type_id == 4) {
            header("Location: showSelectedTeam.php?team_id=" . $team_id);
            return;
        } else {
        ?>
            <section style="display: flex; flex-direction: column;" width="100%">
                <h1>Task name:&nbsp;<span style="font-weight: normal;"> <?= $task_name ?> </span></h1>
                <h3>For:&nbsp;<span style="font-weight: normal;"><?= $user_name ?></span> </h3>
                <p style="font-size: 18px;"><b>Description: &nbsp;</b> <?= $description ?></p>
                <p><b>Date:&nbsp;</b> <?= $start_date ?> - <?= $end_date ?></p>
                <?php
                if ($user_id_for_task == $user_id) { //! не готово 
                ?>
                    <form class="form_container" action="php/completeSelectedTeamTask.php" method="POST">
                        <textarea style="width: 100%;" name="task_report" id="task_report" placeholder="report..." maxlength="256" required></textarea>
                        <input type="hidden" name="task_id" value="<?= $task_id ?>">
                        <input type="hidden" name="team_id" value="<?= $team_id ?>">
                        <button style="margin-top: 15px">Complete task</button>
                    </form>
                <?php
                }
                ?>
            </section>
            <section>
                <!-- Кнопка для открытия/закрытия чата -->
                <div id="chat-toggle">💬</div> <!-- Кнопка чата -->

                <div id="chat-container" style="display: none;"> <!-- Панель чата -->
                    <div id="chat-header">Чат</div>
                    <div id="chat-box"></div>
                    <div id="chat-input-container">
                        <input type="text" id="message-input" placeholder="Введите сообщение...">
                        <button style="width: 20px" id="send-button">Отправить</button>
                    </div>
                </div>

                <script>
                    // Элементы DOM
                    const chatToggle = document.getElementById('chat-toggle');
                    const chatContainer = document.getElementById('chat-container');
                    const chatBox = document.getElementById('chat-box');
                    const messageInput = document.getElementById('message-input');
                    const sendButton = document.getElementById('send-button');
                    const userName = "<?= addslashes($_SESSION['user_name']) ?>";
                    const access_type = <?= $access_type_id ?>;
                    const team_id = <?= $team_id ?>;
                    console.log('access_type', access_type)
                    console.log('access_type', userName)
                    // Установим соединение с WebSocket-сервером
                    
                    const socket = new WebSocket('ws://localhost:8080');
                    socket.onopen = () => {
                        console.log('Соединение установлено');
                    };
                    chatToggle.addEventListener('click', () => {
                        console.log('Кнопка нажата');
                    });
                    // Обработчик открытия/закрытия чата
                    chatToggle.addEventListener('click', () => {
                        if (access_type != 2 && access_type != 1) return;
                        if (chatContainer.style.display === 'none' || chatContainer.style.display === '') {
                            chatContainer.style.display = 'flex'; // Показать чат
                            chatToggle.style.display = 'none'; // Скрыть кнопку
                            messageInput.focus(); // Фокус на поле ввода
                        }
                    });

                    // Закрытие чата при клике на заголовок
                    document.getElementById('chat-header').addEventListener('click', () => {
                        chatContainer.style.display = 'none';
                        chatToggle.style.display = 'block';
                    });

                    // Обработчик получения сообщений
                    socket.addEventListener('message', event => {
                        if (access_type != 2 && access_type != 1) return;
                        const messageDiv = document.createElement('div');
                        let message = event.data;
                        if (!message.startsWith('team_id['+team_id+']')) return;
                        message = message.replace('team_id['+team_id+']', '');
                        messageDiv.textContent = message;
                        chatBox.appendChild(messageDiv);
                        chatBox.scrollTop = chatBox.scrollHeight; // Прокрутка вниз
                    });

                    // Отправка сообщения
                    sendButton.addEventListener('click', () => {
                        if (access_type != 2 && access_type != 1) return;
                        let message = messageInput.value;
                        if (message.trim() !== '') {
                            message = "team_id[" + team_id + "]" + userName + ': ' + message;
                            socket.send(message);
                            messageInput.value = ''; // Очистка поля ввода
                            messageInput.focus();
                        }
                    });

                    messageInput.addEventListener('keydown', event => {
                        if (event.key === 'Enter') {
                            sendButton.click();
                        }
                    });

                    // Обработчик ошибок WebSocket
                    socket.addEventListener('error', () => {
                        alert('Ошибка соединения с сервером!');
                    });
                </script>
            </section>

        <?php
        }
        session_write_close();
        ?>



    </main>
</body>

</html>