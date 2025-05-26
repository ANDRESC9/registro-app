<?php
session_start();

// Verificar si el usuario está logueado
if (isset($_SESSION['user_id'])) {
    // Obtener el nombre del usuario desde la base de datos
    include '../config/database.php';
    $userId = $_SESSION['user_id'];
    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'username' => $user['nombre'], 'role' => $user['rol_id']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No hay sesión activa']);
}
?>
