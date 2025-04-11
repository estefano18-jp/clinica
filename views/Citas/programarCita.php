<?php 
require_once '../include/header.administrador.php'; 

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="../../css/programarcita.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="appointment-form">
                    <div class="header-section text-center">
                        <h2><i class="fas fa-stethoscope me-2"></i>Agendar Cita Médica</h2>
                        <p class="mb-0">Complete el formulario para programar su consulta</p>
                        
                        <!-- Contenedor para mensajes de alerta -->
                        <div id="alertContainer"></div>
                    </div>
                    
                    <form id="citaForm">
                        <div class="mb-4">
                            <label for="especialidad" class="form-label">
                                <i class="fas fa-user-md me-2"></i>Especialidad Médica
                            </label>
                            <select id="especialidad" name="especialidad" class="form-select" onchange="actualizarDoctores()">
                                <option value="">Seleccione una especialidad...</option>
                                <?php foreach($especialidades as $especialidad): ?>
                                    <option value="<?php echo $especialidad['idespecialidad']; ?>">
                                        <?php echo htmlspecialchars($especialidad['especialidad']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="doctor" class="form-label">
                                <i class="fas fa-id-card-alt me-2"></i>Doctor
                            </label>
                            <select id="doctor" name="doctor" class="form-select" disabled>
                                <option value="">Seleccione especialidad primero...</option>
                            </select>
                            <div id="doctorInfo" class="doctor-card mt-2" style="display:none;">
                                <p class="mb-0" id="doctorDesc"></p>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="fecha" class="form-label">
                                    <i class="fas fa-calendar-alt me-2"></i>Fecha
                                </label>
                                <input type="date" id="fecha" name="fecha" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="horario" class="form-label">
                                    <i class="fas fa-clock me-2"></i>Horario
                                </label>
                                <select id="horario" name="horario" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <option value="08:00 - 09:00">08:00 - 09:00</option>
                                    <option value="09:00 - 10:00">09:00 - 10:00</option>
                                    <option value="10:00 - 11:00">10:00 - 11:00</option>
                                    <option value="11:00 - 12:00">11:00 - 12:00</option>
                                    <option value="14:00 - 15:00">14:00 - 15:00</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-user me-2"></i>Nombre del Paciente
                            </label>
                            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingrese su nombre completo" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="telefono" class="form-label">
                                <i class="fas fa-phone me-2"></i>Teléfono de Contacto
                            </label>
                            <input type="tel" id="telefono" name="telefono" class="form-control" 
                                   placeholder="Ingrese su número de teléfono" 
                                   pattern="[0-9]{10}" 
                                   title="Ingrese un número de teléfono de 10 dígitos" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-lg" onclick="agendarCita()">
                                <i class="fas fa-calendar-check me-2"></i>Agendar Cita
                            </button>
                        </div>
                    </form>
                    
                    <div id="confirmacion" class="confirmation-message mt-4" style="display:none;">
                        <div class="alert alert-success">
                            <h4><i class="fas fa-check-circle me-2"></i>¡Cita Agendada con Éxito!</h4>
                            <div id="confirmacionDetalles"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Establecer fecha mínima
        window.onload = function() {
            const hoy = new Date().toISOString().split('T')[0];
            document.getElementById("fecha").setAttribute("min", hoy);
        };

        function mostrarAlerta(mensaje, tipo = 'danger') {
            const alertContainer = document.getElementById('alertContainer');
            const alertHTML = `
                <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${tipo === 'danger' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
                    ${mensaje}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            alertContainer.innerHTML = alertHTML;
        }

        function actualizarDoctores() {
            const especialidadId = document.getElementById("especialidad").value;
            const doctorSelect = document.getElementById("doctor");
            const doctorInfo = document.getElementById("doctorInfo");

            // Resetear select de doctores
            doctorSelect.innerHTML = '<option value="">Seleccione un doctor...</option>';
            doctorSelect.disabled = true;
            doctorInfo.style.display = 'none';

            if (!especialidadId) return;

            // Llamada AJAX para obtener doctores
            fetch('/citas/obtenerDoctores', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'idespecialidad=' + especialidadId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.doctores.length > 0) {
                    doctorSelect.disabled = false;
                    data.doctores.forEach(doctor => {
                        const option = document.createElement("option");
                        option.value = doctor.idmedico;
                        option.textContent = ${doctor.nombre} ${doctor.apellido};
                        doctorSelect.appendChild(option);
                    });
                } else {
                    mostrarAlerta('No hay doctores disponibles para esta especialidad', 'warning');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al cargar los doctores');
            });
        }

        // Mostrar información del doctor
        document.getElementById("doctor").addEventListener("change", function() {
            const doctorSeleccionado = this.value;
            const especialidadId = document.getElementById("especialidad").value;
            const doctorInfo = document.getElementById("doctorInfo");
            const doctorDesc = document.getElementById("doctorDesc");
            
            if (doctorSeleccionado) {
                // En un escenario real, harías otra llamada AJAX para obtener detalles del doctor
                doctorDesc.textContent = "Descripción del doctor..."; // Placeholder
                doctorInfo.style.display = "block";
            } else {
                doctorInfo.style.display = "none";
            }
        });

        function agendarCita() {
            const form = document.getElementById('citaForm');
            
            // Validación del formulario
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            fetch('/citas/guardarCita', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar confirmación
                    const confirmacion = document.getElementById('confirmacion');
                    const confirmacionDetalles = document.getElementById('confirmacionDetalles');
                    
                    confirmacionDetalles.innerHTML = `
                        <p><strong>Especialidad:</strong> ${document.getElementById('especialidad').options[document.getElementById('especialidad').selectedIndex].text}</p>
                        <p><strong>Doctor:</strong> ${document.getElementById('doctor').options[document.getElementById('doctor').selectedIndex].text}</p>
                        <p><strong>Fecha:</strong> ${document.getElementById('fecha').value}</p>
                        <p><strong>Horario:</strong> ${document.getElementById('horario').value}</p>
                        <p><strong>Paciente:</strong> ${document.getElementById('nombre').value}</p>
                        <p><strong>Teléfono:</strong> ${document.getElementById('telefono').value}</p>
                    `;
                    
                    confirmacion.style.display = 'block';
                    
                    // Limpiar formulario
                    form.reset();
                    document.getElementById('doctor').disabled = true;
                    document.getElementById('doctorInfo').style.display = 'none';
                    
                    // Hacer scroll a la confirmación
                    confirmacion.scrollIntoView({behavior: 'smooth'});
                } else {
                    mostrarAlerta(data.mensaje);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al agendar la cita');
            });
        }
    </script>
</body>
</html>