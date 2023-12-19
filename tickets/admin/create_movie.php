<?php
include_once '../db.php';
session_start();
checkAuth();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Добавление Фильма - TicketMov</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <h2>Добавление нового фильма</h2>
        <form id="create-movie-form">
            <div class="form-group">
                <label for="movie-title">Название фильма</label>
                <input type="text" class="form-control" id="movie-title" name="title" required>
            </div>
            <div class="form-group">
                <label for="movie-description">Описание фильма</label>
                <textarea class="form-control" id="movie-description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="movie-image">Изображение фильма</label>
                <input type="file" class="form-control-file" id="movie-image" name="image" required>
            </div>
            <div class="form-group">
                <label for="movie-duration">Длительность фильма (в минутах)</label>
                <input type="number" class="form-control" id="movie-duration" name="duration" min="1" required>
            </div>
            <div class="form-group">
                <label for="movie-showtime">Время показа</label>
                <input type="datetime-local" class="form-control" id="movie-showtime" name="showtime" required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить фильм</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-movie-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'create_movie_controller.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            alert('Фильм успешно добавлен');
                            window.location.href = '../index/index.php';
                        } else {
                            alert(response.error);
                        }
                    },
                    error: function() {
                        alert('Ошибка при добавлении фильма.');
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
