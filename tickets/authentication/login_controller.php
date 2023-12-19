<?php
include_once '../db.php';
header('Content-Type: application/json');

$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password'] ?? '');

$isLogined = loginUser($login, $password);

if (!$isLogined) {
    echo json_encode(['success' => false, 'error' => 'Неправильный логин или пароль.']);
    exit;
}

echo json_encode(['success' => true]);
