<?php
include_once(__DIR__ . '/../../config.php');
?>

<nav class="grey darken-4 white-text">
    <div class="nav-wrapper container">
        <a href=" <?= BASE_URL ?>" class="brand-logo">Gestión QR Vendedores Informales
        </a>
        <a href="#" data-target="mobile-menu" class="sidenav-trigger">
            <i class="material-icons">menu</i>
        </a>
        <ul class="right hide-on-med-and-down" id="navbarLinks">
            <li><a href="#proposito">Propósito</a></li>
            <li><a href="#funcionalidad">Funcionalidades</a></li>
            <li><a class="modal-trigger" href="#loginModal" id="loginBtnNav">Iniciar Sesión</a></li>
        </ul>
    </div>
</nav>

<ul class="sidenav" id="mobile-menu">
    <li><a href="#proposito">Propósito</a></li>
    <li><a href="#funcionalidad">Funcionalidades</a></li>
    <li><a class="modal-trigger" href="#loginModal" id="loginBtnNav">Iniciar Sesión</a></li>
</ul>

<div id="loginModal" class="modal grey darken-4 white-text">
    <div class="modal-content">
        <h5 class="white-text">Iniciar Sesión</h5>
        <div class="input-field">
            <input id="cedula_login" type="text" class="validate white-text">
            <label for="cedula_login" class="white-text">Cédula</label>
        </div>
        <div class="input-field">
            <input id="password_login" type="password" class="validate white-text">
            <label for="password_login" class="white-text">Contraseña</label>
        </div>
    </div>
    <div class="modal-footer grey darken-4">
        <a class="modal-close waves-effect waves-light btn-flat white-text">Cancelar</a>
        <a id="loginBtn" class="waves-effect waves-light btn grey darken-3 white-text">Entrar</a>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.sidenav').sidenav();

        $('.modal').modal();

        checkSession();

        $("#loginBtn").on("click", function() {
            $.ajax({
                url: '<?php echo BASE_URL; ?>/controllers/LoginController.php',
                type: 'POST',
                data: {
                    cedula: $("#cedula_login").val(),
                    password: $("#password_login").val()
                },
                success: function(response) {
                    const res = JSON.parse(response);

                    if (res.status === 'success') {
                        Swal.fire('¡Bienvenido!', 'Has iniciado sesión correctamente', 'success')
                            .then(() => {
                                window.location.href = '<?php echo BASE_URL; ?>/views/welcome.php'; // Redirección con URL base
                            });
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Hubo un error en la conexión con el servidor', 'error');
                }
            });
        });
    });

    function updateNavbar(username, role) {
        var navbarLinks = $('#navbarLinks');
        var mobileMenu = $('#mobile-menu');

        var html = `
        <li><a href="#proposito">Propósito</a></li>
        <li><a href="#funcionalidad">Funcionalidades</a></li>
        <li><a href="<?= BASE_URL; ?>/views/zonasVisual.php">Zonas</a></li>
    `;

        if (role == '1') {
            html += `<li><a href="<?= BASE_URL; ?>/views/adminPanel.php">Panel de Administración</a></li>`;
        }

        html += `
        <li id="usernameDisplay">
            <a href="<?= BASE_URL; ?>/views/welcome.php">
                Bienvenido,<i class="material-icons left">account_circle</i> ${username}
            </a>
        </li>
        <li>
            <a class="waves-effect waves-light btn red white-text logoutBtn">
                <i class="material-icons left" style="margin: 0;">exit_to_app</i>
            </a>
        </li>
    `;

        navbarLinks.html(html);
        mobileMenu.html(html);

        // Agregar el evento de cerrar sesión
        $('.logoutBtn').on("click", function() {
            $.ajax({
                url: '<?php echo BASE_URL; ?>/controllers/LogoutController.php', 
                type: 'POST',
                success: function(response) {
                    Swal.fire('¡Hasta luego!', 'Has cerrado sesión', 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function() {
                    Swal.fire('Error', 'Hubo un error al cerrar sesión', 'error');
                }
            });
        });
    }

    function checkSession() {
        $.ajax({
            url: '<?php echo BASE_URL; ?>/controllers/CheckSessionController.php',
            type: 'GET',
            success: function(response) {
                try {
                    const res = JSON.parse(response);
                    if (res.status === 'success') {
                        updateNavbar(res.username, res.role);
                    }
                } catch (e) {
                    console.error("Error al verificar sesión", e);
                }
            },
            error: function() {
                console.error("Error al verificar la sesión");
            }
        });
    }
</script>