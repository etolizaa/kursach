<?php
include_once '../db.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $duration = $_POST['duration'] ?? 0;
    $showtime = trim($_POST['showtime'] ?? '');

    if (empty($title) || empty($description) || empty($_FILES['image']['name']) || $duration <= 0 || strtotime($showtime) <= time()) {
        echo json_encode(['success' => false, 'error' => "Неверные данные"]);
        exit;
    }

    $imagePath = '../images/' . basename($_FILES['image']['name']);
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        echo json_encode(['success' => false, 'error' => "Ошибка загрузки изображения"]);
        exit;
    }

    createMovie($title, $description, $imagePath, $duration, date('Y-m-d H:i:s', strtotime($showtime)));
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => "Неверный метод"]);
}
