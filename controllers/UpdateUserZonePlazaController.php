<?php
require_once '../config/database.php';
session_start();

// Limpiar cualquier salida previa
ob_clean();
header('Content-Type: application/json');

// Verificar que se hayan enviado los datos necesarios
if (!isset($_POST['nueva_zona']) || !isset($_POST['nueva_plaza'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit;
}

$nuevaZona = (int) $_POST['nueva_zona'];
$nuevaPlaza = (int) $_POST['nueva_plaza'];

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se encontró el usuario en sesión']);
    exit;
}

$usuarioId = $_SESSION['user_id'];

// Verificar que la plaza exista y esté disponible
$sqlVerificarPlaza = "SELECT estado FROM plazas WHERE id = ? AND zona_id = ?";
$stmtVerificar = $conn->prepare($sqlVerificarPlaza);
$stmtVerificar->bind_param('ii', $nuevaPlaza, $nuevaZona);
$stmtVerificar->execute();
$res = $stmtVerificar->get_result();

if ($res->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'La plaza no existe o no pertenece a esa zona']);
    exit;
}

$plazaInfo = $res->fetch_assoc();
if ($plazaInfo['estado'] !== 'disponible') {
    echo json_encode(['status' => 'error', 'message' => 'La plaza ya está ocupada']);
    exit;
}
$stmtVerificar->close();

// Verificar que la plaza no esté asignada a otro usuario
$sqlAsignada = "SELECT id FROM usuarios WHERE plaza = ?";
$stmtAsignada = $conn->prepare($sqlAsignada);
$stmtAsignada->bind_param('i', $nuevaPlaza);
$stmtAsignada->execute();
$resAsignada = $stmtAsignada->get_result();

if ($resAsignada->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Esta plaza ya está asignada a otro usuario']);
    exit;
}
$stmtAsignada->close();

// Obtener plaza anterior para liberarla
$sqlPlazaAnterior = "SELECT plaza FROM usuarios WHERE id = ?";
$stmtPrev = $conn->prepare($sqlPlazaAnterior);
$stmtPrev->bind_param('i', $usuarioId);
$stmtPrev->execute();
$resPrev = $stmtPrev->get_result();
$plazaAnterior = $resPrev->fetch_assoc()['plaza'] ?? null;
$stmtPrev->close();

// Actualizar zona y plaza del usuario
$sqlUpdate = "UPDATE usuarios SET zona = ?, plaza = ? WHERE id = ?";
$stmt = $conn->prepare($sqlUpdate);
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Error al preparar actualización']);
    exit;
}
$stmt->bind_param('iii', $nuevaZona, $nuevaPlaza, $usuarioId);

if ($stmt->execute()) {
    // Ocupamos la nueva plaza
    $sqlOcupar = "UPDATE plazas SET estado = 'ocupada' WHERE id = ? AND zona_id = ?";
    $stmtOcupar = $conn->prepare($sqlOcupar);
    $stmtOcupar->bind_param('ii', $nuevaPlaza, $nuevaZona);
    $stmtOcupar->execute();
    $stmtOcupar->close();

    // Liberamos la plaza anterior si existía
    if ($plazaAnterior) {
        $sqlLiberar = "UPDATE plazas SET estado = 'disponible' WHERE id = ?";
        $stmtLiberar = $conn->prepare($sqlLiberar);
        $stmtLiberar->bind_param('i', $plazaAnterior);
        $stmtLiberar->execute();
        $stmtLiberar->close();
    }

    $_SESSION['zona'] = $nuevaZona;
    $_SESSION['plaza'] = $nuevaPlaza;

    echo json_encode(['status' => 'success', 'message' => 'Zona y plaza actualizadas con éxito']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar los datos']);
}
$stmt->close();
exit;
