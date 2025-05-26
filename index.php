<!DOCTYPE html>
<html lang="es">

<head>
  <?php include __DIR__ . '/views/partials/head.php'; ?>
  <style>
    /* Hero Section */
    .hero {
      position: relative;
      color: white;
      padding: 120px 20px;
      text-align: center;
      background: url('public/imagenes/soacha.jpeg') no-repeat center center;
      background-size: cover;
    }

    .hero h3 {
      font-weight: 700;
      font-size: 3rem;
      letter-spacing: 2px;
    }

    .hero p {
      font-size: 1.4rem;
      margin-top: 20px;
      opacity: 0.8;
    }

    .hero .btn-large {
      border-radius: 30px;
    }

    /* Estilo para las secciones */
    .section-content {
      padding: 60px 0;
    }

    .section-content h4 {
      font-size: 2.5rem;
      font-weight: 600;
      color: #333;
    }

    .section-content p {
      color: #555;
      font-size: 1.2rem;
      line-height: 1.6;
    }

    .section-content .row {
      margin-top: 30px;
    }

    /* Navbar */
    .nav-wrapper {
      /* background-color: #26a69a !important; */
    }


    .sidenav,
    nav {
      /* background-color: #26a69a !important; */
    }

    .sidenav li a {
      color: #fff !important;
    }

    /* Footer */
    footer {
      /* background-color: #26a69a; */
      color: white;
      padding: 20px 0;
      font-size: 1.1rem;
    }

    footer p {
      margin: 0;
      font-weight: 400;
    }

    /* Media Queries */
    @media (max-width: 768px) {
      .hero h3 {
        font-size: 2.5rem;
      }

      .hero p {
        font-size: 1.2rem;
      }

      .section-content h4 {
        font-size: 2rem;
      }

      .sidenav li a {
        color: black !important;
      }
    }
  </style>
</head>

<body>
  <?php include __DIR__ . '/views/partials/navbar.php'; ?>

  <main>
    <!-- Hero Section -->
    <div class="hero">
      <h3>Bienvenido a RegistroApp</h3>
      <p>La plataforma más sencilla y eficiente para gestionar tus registros.</p>
      <a href="views/registro.php" class="btn-large grey darken-4 white-text">¡Regístrate Ahora!</a>
    </div>

    <!-- Propósito -->
    <section id="proposito" class="section-content">
      <div class="container">
        <h4 class="center-align">¿Cuál es el propósito?</h4>
        <p class="flow-text center-align">
          RegistroApp busca ofrecer una solución tecnológica para gestionar registros de manera rápida y eficiente, sin complicaciones. Regístrate y accede a un sistema intuitivo y accesible desde cualquier dispositivo.
        </p>
      </div>
    </section>

    <!-- Funcionalidades -->
    <section id="funcionalidad" class="section-content">
      <div class="container">
        <h4 class="center-align">¿Qué ofrece el sistema?</h4>
        <div class="row">
          <div class="col s12 m4 l4">
            <div class="card-panel">
              <i class="material-icons">assignment</i>
              <h5>Registro Digital</h5>
              <p>Registra tus datos de forma rápida y segura con un solo clic.</p>
            </div>
          </div>
          <div class="col s12 m4 l4">
            <div class="card-panel">
              <i class="material-icons">location_on</i>
              <h5>Zonas Controladas</h5>
              <p>Asigna y consulta tus espacios de trabajo desde el mapa interactivo.</p>
            </div>
          </div>
          <div class="col s12 m4 l4">
            <div class="card-panel">
              <i class="material-icons">qr_code</i>
              <h5>Carné Virtual</h5>
              <p>Generación de un código QR único para cada registro, facilitando la identificación.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Llamado a la acción -->
    <section id="empezar" class="section-content">
      <div class="container center-align">
        <h4>¿Listo para comenzar?</h4>
        <p class="flow-text">Haz clic en el botón y empieza a gestionar tus registros de manera fácil y rápida.</p>
        <a href="/registro-app/public/register.php" class="btn-large grey darken-4 white-text">¡Empezar!</a>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/views/partials/footer.php'; ?>
  <?php include __DIR__ . '/views/partials/scripts.php'; ?>
</body>

</html>