<?php
// cinema_controller.php
include_once '../db.php';
header('Content-Type: application/json');

try {
    $cinemas = getCinemas(); // Функция, которая получает список кинотеатров из БД
    echo json_encode(['success' => true, 'data' => $cinemas]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>
