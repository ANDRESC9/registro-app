<?php
$cedula = isset($_GET['cedula']) ? $_GET['cedula'] : null;

if (!$cedula) {
    echo "Cédula no proporcionada.";
    exit();
}

include __DIR__ . '/../config/database.php';

$query = "SELECT u.*, 
                 (SELECT nombre FROM zonas z WHERE z.id = u.zona) AS zona_nombre, 
                 (SELECT numero FROM plazas p WHERE p.id = u.plaza) AS plaza_numero 
          FROM usuarios AS u 
          WHERE u.cedula = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Usuario no encontrado.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Materialize CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Fuente personalizada -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ece9e6, #ffffff);
            font-family: 'Roboto', sans-serif;
        }
        header {
            background-color:rgb(0, 0, 0);
            padding: 20px 0;
            color: white;
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
        }
        .card {
            margin-top: 50px;
            border-radius: 12px;
        }
        .card .material-icons {
            font-size: 80px;
            color:rgb(0, 0, 0);
        }
        footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
        }
    </style>
</head>
<body>

    <header>
        Perfil del Usuario
    </header>

    <div class="container">
        <div class="row">
            <div class="col s12 m8 offset-m2">
                <div class="card z-depth-3">
                    <div class="card-content center-align">
                        <i class="material-icons circle">account_circle</i>
                        <h5 style="margin-top: 10px;">
                            <?php echo htmlspecialchars($user['nombre']); ?>
                        </h5>
                        <div class="divider" style="margin: 20px 0;"></div>
                        <p><strong>Cédula:</strong> <?php echo htmlspecialchars($user['cedula']); ?></p>
                        <p><strong>Actividad:</strong> <?php echo htmlspecialchars($user['actividad']); ?></p>
                        <p><strong>Zona:</strong> <?php echo htmlspecialchars($user['zona_nombre']); ?></p>
                        <p><strong>Plaza:</strong> <?php echo htmlspecialchars($user['plaza_numero']); ?></p>
                    </div>
                </div>
                <div class="center-align">
                    <a href="http://registro-app.ct.ws" class="btn waves-effect" style="background: black;">
                        Ir a la página principal
                        <i class="material-icons right">arrow_forward</i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="grey-text">
        &copy; <?php echo date('Y'); ?> Proyecto académico - Ingeniería de Sistemas | 2025. Todos los derechos reservados.
    </footer>

    <!-- Materialize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
