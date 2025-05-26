<?php
session_start();

// Cerrar la sesión y redirigir
session_unset();
session_destroy();

echo json_encode(['status' => 'success', 'message' => 'Sesión cerrada correctamente']);
?>
