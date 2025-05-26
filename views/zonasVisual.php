<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost/web-test");
    exit;
}

$zonas = $conn->query("SELECT * FROM zonas")->fetch_all(MYSQLI_ASSOC);

$zonaActual = $_SESSION['zona'];
$zonaActualNombre = match ($zonaActual) {
    1 => 'Soacha Parque',
    2 => 'San Mateo',
    3 => 'Terreros',
    default => 'Sin determinar',
};
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include __DIR__ . '/partials/head.php'; ?>
    <style>
        .zona-container {
            margin-top: 30px;
        }

        .zona-actual {
            border: 3px solid #00796b;
            box-shadow: 0 0 10px rgba(0, 150, 136, 0.4);
        }

        .plazas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 12px;
            margin-top: 20px;
        }

        .plaza {
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .disponible {
            background-color: #43a047;
        }

        .ocupada {
            background-color: #757575;
        }

        .card-content span.card-title {
            font-weight: 500;
            font-size: 20px;
        }

        .change-zone-section {
            margin-top: 40px;
        }

        .fixed-card {
            position: sticky;
            top: 20px;
            z-index: 1;
        }
    </style>
</head>

<body class="grey lighten-4">

    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <main class="container">
        <h4 class="center-align teal-text text-darken-3">Visualización de Zonas y Cambio de Zona</h4>

        <div class="row change-zone-section">
            <div class="col s12 m10 offset-m1">
                <div class="card white z-depth-3 fixed-card">
                    <div class="card-content">
                        <span class="card-title teal-text text-darken-4">Zona actual: <strong><?= htmlspecialchars($zonaActualNombre); ?></strong></span>
                        <form id="changeZoneForm">
                            <div class="input-field">
                                <select id="nueva_zona" name="nueva_zona" required>
                                    <option value="" disabled selected>Selecciona una nueva zona</option>
                                    <?php foreach ($zonas as $zona): ?>
                                        <option value="<?= $zona['id']; ?>" <?= $zona['id'] == $zonaActual ? 'selected' : ''; ?>>
                                            <?= $zona['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="nueva_zona">Cambiar a otra zona</label>
                            </div>
                            <div class="input-field">
                                <select id="nueva_plaza" name="nueva_plaza" required>
                                    <option value="" disabled selected>Selecciona una plaza disponible</option>
                                </select>
                                <label for="nueva_plaza">Cambiar a otra plaza</label>
                            </div>
                            <div class="right-align">
                                <button class="btn waves-effect waves-light teal darken-2" type="submit">
                                    Actualizar Zona y Plaza
                                    <i class="material-icons right">sync</i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php foreach ($zonas as $zona): ?>
            <?php
            $plazas = $conn->query("SELECT * FROM plazas WHERE zona_id = {$zona['id']} ORDER BY numero")->fetch_all(MYSQLI_ASSOC);
            $claseZona = ($zona['id'] == $zonaActual) ? 'zona-container zona-actual' : 'zona-container';
            ?>
            <div class="card white <?= $claseZona; ?> z-depth-2">
                <div class="card-content">
                    <span class="card-title teal-text text-darken-4"><?= $zona['nombre']; ?></span>
                    <div class="plazas-grid">
                        <?php foreach ($plazas as $plaza): ?>
                            <div class="plaza <?= $plaza['estado']; ?>" title="Plaza <?= $plaza['numero']; ?>">
                                <?= $plaza['numero']; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <?php include __DIR__ . '/../views/partials/footer.php'; ?>
    <?php include __DIR__ . '/../views/partials/scripts.php'; ?>

    <script>
        $(document).ready(function() {
            // Inicializar select de zonas
            $('select').formSelect();

            // Función para cargar las plazas disponibles según la zona seleccionada
            function loadPlazas(zonaId) {
                // Limpiar el dropdown antes de mostrar nuevas plazas
                $('#nueva_plaza').html('<option disabled selected>Cargando plazas...</option>');
                $('select').formSelect(); // Actualizar la interfaz de select

                // Enviar solicitud al controlador de PHP
                $.post('../controllers/GetAvailablePlazasByZonaController.php', {
                        zona_id: zonaId
                    })
                    .done(function(res) {
                        // Verificar si la respuesta es válida
                        if (res && res.status === 'success') {
                            let options = '<option value="" disabled selected>Selecciona una plaza disponible</option>';

                            // Generar las opciones de plazas disponibles
                            res.plazas.forEach(function(plaza) {
                                options += `<option value="${plaza.id}">Plaza ${plaza.numero}</option>`;
                            });
                            $('#nueva_plaza').html(options);
                        } else {
                            // En caso de que no haya plazas disponibles
                            $('#nueva_plaza').html('<option value="" disabled>No hay plazas disponibles</option>');
                        }
                        $('select').formSelect(); // Actualizar la interfaz de select
                    })
                    .fail(function() {
                        // Manejo de errores si la solicitud falla
                        Swal.fire('Error', 'Ocurrió un error al cargar las plazas', 'error');
                    });
            }

            // Cargar plazas iniciales al cargar la página (si ya hay una zona seleccionada)
            let zonaInicial = $('#nueva_zona').val();
            if (zonaInicial) {
                loadPlazas(zonaInicial);
            }

            // Event listener para cuando se cambia la zona
            $('#nueva_zona').on('change', function() {
                let zonaId = $(this).val();
                loadPlazas(zonaId); // Cargar plazas basadas en la zona seleccionada
            });

            // Manejo del formulario de cambio de zona y plaza
            $('#changeZoneForm').submit(function(e) {
                e.preventDefault(); // Evitar el comportamiento por defecto del formulario
                const nuevaZona = $('#nueva_zona').val();
                const nuevaPlaza = $('#nueva_plaza').val();

                // Validación de que los campos no estén vacíos
                if (!nuevaZona) {
                    Swal.fire('Error', 'Por favor selecciona una zona válida', 'error');
                    return; // Detener el envío si la zona está vacía
                }

                if (!nuevaPlaza) {
                    Swal.fire('Error', 'Por favor selecciona una plaza válida', 'error');
                    return; // Detener el envío si la plaza está vacía
                }

                // Realizar la solicitud AJAX para actualizar la zona y la plaza
                $.ajax({
                    url: '../controllers/UpdateUserZonePlazaController.php',
                    method: 'POST',
                    data: {
                        nueva_zona: nuevaZona,
                        nueva_plaza: nuevaPlaza
                    },
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('¡Listo!', res.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Ocurrió un error al cambiar la zona y la plaza', 'error');
                    }
                });
            });
        });
    </script>

</body>

</html>