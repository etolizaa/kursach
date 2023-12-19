<?php
include_once '../db.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($name) || empty($address) || empty($_FILES['image']['name'])) {
        echo json_encode(['success' => false, 'error' => "Неверные данные"]);
        exit;
    }

    $imagePath = '../images/' . basename($_FILES['image']['name']);
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        echo json_encode(['success' => false, 'error' => "Ошибка загрузки изображения"]);
        exit;
    }

    createCinema($name, $address, $imagePath);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => "Неверный метод"]);
}
?>
