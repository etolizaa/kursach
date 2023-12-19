<?php
include_once '../db.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] == 'loadMovieDetails') {
    $movieId = $_GET['movie_id'] ?? null;
    if (!$movieId) {
        echo json_encode(['success' => false, 'error' => 'Неверный идентификатор фильма']);
        exit;
    }

    $movie = getMovie($movieId);
    if (!$movie || strtotime($movie['showtime']) < time()) {
        echo json_encode(['success' => false, 'error' => 'Фильм недоступен']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => ['movie' => $movie, 'isAdmin' => isAdmin()]]);
    exit;
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] == 'deleteMovie') {
    $movieId = $_POST['movie_id'] ?? null;
    if (!$movieId) {
        echo json_encode(['success' => false, 'error' => 'Неверный идентификатор фильма']);
        exit;
    }

    if (!isAdmin()) {
        echo json_encode(['success' => false, 'error' => 'У вас нет прав для удаления этого фильма']);
        exit;
    }

    if (deleteMovie($movieId)) {
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Ошибка при удалении фильма']);
        exit;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] == 'loadSeats') {
    $movieId = $_GET['movie_id'] ?? null;
    if (!$movieId) {
        echo json_encode(['success' => false, 'error' => 'Неверный идентификатор фильма']);
        exit;
    }

    $movie = getMovie($movieId);
    if (!$movie || strtotime($movie['showtime']) < time()) {
        echo json_encode(['success' => false, 'error' => 'Фильм недоступен']);
        exit;
    }

    $seats = getSeatsByMovie($movieId);
    echo json_encode(['success' => true, 'data' => ['movie' => $movie, 'seats' => $seats]]);
    exit;
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] == 'reserveSeat') {
    $movieId = $_POST['movie_id'] ?? null;
    $rowNumber = $_POST['row_number'] ?? null;
    $seatNumber = $_POST['seat_number'] ?? null;
    $userId = $_SESSION['user_id'];

    if (!$movieId || !$rowNumber || !$seatNumber) {
        echo json_encode(['success' => false, 'error' => 'Неверные данные для запроса.']);
        exit;
    }

    $movie = getMovie($movieId);
    if (!$movie) {
        echo json_encode(['success' => false, 'error' => 'Фильм не найден']);
        exit;
    }

    if ($rowNumber <= 0 || $rowNumber > $movie['max_rows'] || $seatNumber <= 0 || $seatNumber > $movie['max_seats']) {
        echo json_encode(['success' => false, 'error' => 'Неверные номера ряда или места']);
        exit;
    }

    if (isUserHasSeat($userId, $movieId)) {
        echo json_encode(['success' => false, 'error' => 'Вы уже зарезервировали место на этот фильм']);
        exit;
    }

    if (isSeatReserved($movieId, $rowNumber, $seatNumber)) {
        echo json_encode(['success' => false, 'error' => 'Место уже занято']);
        exit;
    }

    if (reserveSeat($movieId, $userId, $rowNumber, $seatNumber)) {
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Ошибка при бронировании места']);
        exit;
    }
}
