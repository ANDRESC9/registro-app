<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax'])) {
  session_start();

  $nombre    = $_POST['nombre'];
  $cedula    = $_POST['cedula'];
  $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $actividad = $_POST['actividad'];
  $rol_id = 2;

  $query = "INSERT INTO usuarios (nombre, cedula, password, actividad, rol_id) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssssi", $nombre, $cedula, $password, $actividad, $rol_id);

  if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['username'] = $nombre;
    $_SESSION['cedula'] = $cedula;
    echo json_encode(['status' => 'success', 'message' => '¡Registro exitoso!']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Hubo un error al registrar el usuario']);
  }

  $stmt->close();
  $conn->close();
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php include __DIR__ . '/partials/head.php'; ?>
  <style>
    body {
      background-color: #f5f5f5;
    }

    .card-panel {
      padding: 40px 30px;
      border-radius: 12px;
    }

    .btn-large {
      width: 100%;
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <?php include __DIR__ . '/partials/navbar.php'; ?>

  <main class="container">
    <div class="row" style="margin-top: 40px;">
      <div class="col s12 m8 offset-m2 l6 offset-l3">
        <div class="card-panel white z-depth-3">
          <h5 class="center-align teal-text text-darken-4">Registro de Usuario</h5>

          <form id="registroForm">
            <div class="input-field">
              <input type="text" id="nombre" name="nombre" required>
              <label for="nombre">Nombre completo</label>
            </div>
            <div class="input-field">
              <input type="text" id="cedula" name="cedula" required>
              <label for="cedula">Cédula</label>
            </div>
            <div class="input-field">
              <input id="password" name="password" type="password" class="validate" required>
              <label for="password">Contraseña</label>
            </div>
            <div class="input-field">
              <input type="text" id="actividad" name="actividad" required>
              <label for="actividad">Actividad</label>
            </div>
            <button type="submit" class="btn-large teal darken-2 waves-effect waves-light">Registrar</button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <?php include __DIR__ . '/../views/partials/footer.php'; ?>
  <?php include __DIR__ . '/../views/partials/scripts.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      M.FormSelect.init(document.querySelectorAll('select'));
    });

    $("#registroForm").submit(function(e) {
      e.preventDefault();

      let nombre = $("#nombre").val().trim();
      let cedula = $("#cedula").val().trim();
      let password = $("#password").val().trim();
      let actividad = $("#actividad").val().trim();

      if (!nombre || !cedula || !password || !actividad) {
        Swal.fire('Campos incompletos', 'Todos los campos son obligatorios', 'warning');
        return;
      }

      let formData = $(this).serializeArray();
      formData.push({
        name: 'ajax',
        value: true
      });

      $.ajax({
        type: "POST",
        url: "registro.php",
        data: formData,
        dataType: "json",
        success: function(response) {
          if (response.status === 'success') {
            Swal.fire('¡Registro exitoso!', response.message, 'success');
            $("#registroForm")[0].reset();
          } else {
            Swal.fire('Error', response.message, 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'Hubo un problema de conexión', 'error');
        }
      });
    });
  </script>
</body>

</html>