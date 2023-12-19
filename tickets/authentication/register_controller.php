<?php
include_once '../db.php';

header('Content-Type: application/json');

$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirmPassword = trim($_POST['confirmPassword'] ?? '');

if ($password !== $confirmPassword) {
    echo json_encode(['success' => false, 'error' => 'Пароли не совпадают.']);
    exit;
}

if (userExists($login)) {
    echo json_encode(['success' => false, 'error' => 'Пользователь с таким логином уже существует.']);
    exit;
}

registerUser($login, $password);

echo json_encode(['success' => true]);
