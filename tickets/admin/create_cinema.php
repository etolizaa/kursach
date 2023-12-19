<?php
include_once '../db.php';
session_start();
checkAuth();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавление Кинотеатра - TicketMov</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Добавление нового кинотеатра</h2>
    <form id="create-cinema-form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="cinema-name">Название кинотеатра</label>
            <input type="text" class="form-control" id="cinema-name" name="name" required>
        </div>
        <div class="form-group">
            <label for="cinema-address">Адрес</label>
            <input type="text" class="form-control" id="cinema-address" name="address" required>
        </div>
        <div class="form-group">
            <label for="cinema-image">Изображение кинотеатра</label>
            <input type="file" class="form-control-file" id="cinema-image" name="image" required>
        </div>
        <button type="submit" class="btn btn-primary">Добавить кинотеатр</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-cinema-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: 'create_cinema_controller.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert('Кинотеатр успешно добавлен');
                    window.location.href = '../index/index.php';
                },
                error: function() {
                    alert('Ошибка пи добавлении кинотеатра.');
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
</script>
</body>
</html>

