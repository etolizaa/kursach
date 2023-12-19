<?php
include_once '../db.php';
session_start();
checkNotAuth();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<div class="background-image">
    <img src="../images/film.jpg" alt="Фоновое изображение">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-form">
                <h2 class="text-center">Вход в аккаунт</h2>
                <form id="loginForm">
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <input type="text" class="form-control" id="login" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
                <a href="register.php">Нет аккаунта? Регистрация</a>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="authentication.js" ></script>
</body>

</html>
