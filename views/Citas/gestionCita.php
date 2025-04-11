<?php require_once '../include/header.administrador.php'; ?>

<!-- Contenido principal de la página -->
<div class="container">
    <h1 class="page-title">Gestión de Citas</h1>

    <!-- Formulario o sección para añadir nuevas citas (opcional) -->
    <div class="add-cita">
        <a href="agregarCita.php" class="btn btn-primary">Agregar Nueva Cita</a>
    </div>

    <!-- Tabla de citas (Vista estática) -->
    <div class="citas-list">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Fila de ejemplo 1 -->
                <tr>
                    <td>1</td>
                    <td>Juan Pérez</td>
                    <td>2025-03-30</td>
                    <td>10:00</td>
                    <td id="estado-1">Programada</td>
                    <td>
                        <button class="btn btn-warning" onclick="toggleEstado(1)">Cancelar Cita</button>
                        <a href="editarCita.php?id=1" class="btn btn-info">Editar</a>
                        <a href="eliminarCita.php?id=1" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>

                <!-- Fila de ejemplo 2 -->
                <tr>
                    <td>2</td>
                    <td>Ana García</td>
                    <td>2025-04-01</td>
                    <td>14:30</td>
                    <td id="estado-2">Cancelada</td>
                    <td>
                        <button class="btn btn-warning" onclick="toggleEstado(2)">Cancelar Cita</button>
                        <a href="editarCita.php?id=2" class="btn btn-info">Editar</a>
                        <a href="eliminarCita.php?id=2" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>

                <!-- Fila de ejemplo 3 -->
                <tr>
                    <td>3</td>
                    <td>Pedro Rodríguez</td>
                    <td>2025-04-05</td>
                    <td>09:45</td>
                    <td id="estado-3">Programada</td>
                    <td>
                        <button class="btn btn-warning" onclick="toggleEstado(3)">Cancelar Cita</button>
                        <a href="editarCita.php?id=3" class="btn btn-info">Editar</a>
                        <a href="eliminarCita.php?id=3" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Estilos CSS -->
<style>
    /* Contenedor principal */
    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Título de la página */
    .page-title {
        text-align: center;
        color: #333;
        font-size: 2em;
        margin-bottom: 20px;
    }

    /* Estilos de la tabla */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .table th, .table td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #007bff;
        color: #fff;
    }

    .table td {
        background-color: #f9f9f9;
    }

    /* Botones de acción */
    .btn {
        padding: 8px 16px;
        border-radius: 5px;
        text-decoration: none;
        color: #fff;
        font-size: 14px;
        margin: 5px;
    }

    .btn-primary {
        background-color: #007bff;
    }

    .btn-info {
        background-color: #17a2b8;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-warning {
        background-color: #ffcc00;
    }

    /* Estilo para agregar nueva cita */
    .add-cita {
        text-align: right;
        margin-bottom: 20px;
    }
</style>

<!-- Script para alternar estado de la cita (ON/OFF) -->
<script>
    function toggleEstado(id) {
        const estadoElement = document.getElementById(`estado-${id}`);
        const estadoActual = estadoElement.innerText;

        if (estadoActual === "Programada") {
            estadoElement.innerText = "Cancelada";
            // Aquí deberías hacer la actualización de la base de datos mediante AJAX o una llamada POST para cambiar el estado de la cita
            // Ejemplo: actualizarEstadoCita(id, "Cancelada");
        } else {
            estadoElement.innerText = "Programada";
            // Actualización de la base de datos mediante AJAX o POST para restaurar el estado a Programada
            // Ejemplo: actualizarEstadoCita(id, "Programada");
        }
    }

    // Función para actualizar el estado de la cita en la base de datos (ejemplo)
    function actualizarEstadoCita(id, estado) {
        // Usar AJAX para enviar el cambio al servidor y actualizar la base de datos
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "actualizar_estado_cita.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(`id=${id}&estado=${estado}`);

        xhr.onload = function() {
            if (xhr.status == 200) {
                alert("Estado actualizado correctamente");
            } else {
                alert("Hubo un error al actualizar el estado");
            }
        };
    }
</script>

<?php require_once '../include/footer.php'; ?>
