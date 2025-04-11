<?php
// Aseguramos que se envíe el código de estado HTTP correcto
http_response_code(404);

// Definir la ruta base absoluta para todos los recursos
$baseURL = '/sistemaclinica/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página no encontrada - Sistema Clínica</title>
  <!-- Bootstrap -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    crossorigin="anonymous" />
  <!-- Volt CSS -->
  <link type="text/css" href="<?php echo $baseURL; ?>css/dashboard/volt.css" rel="stylesheet" />
  <!-- Favicon -->
  <link rel="shortcut icon" href="<?php echo $baseURL; ?>img/favicon.ico" type="image/x-icon">
</head>
<body>
  <section class="vh-100 d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center d-flex align-items-center justify-content-center">
          <div class="mt-2">
            <img
              class="img-fluid w-75 p-3"
              src="<?php echo $baseURL; ?>img/404/404.jpg"
              alt="404 not found" />
            <h1 class="mt-5">
              Página no <span class="fw-bolder text-primary">encontrada</span>
            </h1>
            <p class="lead my-4">
              Lo sentimos, la página que estás buscando no existe o ha sido movida.
            </p>
            <a
              href="<?php echo $baseURL; ?>index.php"
              class="btn btn-gray-800 d-inline-flex align-items-center justify-content-center mb-4">
              <svg
                class="icon icon-xs me-2"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path
                  fill-rule="evenodd"
                  d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                  clip-rule="evenodd"></path>
              </svg>
              Volver al inicio
            </a>
            <?php if(isset($_SERVER['HTTP_REFERER'])): ?>
              <a
                href="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER']); ?>"
                class="btn btn-outline-primary d-inline-flex align-items-center justify-content-center mb-4 ms-2">
                Regresar a la página anterior
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="bg-white rounded shadow p-3 fixed-bottom">
    <div class="row">
      <div class="col-12 text-center">
        <p class="mb-0">© <?php echo date('Y'); ?> Sistema Clínica. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>

  <!-- Core JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
    crossorigin="anonymous"></script>
</body>
</html>