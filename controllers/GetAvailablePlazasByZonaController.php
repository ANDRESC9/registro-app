<?php
require_once '../config/database.php';

// Limpiar cualquier salida previa (en caso de errores o espacio en blanco antes del JSON)
ob_clean();

// Asegúrate de que la respuesta es de tipo JSON
header('Content-Type: application/json');

// Verifica si se pasó el ID de la zona
if (!isset($_POST['zona_id']) || empty($_POST['zona_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Zona no especificada']);
    exit;
}

$zonaId = (int) $_POST['zona_id']; // Asegúrate de que el ID sea un número entero

// Consulta para obtener las plazas disponibles
$sql = "SELECT id, numero FROM plazas WHERE zona_id = ? AND estado = 'disponible' ORDER BY numero";

// Preparar la consulta para evitar inyecciones SQL
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $zonaId); // Vincula el parámetro (zona_id)
$stmt->execute();
$result = $stmt->get_result();

$plazas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $plazas[] = $row;
    }
}

// Respuesta que se enviará al cliente
$response = [
    'status' => 'success',
    'plazas' => $plazas
];

echo json_encode($response); // Envía la respuesta en formato JSON
exit;
