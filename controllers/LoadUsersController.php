<?php
// controllers/LoadUsersController.php

include_once(__DIR__ . '/../config/database.php');
include_once(__DIR__ . '/../models/User.php');

session_start();

// Verifica si hay una sesión activa y si el rol es "admin"
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] !== 1) {  // 1 es el rol de administrador
    http_response_code(403); // Prohibido
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado']);
    exit;
}

// Crear una instancia de User pasando la conexión
$userModel = new User($conn);
$users = $userModel->getAllUsers(); // Obtener todos los usuarios

echo json_encode(['status' => 'success', 'users' => $users]);

?>
