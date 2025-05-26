<?php
session_start();

// Incluir conexión a la base de datos
include '../config/database.php';

// Comprobar si se ha enviado la solicitud por AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados desde el formulario
    $cedula = $_POST['cedula'];
    $password = $_POST['password'];

    // Consultar si el usuario existe
    $query = "SELECT * FROM usuarios WHERE cedula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Si el usuario es encontrado
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Establecer la sesión del usuario
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nombre']; 
            $_SESSION['cedula'] = $user['cedula']; 
            $_SESSION['rol_id'] = $user['rol_id']; 
            $_SESSION['zona'] = $user['zona']; 
            $_SESSION['plaza'] = $user['plaza']; 
            
            // Redirigir a la página de bienvenida
            echo json_encode(['status' => 'success', 'redirect_url' => 'views/welcome.php']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cédula no encontrada']);
    }
    
    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
