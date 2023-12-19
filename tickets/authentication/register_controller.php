<?php
include_once '../db.php';

header('Content-Type: application/json');

$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirmPassword = trim($_POST['confirmPassword'] ?? '');

if (empty($login) || empty($password) || empty($confirmPassword) || !preg_match('/^[a-zA-Z0-9]+$/', $login) || !preg_match('/^[a-zA-Z0-9]+$/', $password)) {
    echo json_encode(['success' => false, 'error' => 'Неверно заполены поля.']);
    exit;
}

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
