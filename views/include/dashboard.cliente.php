<!-- views/include/dashboard.cliente.php -->
<?php 
require_once 'header.cliente.php'; 

// Verificar que la sesión del cliente está activa
if (!isset($_SESSION['login']) || !$_SESSION['login']['permitido']) {
    header('Location: /AlquilerCampoDeportivo/index.php'); // Redirigir al inicio de sesión
    exit();
}

// Obtener los datos del cliente desde la sesión
$usuarioNombre = $_SESSION['login']['nombre'] ?? 'Cliente';
$usuarioApellido = $_SESSION['login']['apellido'] ?? '';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Bienvenido</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Inicio</li>
    </ol>

    <!-- Bienvenida al cliente -->
    <div class="alert alert-success text-center" role="alert">
        ¡Hola, <?= htmlspecialchars($usuarioNombre) . ' ' . htmlspecialchars($usuarioApellido) ?>! 
        Estamos encantados de que uses nuestro sistema. Explora y reserva tu cancha favorita.
    </div>

    <!-- Opciones para el cliente -->
    <div class="text-center">
        <p>¿Qué deseas hacer hoy?</p>
        <a href="../ClienteReserva/seleccionReserva.php" class="btn btn-primary m-2">Reservar una cancha</a>
        <a href="../HistorialReservaCliente/index.php" class="btn btn-secondary m-2">Ver mi historial</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
