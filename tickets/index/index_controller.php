<?php
include_once '../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $movies = getActiveMovies();
    echo json_encode(['success' => true, 'data' => $movies]);
} else {
    echo json_encode(['success' => false, 'error' => "Неверный метод"]);
}
