<?php
// Incluir archivo de conexión
require_once 'models/Conexion.php';

// Creación de conexión
$conexion = new Conexion();
$conn = $conexion->getConexion();

// Obtener las imágenes del carrusel
try {
    $stmt = $conn->prepare("SELECT * FROM carrusel ORDER BY id ASC LIMIT 3");
    $stmt->execute();
    $carruselItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener imágenes del carrusel: " . $e->getMessage());
    $carruselItems = [];
}

// Obtener promociones visibles inicialmente (primeras 2)
try {
    $stmt = $conn->prepare("SELECT * FROM promociones WHERE estado = 1 ORDER BY id DESC LIMIT 2");
    $stmt->execute();
    $promocionesIniciales = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener promociones iniciales: " . $e->getMessage());
    $promocionesIniciales = [];
}

// Obtener todas las demás promociones (a partir de la 3ra)
try {
    $stmt = $conn->prepare("SELECT * FROM promociones WHERE estado = 1 ORDER BY id DESC LIMIT 100 OFFSET 2");
    $stmt->execute();
    $promocionesAdicionales = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener promociones adicionales: " . $e->getMessage());
    $promocionesAdicionales = [];
}

// Generar un código único para prevenir el cache de imágenes
$cacheBuster = time();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
  <title>Clínica Médica - Cuidamos de su Salud</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Animate.css para animaciones -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  <!-- CSS personalizado -->
  <link rel="stylesheet" href="css/web.css">
  
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  
  <style>
    /* Mejoras para el carrusel sin cambiar su apariencia */
    .carousel-item img {
      width: 100%;
      height: auto;
      object-fit: cover;
    }
    
    /* Aseguramos que los controles del carrusel sean visibles */
    .carousel-control-prev, 
    .carousel-control-next {
      opacity: 0.7;
    }
    
    .carousel-control-prev:hover, 
    .carousel-control-next:hover {
      opacity: 1;
    }
    
    /* Aseguramos que los indicadores del carrusel sean visibles */
    .carousel-indicators button {
      background-color: rgba(255, 255, 255, 0.5);
      border: 1px solid rgba(0, 0, 0, 0.2);
    }
    
    .carousel-indicators .active {
      background-color: #fff;
    }
    
    /* Estilos para las promociones adicionales (inicialmente ocultas) */
    #promocionesAdicionales {
      display: none;
      animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    /* Estilo para el badge de descuento */
    .promo-badge {
      font-weight: bold;
      z-index: 1;
    }
    
    /* Efecto hover para las tarjetas de promoción */
    .promotion-item {
      transition: all 0.3s ease;
    }
    
    .promotion-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
    }
    
    /* Estilo para el botón Ver más/menos */
    .btn-ver-mas {
      transition: all 0.3s ease;
    }
    
    .btn-ver-mas:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Animación para el icono */
    .btn-ver-mas i {
      transition: transform 0.3s ease;
    }
    
    .btn-ver-mas.active i {
      transform: rotate(180deg);
    }
  </style>
</head>

<body id="inicio">
  <!-- Barra de navegación -->
  <nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
    <div class="container">
      <!-- Logo y nombre a la izquierda con mejoras de visibilidad -->
      <a class="navbar-brand d-flex align-items-center" href="#inicio">
        <div class="logo-container"
          style="width: 50px; height: 50px; overflow: hidden; border-radius: 50%; border: 2px solid #0d6efd; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
          <img src="img/iconoClinica.jpg" alt="Logo Clínica" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <span class="ms-3 fw-bold text-primary">Clínica Médica</span>
      </a>

      <!-- Botón de hamburguesa para móviles -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Los elementos de navegación -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Elementos de navegación en el centro -->
        <ul class="navbar-nav me-auto ms-auto">
          <li class="nav-item">
            <a class="nav-link nav-item-custom active" href="#inicio">
              <i class="fas fa-home nav-icon"></i> INICIO
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-item-custom" href="#especialidades">
              <i class="fas fa-stethoscope nav-icon"></i> ESPECIALIDADES
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-item-custom" href="#promociones">
              <i class="fas fa-tag nav-icon"></i> PROMOCIONES
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-item-custom" href="#testimonios">
              <i class="fas fa-comment-alt nav-icon"></i> TESTIMONIOS
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-item-custom" href="#contacto">
              <i class="fas fa-phone-alt nav-icon"></i> CONTACTO
            </a>
          </li>
        </ul>
      </div>

      <!-- Botón "INICIAR SESIÓN" completamente a la derecha, fuera del collapse -->
      <a class="btn btn-primary login-btn ms-2 fw-bold" href="login.php" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <i class="fas fa-user-circle me-1"></i> INICIAR SESIÓN
      </a>
    </div>
  </nav>

  <!-- Carrusel -->
  <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <?php foreach ($carruselItems as $key => $item): ?>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $key; ?>" 
                class="<?php echo ($key == 0) ? 'active' : ''; ?>" 
                aria-current="<?php echo ($key == 0) ? 'true' : 'false'; ?>" 
                aria-label="Slide <?php echo $key + 1; ?>"></button>
      <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
      <?php foreach ($carruselItems as $key => $item): ?>
        <div class="carousel-item <?php echo ($key == 0) ? 'active' : ''; ?>">
          <img src="img/carrusel/<?php echo htmlspecialchars($item['imagen']); ?>?v=<?php echo $cacheBuster; ?>" 
               class="d-block w-100 carousel-image" 
               data-src="img/carrusel/<?php echo htmlspecialchars($item['imagen']); ?>" 
               alt="<?php echo htmlspecialchars($item['descripcion']); ?>">
          <div class="carousel-caption">
            <h3 class="animate__animated animate__fadeInDown"><?php echo htmlspecialchars($item['titulo']); ?></h3>
            <p class="animate__animated animate__fadeInUp"><?php echo htmlspecialchars($item['texto']); ?></p>
            <a href="<?php echo htmlspecialchars($item['boton_enlace']); ?>" 
               class="btn btn-primary animate__animated animate__fadeInUp">
              <?php echo htmlspecialchars($item['boton_texto']); ?>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
      data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
      data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
    </button>
  </div>

  <!-- Sección de especialidades -->
  <section id="especialidades" class="specialties-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="section-title text-center mb-5">
            <h2 class="title-bold">NUESTRAS ESPECIALIDADES</h2>
            <span class="section-separator"></span>
            <p class="title-desc">Ofrecemos atención especializada en diversas áreas de la medicina</p>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Especialidad 1 -->
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="specialty-card">
            <div class="specialty-icon">
              <i class="fas fa-heartbeat"></i>
            </div>
            <h4>Cardiología</h4>
            <p>Diagnóstico y tratamiento integral de enfermedades cardiovasculares con equipos de última generación.</p>
            <a href="#" class="btn btn-sm btn-outline-primary mt-2">Más información</a>
          </div>
        </div>

        <!-- Especialidad 2 -->
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="specialty-card">
            <div class="specialty-icon">
              <i class="fas fa-brain"></i>
            </div>
            <h4>Neurología</h4>
            <p>Evaluación y tratamiento de trastornos del sistema nervioso por médicos altamente especializados.</p>
            <a href="#" class="btn btn-sm btn-outline-primary mt-2">Más información</a>
          </div>
        </div>

        <!-- Especialidad 3 -->
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="specialty-card">
            <div class="specialty-icon">
              <i class="fas fa-baby"></i>
            </div>
            <h4>Pediatría</h4>
            <p>Cuidados especializados para la salud y el desarrollo de bebés, niños y adolescentes.</p>
            <a href="#" class="btn btn-sm btn-outline-primary mt-2">Más información</a>
          </div>
        </div>

        <!-- Especialidad 4 -->
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="specialty-card">
            <div class="specialty-icon">
              <i class="fas fa-tooth"></i>
            </div>
            <h4>Odontología</h4>
            <p>Servicios odontológicos completos para toda la familia, desde prevención hasta tratamientos específicos.</p>
            <a href="#" class="btn btn-sm btn-outline-primary mt-2">Más información</a>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-12 text-center">
          <a href="especialidades.php" class="btn btn-primary">Ver todas las especialidades</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Sección de promociones -->
  <section id="promociones" class="promotion-area py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="section-title text-center mb-5">
            <h2 class="title-bold">NUESTRAS PROMOCIONES</h2>
            <span class="section-separator"></span>
            <p class="title-desc">Cuidamos de su salud con servicios de calidad a precios accesibles</p>
          </div>
        </div>
      </div>

      <!-- Promociones iniciales -->
      <div class="row">
        <?php if (count($promocionesIniciales) > 0): ?>
          <?php foreach ($promocionesIniciales as $promo): ?>
            <div class="col-lg-6 col-md-6 mb-4">
              <div class="card promotion-item border-0 rounded shadow-sm h-100">
                <div class="position-relative">
                  <img src="img/promociones/<?php echo htmlspecialchars($promo['imagen']); ?>?v=<?php echo $cacheBuster; ?>" 
                       class="card-img-top" alt="<?php echo htmlspecialchars($promo['titulo']); ?>">
                  <div class="promo-badge position-absolute top-0 end-0 m-3 px-3 py-2 bg-primary text-white rounded">
                    <?php echo htmlspecialchars($promo['porcentaje_descuento']); ?>% DESCUENTO
                  </div>
                </div>
                <div class="card-body">
                  <h4 class="card-title text-primary"><?php echo htmlspecialchars($promo['titulo']); ?></h4>
                  <p class="card-text"><?php echo htmlspecialchars($promo['descripcion']); ?></p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <p class="mb-0 text-decoration-line-through text-muted">S/<?php echo number_format($promo['precio_regular'], 2); ?></p>
                      <p class="text-primary fw-bold fs-4 mb-0">S/<?php echo number_format($promo['precio_oferta'], 2); ?></p>
                    </div>
                    <a href="<?php echo htmlspecialchars($promo['enlace_boton']); ?>" class="btn btn-outline-primary">
                      <?php echo htmlspecialchars($promo['texto_boton']); ?>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Si no hay promociones iniciales -->
          <div class="col-12 text-center mb-4">
            <p>No hay promociones disponibles en este momento.</p>
          </div>
        <?php endif; ?>
      </div>

      <!-- Promociones adicionales (inicialmente ocultas) -->
      <div id="promocionesAdicionales">
        <div class="row">
          <?php if (count($promocionesAdicionales) > 0): ?>
            <?php foreach ($promocionesAdicionales as $promo): ?>
              <div class="col-lg-6 col-md-6 mb-4">
                <div class="card promotion-item border-0 rounded shadow-sm h-100">
                  <div class="position-relative">
                    <img src="img/promociones/<?php echo htmlspecialchars($promo['imagen']); ?>?v=<?php echo $cacheBuster; ?>" 
                         class="card-img-top" alt="<?php echo htmlspecialchars($promo['titulo']); ?>">
                    <div class="promo-badge position-absolute top-0 end-0 m-3 px-3 py-2 bg-primary text-white rounded">
                      <?php echo htmlspecialchars($promo['porcentaje_descuento']); ?>% DESCUENTO
                    </div>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title text-primary"><?php echo htmlspecialchars($promo['titulo']); ?></h4>
                    <p class="card-text"><?php echo htmlspecialchars($promo['descripcion']); ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <p class="mb-0 text-decoration-line-through text-muted">S/<?php echo number_format($promo['precio_regular'], 2); ?></p>
                        <p class="text-primary fw-bold fs-4 mb-0">S/<?php echo number_format($promo['precio_oferta'], 2); ?></p>
                      </div>
                      <a href="<?php echo htmlspecialchars($promo['enlace_boton']); ?>" class="btn btn-outline-primary">
                        <?php echo htmlspecialchars($promo['texto_boton']); ?>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Botón para ver más promociones -->
      <?php if (count($promocionesAdicionales) > 0): ?>
      <div class="row mt-4">
        <div class="col-md-12 text-center">
          <button id="btnVerMasPromociones" class="btn btn-primary btn-ver-mas">
            <span id="btnTexto">Ver Todas las Promociones</span> <i class="fas fa-chevron-down ms-2"></i>
          </button>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Footer mejorado -->
  <footer id="footer" class="footer bg-dark text-white pt-5 pb-3">
    <div class="footer-main">
      <div class="container">
        <div class="row justify-content-between">
          <!-- Sobre la Clínica -->
          <div class="col-lg-4 col-md-6 footer-widget footer-about mb-4">
            <h3 class="widget-title border-bottom pb-2 mb-3">Sobre Nosotros</h3>
            <img loading="lazy" class="footer-logo mb-3" src="img/iconoClinica.jpg" alt="Clínica"
              style="max-height: 80px; border-radius: 5px;">
            <p>Somos una clínica comprometida con su salud y bienestar. Contamos con los mejores especialistas médicos y
              la tecnología más avanzada para brindarle una atención personalizada y de calidad.</p>
            <div class="footer-social mt-3">
              <ul class="list-inline">
                <li class="list-inline-item"><a href="https://facebook.com/clinica" class="btn btn-outline-light btn-sm"
                    aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                <li class="list-inline-item"><a href="https://twitter.com/clinica" class="btn btn-outline-light btn-sm"
                    aria-label="Twitter"><i class="fab fa-twitter"></i></a></li>
                <li class="list-inline-item"><a href="https://instagram.com/clinica"
                    class="btn btn-outline-light btn-sm" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </li>
                <li class="list-inline-item"><a href="https://youtube.com/clinica" class="btn btn-outline-light btn-sm"
                    aria-label="YouTube"><i class="fab fa-youtube"></i></a></li>
              </ul>
            </div>
          </div>

          <!-- Información de Contacto -->
          <div class="col-lg-4 col-md-6 footer-widget mt-md-0 mb-4">
            <h3 class="widget-title border-bottom pb-2 mb-3">Contáctenos</h3>
            <div class="contact-info">
              <p><i class="fas fa-map-marker-alt me-2"></i> Av. Principal 123, Ciudad</p>
              <p><i class="fas fa-phone-alt me-2"></i> Teléfono: <a href="tel:+123456789" class="text-white">+12 345
                  6789</a></p>
              <p><i class="fas fa-envelope me-2"></i> Email: <a href="mailto:contacto@clinica.com"
                  class="text-white">contacto@clinica.com</a></p>
              <p><i class="fas fa-clock me-2"></i> Lunes - Viernes: 08:00 - 18:00</p>
              <p class="ms-4">Sábado: 09:00 - 14:00</p>
              <p class="ms-4">Domingo y feriados: Cerrado</p>
            </div>
          </div>

          <!-- Servicios y Enlaces Rápidos -->
          <div class="col-lg-3 col-md-6 mt-lg-0 mb-4 footer-widget">
            <h3 class="widget-title border-bottom pb-2 mb-3">Servicios</h3>
            <ul class="list-unstyled">
              <li class="mb-2"><a href="#consultas" class="text-white text-decoration-none"><i
                    class="fas fa-angle-right me-2"></i>Consultas Médicas</a></li>
              <li class="mb-2"><a href="#emergencias" class="text-white text-decoration-none"><i
                    class="fas fa-angle-right me-2"></i>Emergencias 24/7</a></li>
              <li class="mb-2"><a href="#laboratorio" class="text-white text-decoration-none"><i
                    class="fas fa-angle-right me-2"></i>Laboratorio Clínico</a></li>
              <li class="mb-2"><a href="#cirugia" class="text-white text-decoration-none"><i
                    class="fas fa-angle-right me-2"></i>Cirugías Especializadas</a></li>
              <li class="mb-2"><a href="#rehabilitacion" class="text-white text-decoration-none"><i
                    class="fas fa-angle-right me-2"></i>Rehabilitación</a></li>
              <li class="mb-2"><a href="#imagenologia" class="text-white text-decoration-none"><i
                    class="fas fa-angle-right me-2"></i>Diagnóstico por Imagen</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Copyright y enlaces secundarios -->
    <div class="copyright mt-4 pt-3 border-top border-secondary">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 text-center text-md-start">
            <div class="copyright-info">
              <span>&copy;
                <script>document.write(new Date().getFullYear())</script> Clínica. Todos los derechos reservados.
              </span>
            </div>
          </div>

          <div class="col-md-6 text-center text-md-end">
            <div class="footer-menu">
              <ul class="list-inline mb-0">
                <li class="list-inline-item"><a href="#" class="text-white text-decoration-none">Política de
                    Privacidad</a></li>
                <li class="list-inline-item"><a href="#" class="text-white text-decoration-none">Términos de Uso</a>
                </li>
                <li class="list-inline-item"><a href="#" class="text-white text-decoration-none">Accesibilidad</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts de Bootstrap y FontAwesome -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  
  <!-- Script para actualizar automáticamente las imágenes del carrusel -->
  <script>
    // Función para actualizar las imágenes con un nuevo parámetro de timestamp
    function refreshCarouselImages() {
      const carouselImages = document.querySelectorAll('.carousel-image');
      const timestamp = new Date().getTime(); // Obtener un timestamp único
      
      carouselImages.forEach(function(img) {
        const originalSrc = img.getAttribute('data-src');
        if (originalSrc) {
          img.src = originalSrc + '?v=' + timestamp;
        }
      });
      
      // También actualizar las animaciones del carrusel
      const captions = document.querySelectorAll('.carousel-caption h3, .carousel-caption p, .carousel-caption a');
      captions.forEach(function(element) {
        // Reiniciar animaciones eliminando y volviendo a agregar las clases de animación
        const animationClass = Array.from(element.classList).find(c => c.startsWith('animate__'));
        if (animationClass) {
          element.classList.remove(animationClass);
          // Forzar un reflow
          void element.offsetWidth;
          // Agregar de nuevo la clase para reiniciar la animación
          element.classList.add(animationClass);
        }
      });
    }
    
    // Actualizar las imágenes del carrusel cada 30 segundos
    setInterval(refreshCarouselImages, 30000);
    
    // También actualizar las imágenes al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
      refreshCarouselImages();
      
      // Actualizar también cuando se cambia de slide en el carrusel
      const carousel = document.getElementById('carouselExampleIndicators');
      if (carousel) {
        carousel.addEventListener('slide.bs.carousel', function() {
          refreshCarouselImages();
        });
      }
      
      // Gestionar el botón "Ver Todas las Promociones"
      const btnVerMas = document.getElementById('btnVerMasPromociones');
      const promocionesAdicionales = document.getElementById('promocionesAdicionales');
      const btnTexto = document.getElementById('btnTexto');
      
      if (btnVerMas && promocionesAdicionales) {
        btnVerMas.addEventListener('click', function() {
          if (promocionesAdicionales.style.display === 'block') {
            // Ocultar promociones adicionales
            promocionesAdicionales.style.display = 'none';
            btnTexto.textContent = 'Ver Todas las Promociones';
            btnVerMas.classList.remove('active');
            
            // Hacer scroll hacia la sección de promociones
            document.getElementById('promociones').scrollIntoView({ behavior: 'smooth' });
          } else {
            // Mostrar promociones adicionales
            promocionesAdicionales.style.display = 'block';
            btnTexto.textContent = 'Ver Menos Promociones';
            btnVerMas.classList.add('active');
            
            // Hacer scroll hacia la primera promoción adicional
            promocionesAdicionales.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      }
    });
  </script>
</body>

</html>