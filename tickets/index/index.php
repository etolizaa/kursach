<?php
include_once '../db.php';
session_start();
checkAuth();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>TicketMov - Главная</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header class="header">
    <div class="container navbar">
        <a href="../user/profile.php" class="navbar-brand">Личный кабинет</a>
        <a href="../authentication/logout.php" class="btn btn-outline-danger">Выйти</a>
    </div>
</header>

<div class="container mt-4">
    <section class="mb-4 text-center">
        <h2 class="font-weight-bold">Добро пожаловать в TicketMov!</h2>
        <p>Здесь вы можете бронировать билеты в кино.</p>
    </section>

    <div class="movie-row" id="movies-row">
        <!-- Карточки фильмов будут здесь -->
    </div>

    <?php if (isAdmin()) : ?>
        <a href="../admin/create_movie.php" class="btn btn-success">Добавить фильм</a>
    <?php endif; ?>

    <div class="container">
        <h2 class="mb-4 mt-4">Наши кинотеатры</h2>
        <div id="cinemas-row" class="row">
            <!-- Карточки кинотеатров будут здесь -->
        </div>
        <div id="map"></div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=PLACEHOLDER&lang=ru_RU"></script>
    <script src="index.js" ></script>
</body>

</html>
