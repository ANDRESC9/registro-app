<?php
session_start();
include_once '../config/database.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'No has iniciado sesión']);
    exit;
}

if (!isset($_POST['nueva_zona'])) {
    echo json_encode(['status' => 'error', 'message' => 'Zona no válida']);
    exit;
}

$cedula = $_SESSION['cedula'];
$nuevaZona = $_POST['nueva_zona'];

$stmt = $conn->prepare("UPDATE usuarios SET zona = ? WHERE cedula = ?");
$stmt->bind_param('ss', $nuevaZona, $cedula);

if ($stmt->execute()) {
    $_SESSION['zona'] = $nuevaZona;
    echo json_encode(['status' => 'success', 'message' => 'Zona actualizada correctamente']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la zona']);
}
