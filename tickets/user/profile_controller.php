<?php
include_once '../db.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_SESSION['user_id'];
    $seats = getUserSeats($userId);
    echo json_encode(['success' => true, 'data' => $seats]);
} else {
    echo json_encode(['success' => false, 'error' => 'Неверный метод запроса']);
}
