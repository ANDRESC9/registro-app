<?php
include_once(__DIR__ . '/../config.php');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include __DIR__ . '/partials/head.php'; ?>
</head>
<style>
    .card-panel {
        margin: 2rem 0 !important;
    }
</style>

<body>
    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <main>
        <div class="container">
            <div id="adminPanel" class="card-panel grey lighten-4">
                <h5 class="center-align">Panel de Administrador</h5>
                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cédula</th>
                            <th>Actividad</th>
                            <th>Zona</th>
                            <th>Plaza</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody id="userList">
                        <!-- Los registros de usuarios se actualizarán aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>


    <?php include __DIR__ . '/partials/footer.php'; ?>
    <?php include __DIR__ . '/partials/scripts.php'; ?>

    <script>
        $(document).ready(function() {
            // Cargar los usuarios en la tabla
            loadUsers();
        });

        // Función para cargar los usuarios en la tabla del panel de administración
        function loadUsers() {
            $.ajax({
                url: '<?php echo BASE_URL; ?>/controllers/LoadUsersController.php',
                type: 'GET',
                success: function(response) {
                    const res = JSON.parse(response);
                    if (res.status === 'success') {
                        var userList = $('#userList');
                        userList.html('');
                        res.users.forEach(user => {
                            // Convertir el rol_id en texto
                            var rol = (user.rol_id == 1) ? 'Administrador' : 'Vendedor';
                            userList.append(`
                            <tr>
                                <td>${user.nombre}</td>
                                <td>${user.cedula}</td>
                                <td>${user.actividad}</td>
                                <td>${user.zona_nombre}</td>
                                <td>${user.plaza_numero}</td>
                                <td>${rol}</td>
                            </tr>
                        `);
                        });
                    } else {
                        Swal.fire('Error', 'No se pudo cargar la lista de usuarios', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Hubo un error al cargar los usuarios', 'error');
                }
            });
        }
    </script>
</body>

</html>