<?php require_once '../include/header.administrador.php'; ?>
<?php
/**
 * Footer para el área de administración
 * Este archivo debe ser incluido al final de todas las páginas de administración
 */

// Verificar que este archivo no sea accedido directamente
if (!defined('BASE_PATH')) {
    // Si alguien intenta acceder directamente a este archivo, redirigir al login
    header('Location: ../../login.php');
    exit;
}
?>

    </div>
    <!-- Fin del contenido principal -->
  </div>
  <!-- Fin del contenedor principal -->

  <!-- Footer -->
  <footer class="footer bg-light mt-auto py-3 border-top">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6 text-center text-md-start">
          <span class="text-muted">&copy; <?php echo date('Y'); ?> Clínica Médica. Todos los derechos reservados.</span>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <span class="text-muted">Panel de Administración v1.0</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- jQuery (necesario para algunas funcionalidades avanzadas) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- FontAwesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  
  <!-- SweetAlert2 para alertas y confirmaciones más atractivas -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <!-- DataTables para tablas con paginación y búsqueda -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  
  <!-- Scripts personalizados para el área de administración -->
  <script src="../js/admin.js"></script>
  
  <!-- Script para inicializar DataTables en todas las tablas con la clase .dataTable -->
  <script>
    $(document).ready(function() {
      // Inicializar DataTables si existe alguna tabla con la clase dataTable
      if ($('.dataTable').length > 0) {
        $('.dataTable').DataTable({
          language: {
            url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
          },
          responsive: true,
          pageLength: 10,
          lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]]
        });
      }
      
      // Inicializar tooltips de Bootstrap
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
      
      // Mostrar mensajes flash con SweetAlert2 si existen
      <?php if (isset($_SESSION['mensaje']) && isset($_SESSION['tipo_mensaje'])): ?>
        Swal.fire({
          icon: '<?php echo $_SESSION['tipo_mensaje']; ?>',
          title: '<?php echo $_SESSION['titulo_mensaje'] ?? 'Notificación'; ?>',
          text: '<?php echo $_SESSION['mensaje']; ?>',
          timer: 3000,
          timerProgressBar: true
        });
        <?php 
        // Limpiar los mensajes de sesión para que no se muestren nuevamente
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
        unset($_SESSION['titulo_mensaje']);
        ?>
      <?php endif; ?>
      
      // Confirmar eliminación con SweetAlert2 para formularios con clase confirm-delete
      $('.confirm-delete').on('submit', function(e) {
        var form = this;
        e.preventDefault();
        Swal.fire({
          title: '¿Está seguro?',
          text: "Esta acción no se puede deshacer",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  </script>
  
  <!-- Espacio para scripts adicionales específicos de cada página -->
  <?php if (isset($scripts_adicionales)) echo $scripts_adicionales; ?>
</body>
</html>
<?php require_once '../include/footer.php'; ?>