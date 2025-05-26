<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost/web-test");
    exit();
}

include __DIR__ . '/../config/database.php';
$userId = $_SESSION['user_id'];
$query = "SELECT u.*, (SELECT nombre FROM zonas z WHERE z.id = u.zona) AS zona_nombre, (SELECT numero FROM plazas p WHERE p.id = u.plaza) AS plaza_numero FROM usuarios AS u WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Usuario no encontrado";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include __DIR__ . '/../views/partials/head.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>
</head>

<body>
    <?php include __DIR__ . '/../views/partials/navbar.php'; ?>

    <main>
        <div class="container">
            <div class="section">
                <div class="card center-align">
                    <div class="card-content">
                        <span class="card-title">Bienvenido, <?php echo htmlspecialchars($user['nombre']); ?>!</span>

                        <div class="center-align">
                            <i class="material-icons large circle">account_circle</i>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <p><strong>Cédula:</strong> <?php echo htmlspecialchars($user['cedula']); ?></p>
                                <p><strong>Actividad:</strong> <?php echo htmlspecialchars($user['actividad']); ?></p>
                                <p><strong>Zona:</strong> <?php echo htmlspecialchars($user['zona_nombre']); ?></p>
                                <p><strong>Plaza:</strong> <?php echo htmlspecialchars($user['plaza_numero']); ?></p>
                            </div>
                        </div>

                        <div class="center-align">
                            <canvas id="qrcode"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../views/partials/footer.php'; ?>
    <?php include __DIR__ . '/../views/partials/scripts.php'; ?>

    <script type="text/javascript">
        var cedula = "<?php echo $_SESSION['cedula']; ?>";
        var url = "http://localhost/web-test/perfil.php?cedula=" + encodeURIComponent(cedula);

        QRCode.toCanvas(document.getElementById("qrcode"), url, {
            width: 128,
            height: 128,
            colorDark: "#000000",
            colorLight: "#ffffff"
        }, function (error) {
            if (error) console.error(error);
            console.log('QR code generado!');
        });
    </script>


</body>

</html>
