<?php
include_once '../db.php';
session_start();
checkAuth();
$movieId = $_GET['movie_id'] ?? null;
$cinemaId = $_GET['cinema_id'] ?? null;
$seats = getSeatsByMovie($movieId);

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Детали Фильма - TicketMov</title>
    <link rel="stylesheet" href="movie_style.css">
</head>

<body>
<div class="container">
    <div class="movie-container" id="movie-details-container">
        <!-- Детали фильма будут загружены здесь -->
        <div class="movie-details">
            <!-- Текстовая информация о фильме будет здесь -->
            <a href="../index/index.php" class="btn btn-primary">На главную страницу</a>
        </div>
    </div>
    <section class="mb-4 text-center">
        <h2 class="font-weight-bold">Места в зале</h2>
        <p>Вы можете выбрать себе любое доступное.</p>
    </section>
    <div id="seats-container">
        <!-- Таблица мест будет загружена здесь -->
    </div>
        <div class="movie-details">
            <!-- Текстовая информация о фильме будет здесь -->
        </div>
    </div>
    <div class="text-center">
        <a href="../index/index.php" class="btn btn-primary">На главную страницу</a>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script>
    $(document).ready(function() {
        var movieId = new URLSearchParams(window.location.search).get('movie_id');
        if (!movieId) {
            alert('Фильм не найден.');
            window.location.href = '../index/index.php';
            return;
        }

        loadMovieDetails(movieId);
        setInterval(function() {
            loadMovieDetails(movieId);
        }, 5000);
        loadSeats(movieId);
        setInterval(function() {
            loadSeats(movieId);
        }, 5000);

        function loadMovieDetails(movieId) {
            $.ajax({
                url: 'movie_details_controller.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    action: 'loadMovieDetails',
                    movie_id: movieId
                },
                success: function(response) {
                    if (response.success) {
                        updateMovieDetails(response.data);
                    } else {
                        alert(response.error);
                        window.location.href = '../index/index.php';
                    }
                },
                error: function() {
                    alert('Ошибка при загрузке деталей фильма.');
                }
            });
        }

        function updateMovieDetails(data) {
            var movie = data.movie;
            $('#movie-details-container').html(`
                    <div class="row">
                        <div class="col-md-4">
                            <img src="${movie.image}" alt="${movie.title}" style="width: 250px; height: 400px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <h3 class="text-center">${movie.title}</h3>
                            <p>${movie.description}</p>
                            <p class="text-right">Длительность: ${movie.duration} минут</p>
                            <p class="text-right">Время показа: ${movie.showtime}</p>
                            <!-- Условие для отображения кнопки удаления для админов -->
                            ${data.isAdmin ? '<button class="movie-delete-btn btn btn-danger" style="float: right">Удалить фильм</button>' : ''}
                        </div>
                    </div>
                `);
        }

        $(document).on('click', '.movie-delete-btn', function() {
            if (confirm('Вы уверены, что хотите удалить этот фильм?')) {
                deleteMovie(movieId);
            }
        });

        function deleteMovie(movieId) {
            $.ajax({
                url: 'movie_details_controller.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'deleteMovie',
                    movie_id: movieId
                },
                success: function(response) {
                    if (response.success) {
                        alert('Фильм успешно удален');
                        window.location.href = '../index/index.php';
                    } else {
                        alert(response.error);
                    }
                },
                error: function() {
                    alert('Ошибка при удалении фильма.');
                }
            });
        }

        function loadSeats(movieId) {
            $.ajax({
                url: 'movie_details_controller.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    action: 'loadSeats',
                    movie_id: movieId
                },
                success: function(response) {
                    if (response.success) {
                        createSeatsTable(response.data.movie, response.data.seats);
                    } else {
                        alert(response.error);
                    }
                },
                error: function() {
                    alert('Ошибка при загрузке мест.');
                }
            });
        }

        function createSeatsTable(movie, seats) {
            var table = $('<table class="table table-bordered"></table>');
            for (var i = 1; i <= movie.max_rows; i++) {
                var row = $('<tr></tr>');
                for (var j = 1; j <= movie.max_seats; j++) {
                    var seatId = `${i}-${j}`;
                    var isReserved = seats.some(seat => seat.row_number === i && seat.seat_number === j);
                    var checkbox = $('<input type="checkbox" class="seat-checkbox">').attr('id', seatId).prop('checked', isReserved);
                    var cell = $('<td></td>').append(checkbox);
                    row.append(cell);
                }
                table.append(row);
            }
            $('#seats-container').html(table);
        }

        $(document).on('change', '.seat-checkbox', function() {
            var seatId = $(this).attr('id');
            var [row, seat] = seatId.split('-').map(Number);
            reserveSeat(movieId, row, seat);
        });

        function reserveSeat(movieId, row, seat) {
            $.ajax({
                url: 'movie_details_controller.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'reserveSeat',
                    movie_id: movieId,
                    row_number: row,
                    seat_number: seat
                },
                success: function(response) {
                    if (response.success) {
                        alert('Место успешно зарезервировано');
                        loadSeats(movieId);
                    } else {
                        alert(response.error);
                        loadSeats(movieId);
                    }
                },
                error: function() {
                    alert('Ошибка при бронировании места.');
                    loadSeats(movieId);
                }
            });
        }


    });
</script>
</body>

</html>
