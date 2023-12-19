<?php
include_once '../db.php';
session_start();
checkAuth();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Личный кабинет - TicketMov</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="container mt-4">
        <div class="header">
            <h2>Личный кабинет пользователя <?php echo $_SESSION['login']; ?></h2>
            <button class="btn btn-primary" onclick="redirectToHomePage()">На главную страницу</button>
        </div>
        <h3>Ваши бронирования</h3>
        <div id="bookings-container"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="profile.js"></script>
    <script>
        function redirectToHomePage() {
            window.location.href = '../index/index.php'; // Замените 'index.php' на путь к вашей главной странице
        }
    </script>

</body>

</html>
