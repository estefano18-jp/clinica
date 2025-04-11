<?php require_once '../../include/header.administrador.php'; ?>

<head>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="../../../css/registrarDoctor.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="text-center mb-3">Registro de Doctor</h2>
                <div id="doctorNameDisplay" class="text-center mb-3 d-none">
                    <span class="badge bg-primary p-3 fs-5">
                        <i class="fas fa-user-md me-2"></i> <span id="doctorNameText" style="font-size: 18px; font-weight: bold;">Nombre del doctor</span>
                    </span>
                </div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <form id="doctorRegistrationForm" method="POST" action="procesarRegistroDoctor.php" enctype="multipart/form-data">
            <!-- Información Personal -->
            <div class="card" id="personalInfoCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-user-md me-2"></i> Información del Doctor
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Datos Personales</h5>
                        </div>
                    </div>

                    <!-- Tipo y Número de Documento -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tipodoc" class="form-label required-field">Tipo de Documento</label>
                            <select class="form-select" id="tipodoc" name="tipodoc" required>
                                <option value="">Seleccione...</option>
                                <option value="DNI">DNI</option>
                                <option value="PASAPORTE">Pasaporte</option>
                                <option value="CARNET DE EXTRANJERIA">Carnet de Extranjería</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="nrodoc" class="form-label required-field">Número de Documento</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="nrodoc" name="nrodoc" required>
                                <button type="button" class="btn btn-primary" id="btnBuscarDocumento">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="fechanacimiento" class="form-label required-field">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fechanacimiento" name="fechanacimiento" required>
                        </div>
                    </div>

                    <!-- Nombres y Apellidos -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="apellidos" class="form-label required-field">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nombres" class="form-label required-field">Nombres</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" required>
                        </div>
                    </div>

                    <!-- Género, Teléfono, Email -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="genero" class="form-label required-field">Género</label>
                            <select class="form-select" id="genero" name="genero" required>
                                <option value="">Seleccione...</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="telefono" class="form-label required-field">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label required-field">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="direccion" class="form-label required-field">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                    </div>

                    <!-- Datos Profesionales -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Datos Profesionales</h5>
                        </div>
                    </div>

                    <!-- Especialidad y Precio de Atención -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="especialidad" class="form-label required-field">Especialidad</label>
                            <div class="input-group">
                                <select class="form-select" id="especialidad" name="especialidad" required>
                                    <option value="">Seleccione...</option>
                                    <!-- Aquí se cargan dinámicamente las especialidades -->
                                </select>
                                <button type="button" class="btn btn-primary" id="btnAgregarEspecialidad" disabled>
                                    <i class="fas fa-plus"></i> Agregar Especialidad
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="precioatencion" class="form-label required-field">Precio de Atención</label>
                            <div class="input-group">
                                <span class="input-group-text">S/.</span>
                                <input type="number" class="form-control" id="precioatencion" name="precioatencion" step="0.01" min="0" required readonly>
                                <button type="button" class="btn btn-primary" id="btnEditarPrecio" disabled>
                                    <i class="fas fa-edit"></i> Editar Precio
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de navegación -->
                    <div class="row mb-0">
                        <div class="col-md-12">
                            <div class="action-buttons text-end">
                                <button type="button" id="btnSiguiente" class="btn btn-primary" onclick="showTab('contrato')">Siguiente <i class="fas fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Esta sección reemplazaría las tres tarjetas separadas (contratoCard, horarioCard y credencialesCard) -->
            <div class="card d-none" id="informacionComplementariaCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-file-contract me-2"></i> Información Complementaria del Doctor
                    </div>
                </div>
                <div class="card-body">
                    <!-- Sección de Contrato -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Información de Contrato</h5>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="tipocontrato" class="form-label required-field">Tipo de Contrato</label>
                            <select class="form-select" id="tipocontrato" name="tipocontrato" required>
                                <option value="">Seleccione...</option>
                                <option value="INDEFINIDO">Indefinido</option>
                                <option value="PLAZO FIJO">Plazo Fijo</option>
                                <option value="TEMPORAL">Temporal</option>
                                <option value="EVENTUAL">Eventual</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="fechainicio" class="form-label required-field">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fechainicio" name="fechainicio" required>
                        </div>
                        <div class="col-md-4">
                            <label for="fechafin" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fechafin" name="fechafin">
                            <small class="text-muted">Opcional para contratos indefinidos</small>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-success mb-3" id="btnConfirmarContrato">
                                <i class="fas fa-check me-1"></i> Confirmar Contrato y Activar Horarios
                            </button>
                        </div>
                    </div>

                    <!-- Sección de Horario de Atención -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Horario de Atención</h5>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i> Configuración de Horarios</h5>
                                <p>Establezca los días y horarios en que el doctor atenderá en la clínica.</p>
                                <p><strong>Medio tiempo:</strong> Un solo horario (mañana o tarde)</p>
                                <p><strong>Tiempo completo:</strong> Dos horarios (mañana y tarde)</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 12%">Día</th>
                                            <th style="width: 8%">Atiende</th>
                                            <th style="width: 15%">Modalidad</th>
                                            <th style="width: 65%">Horarios</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Lunes -->
                                        <tr>
                                            <td>Lunes</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input dia-atencion" type="checkbox" id="atiendeLunes" name="atiende[LUNES]" onchange="toggleHorario('Lunes')">
                                                </div>
                                            </td>
                                            <td>
                                                <select class="form-select modalidad-select" id="modalidadLunes" name="modalidad[LUNES]" disabled onchange="toggleModalidad('Lunes')">
                                                    <option value="medioTiempo" selected>Medio tiempo</option>
                                                    <option value="tiempoCompleto">Tiempo completo</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="row mb-2 horario-manana">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioLunes1" name="horainicio[LUNES][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinLunes1" name="horafin[LUNES][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row horario-tarde d-none">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioLunes2" name="horainicio[LUNES][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinLunes2" name="horafin[LUNES][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Martes -->
                                        <tr>
                                            <td>Martes</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input dia-atencion" type="checkbox" id="atiendeMartes" name="atiende[MARTES]" onchange="toggleHorario('Martes')">
                                                </div>
                                            </td>
                                            <td>
                                                <select class="form-select modalidad-select" id="modalidadMartes" name="modalidad[MARTES]" disabled onchange="toggleModalidad('Martes')">
                                                    <option value="medioTiempo" selected>Medio tiempo</option>
                                                    <option value="tiempoCompleto">Tiempo completo</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="row mb-2 horario-manana">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioMartes1" name="horainicio[MARTES][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinMartes1" name="horafin[MARTES][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row horario-tarde d-none">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioMartes2" name="horainicio[MARTES][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinMartes2" name="horafin[MARTES][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Miércoles -->
                                        <tr>
                                            <td>Miércoles</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input dia-atencion" type="checkbox" id="atiendeMiercoles" name="atiende[MIERCOLES]" onchange="toggleHorario('Miercoles')">
                                                </div>
                                            </td>
                                            <td>
                                                <select class="form-select modalidad-select" id="modalidadMiercoles" name="modalidad[MIERCOLES]" disabled onchange="toggleModalidad('Miercoles')">
                                                    <option value="medioTiempo" selected>Medio tiempo</option>
                                                    <option value="tiempoCompleto">Tiempo completo</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="row mb-2 horario-manana">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioMiercoles1" name="horainicio[MIERCOLES][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinMiercoles1" name="horafin[MIERCOLES][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row horario-tarde d-none">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioMiercoles2" name="horainicio[MIERCOLES][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinMiercoles2" name="horafin[MIERCOLES][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Jueves -->
                                        <tr>
                                            <td>Jueves</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input dia-atencion" type="checkbox" id="atiendeJueves" name="atiende[JUEVES]" onchange="toggleHorario('Jueves')">
                                                </div>
                                            </td>
                                            <td>
                                                <select class="form-select modalidad-select" id="modalidadJueves" name="modalidad[JUEVES]" disabled onchange="toggleModalidad('Jueves')">
                                                    <option value="medioTiempo" selected>Medio tiempo</option>
                                                    <option value="tiempoCompleto">Tiempo completo</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="row mb-2 horario-manana">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioJueves1" name="horainicio[JUEVES][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinJueves1" name="horafin[JUEVES][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row horario-tarde d-none">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioJueves2" name="horainicio[JUEVES][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinJueves2" name="horafin[JUEVES][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Viernes -->
                                        <tr>
                                            <td>Viernes</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input dia-atencion" type="checkbox" id="atiendeViernes" name="atiende[VIERNES]" onchange="toggleHorario('Viernes')">
                                                </div>
                                            </td>
                                            <td>
                                                <select class="form-select modalidad-select" id="modalidadViernes" name="modalidad[VIERNES]" disabled onchange="toggleModalidad('Viernes')">
                                                    <option value="medioTiempo" selected>Medio tiempo</option>
                                                    <option value="tiempoCompleto">Tiempo completo</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="row mb-2 horario-manana">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioViernes1" name="horainicio[VIERNES][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinViernes1" name="horafin[VIERNES][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row horario-tarde d-none">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioViernes2" name="horainicio[VIERNES][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinViernes2" name="horafin[VIERNES][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Sábado -->
                                        <tr>
                                            <td>Sábado</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input dia-atencion" type="checkbox" id="atiendeSabado" name="atiende[SABADO]" onchange="toggleHorario('Sabado')">
                                                </div>
                                            </td>
                                            <td>
                                                <select class="form-select modalidad-select" id="modalidadSabado" name="modalidad[SABADO]" disabled onchange="toggleModalidad('Sabado')">
                                                    <option value="medioTiempo" selected>Medio tiempo</option>
                                                    <option value="tiempoCompleto">Tiempo completo</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="row mb-2 horario-manana">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioSabado1" name="horainicio[SABADO][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinSabado1" name="horafin[SABADO][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row horario-tarde d-none">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioSabado2" name="horainicio[SABADO][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinSabado2" name="horafin[SABADO][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Domingo -->
                                        <tr>
                                            <td>Domingo</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input dia-atencion" type="checkbox" id="atiendeDomingo" name="atiende[DOMINGO]" onchange="toggleHorario('Domingo')">
                                                </div>
                                            </td>
                                            <td>
                                                <select class="form-select modalidad-select" id="modalidadDomingo" name="modalidad[DOMINGO]" disabled onchange="toggleModalidad('Domingo')">
                                                    <option value="medioTiempo" selected>Medio tiempo</option>
                                                    <option value="tiempoCompleto">Tiempo completo</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="row mb-2 horario-manana">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioDomingo1" name="horainicio[DOMINGO][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinDomingo1" name="horafin[DOMINGO][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row horario-tarde d-none">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Desde</span>
                                                            <input type="time" class="form-control" id="horaInicioDomingo2" name="horainicio[DOMINGO][]" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Hasta</span>
                                                            <input type="time" class="form-control" id="horaFinDomingo2" name="horafin[DOMINGO][]" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Sección de Credenciales de Acceso -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Credenciales de Acceso</h5>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="nomuser" class="form-label required-field">Nombre de Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="nomuser" name="nomuser" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="passuser" class="form-label required-field">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="passuser" name="passuser" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-12">
                            <div class="action-buttons">
                                <button type="button" class="btn btn-secondary" onclick="showTab('personalInfo')"><i class="fas fa-arrow-left me-1"></i> Anterior</button>
                                <button type="button" class="btn btn-primary" onclick="showTab('confirmacion')">Siguiente <i class="fas fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Confirmación -->
            <div class="card d-none" id="confirmacionCard">
                <div class="card-header">
                    <i class="fas fa-check-circle me-2"></i> Confirmación
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i> Revisar Información</h5>
                                <p>Por favor, revise que todos los datos ingresados sean correctos antes de guardar.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-12">
                            <div id="resumenRegistro" class="mb-4">
                                <!-- Aquí se mostrará el resumen de los datos ingresados -->
                            </div>

                            <div class="action-buttons">
                                <button type="button" class="btn btn-secondary" onclick="showTab('credenciales')"><i class="fas fa-arrow-left me-1"></i> Anterior</button>
                                <button type="button" class="btn btn-danger me-2" onclick="resetForm()"><i class="fas fa-times me-1"></i> Cancelar</button>
                                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Modal para agregar especialidad -->
    <div class="modal fade" id="modalEspecialidad" tabindex="-1" aria-labelledby="modalEspecialidadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEspecialidadLabel">Agregar Nueva Especialidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEspecialidad">
                        <div class="mb-3">
                            <label for="nombreEspecialidad" class="form-label">Nombre de Especialidad</label>
                            <input type="text" class="form-control" id="nombreEspecialidad" required>
                        </div>
                        <div class="mb-3">
                            <label for="precioEspecialidad" class="form-label">Precio de Atención</label>
                            <div class="input-group">
                                <span class="input-group-text">S/.</span>
                                <input type="number" class="form-control" id="precioEspecialidad" step="0.01" min="0" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarEspecialidad">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para editar precio de atención -->
    <div class="modal fade" id="modalEditarPrecio" tabindex="-1" aria-labelledby="modalEditarPrecioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarPrecioLabel">Editar Precio de Atención</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarPrecio">
                        <div class="mb-3">
                            <label for="especialidadActual" class="form-label">Especialidad</label>
                            <input type="text" class="form-control" id="especialidadActual" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="nuevoPrecioAtencion" class="form-label">Nuevo Precio de Atención</label>
                            <div class="input-group">
                                <span class="input-group-text">S/.</span>
                                <input type="number" class="form-control" id="nuevoPrecioAtencion" step="0.01" min="0" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarPrecio">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Variables globales
            let currentTab = 'personalInfo';

            // Configuración de validaciones por tipo de documento
            const documentoConfig = {
                'DNI': {
                    length: 8,
                    pattern: /^\d{8}$/,
                    message: 'El DNI debe tener 8 dígitos numéricos'
                },
                'PASAPORTE': {
                    length: 12,
                    pattern: /^[A-Z0-9]{6,12}$/,
                    message: 'El pasaporte debe tener entre 6 y 12 caracteres alfanuméricos'
                },
                'CARNET DE EXTRANJERIA': {
                    length: 9,
                    pattern: /^[A-Z0-9]{9}$/,
                    message: 'El carnet de extranjería debe tener 9 caracteres alfanuméricos'
                },
                'OTRO': {
                    length: 15,
                    pattern: /^.{1,15}$/,
                    message: 'El documento puede tener hasta 15 caracteres'
                }
            };

            // Inicializar progreso
            updateProgressBar(0);

            // Establecer DNI como valor por defecto para tipo de documento
            document.getElementById("tipodoc").value = "DNI";

            // Configurar maxlength para documento según el tipo por defecto (DNI = 8)
            const nrodocInput = document.getElementById("nrodoc");
            nrodocInput.setAttribute("maxlength", "8");

            // Establecer Masculino como valor por defecto para género
            document.getElementById("genero").value = "M";

            // Establecer fecha máxima para fecha de nacimiento (hoy)
            const today = new Date();
            const formattedDate = today.toISOString().split('T')[0];
            document.getElementById("fechanacimiento").setAttribute("max", formattedDate);

            // Bloquear todos los campos excepto tipo y número de documento
            bloquearCampos();

            // Cargar especialidades desde el servidor
            cargarEspecialidades();

            // Setup eventos de validación para todos los campos
            setupFieldValidations();
            // Asociar evento al select de especialidad para cargar precio automáticamente
            const selectEspecialidad = document.getElementById('especialidad');
            if (selectEspecialidad) {
                selectEspecialidad.addEventListener('change', function() {
                    cargarPrecioEspecialidad(this.value);
                });
            }

            // Asociar evento al botón de agregar especialidad
            const btnAgregarEspecialidad = document.getElementById('btnAgregarEspecialidad');
            if (btnAgregarEspecialidad) {
                btnAgregarEspecialidad.addEventListener('click', function() {
                    // Mostrar modal
                    const modalEspecialidad = new bootstrap.Modal(document.getElementById('modalEspecialidad'));
                    modalEspecialidad.show();
                });
            }

            // Asociar evento al botón de guardar especialidad en el modal
            const btnGuardarEspecialidad = document.getElementById('btnGuardarEspecialidad');
            if (btnGuardarEspecialidad) {
                btnGuardarEspecialidad.addEventListener('click', function() {
                    guardarNuevaEspecialidad();
                });
            }
            // Función para cargar el precio de la especialidad seleccionada
            function cargarPrecioEspecialidad(idEspecialidad) {
                const precioInput = document.getElementById('precioatencion');

                // Asegurar que siempre esté en readonly
                precioInput.readOnly = true;

                if (!idEspecialidad) {
                    precioInput.value = '';
                    return;
                }


                // Buscar la especialidad en el listado y obtener su precio
                fetch(`../../../controllers/especialidad.controller.php?op=obtener&id=${idEspecialidad}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status && data.data) {
                            // Asignar el precio y marcar como válido
                            precioInput.value = data.data.precioatencion;
                            markFieldAsValid(precioInput);
                            removeFieldHelpMessage(precioInput);
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener precio de especialidad:', error);
                    });
            }

            // Función para guardar una nueva especialidad
            function guardarNuevaEspecialidad() {
                const nombreEspecialidad = document.getElementById('nombreEspecialidad').value;
                const precioEspecialidad = document.getElementById('precioEspecialidad').value;

                if (!nombreEspecialidad || !precioEspecialidad) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Campos incompletos',
                        text: 'Por favor, complete todos los campos requeridos.'
                    });
                    return;
                }

                const formData = new FormData();
                formData.append('especialidad', nombreEspecialidad);
                formData.append('precioatencion', precioEspecialidad);

                // Mostrar loader
                Swal.fire({
                    title: 'Guardando...',
                    text: 'Por favor espere mientras se registra la especialidad.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('../../../controllers/especialidad.controller.php?op=registrar', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();

                        if (data.status) {
                            // Cerrar modal
                            bootstrap.Modal.getInstance(document.getElementById('modalEspecialidad')).hide();

                            // Limpiar formulario
                            document.getElementById('nombreEspecialidad').value = '';
                            document.getElementById('precioEspecialidad').value = '';

                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: 'Especialidad registrada',
                                text: 'La especialidad ha sido registrada correctamente.'
                            });

                            // Recargar lista de especialidades
                            cargarEspecialidades(data.idespecialidad);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al registrar',
                                text: data.mensaje || 'No se pudo registrar la especialidad.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al registrar especialidad:', error);
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'No se pudo completar el registro de la especialidad.'
                        });
                    });
            }

            // Setup eventos para mostrar/ocultar contraseña
            document.getElementById("togglePassword").addEventListener("click", function() {
                const passwordInput = document.getElementById("passuser");
                const icon = this.querySelector("i");

                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    passwordInput.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            });

            // Controladores de eventos para cambios en tipo y número de documento
            document.getElementById("tipodoc").addEventListener("change", function() {
                const tipodoc = this.value;
                // Configurar validación según tipo de documento seleccionado
                if (tipodoc && documentoConfig[tipodoc]) {
                    const maxLength = documentoConfig[tipodoc].length;
                    nrodocInput.setAttribute("maxlength", maxLength);
                    console.log(`Tipo de documento cambiado a ${tipodoc}. Longitud máxima: ${maxLength}`);
                }

                // Desbloquear el campo de número de documento
                nrodocInput.disabled = false;
                // Restablecer el número de documento
                nrodocInput.value = "";
                // Volver a bloquear otros campos y botones
                bloquearCampos();
            });

            nrodocInput.addEventListener("input", function() {
                // Al cambiar el número de documento, volver a bloquear campos
                bloquearCampos();

                // Validar formato según tipo de documento
                const tipodoc = document.getElementById('tipodoc').value;
                if (tipodoc && documentoConfig[tipodoc]) {
                    if (this.value && documentoConfig[tipodoc].pattern.test(this.value)) {
                        markFieldAsValid(this);
                        removeFieldHelpMessage(this);
                    } else if (this.value) {
                        markFieldAsInvalid(this);
                        addFieldHelpMessage(this, documentoConfig[tipodoc].message);
                    }
                }
            });

            // Setup botón de búsqueda de documento
            const btnBuscar = document.getElementById("btnBuscarDocumento");
            if (btnBuscar) {
                btnBuscar.addEventListener("click", function(e) {
                    e.preventDefault();
                    buscarDoctorPorDocumento();
                });
            }

            // Eliminar atributos onclick existentes y agregar event listeners
            const btnSiguiente = document.getElementById("btnSiguiente");
            if (btnSiguiente) {
                btnSiguiente.removeAttribute("onclick");
                btnSiguiente.addEventListener("click", function() {
                    // Forzar la validación de todos los campos
                    const requiredFields = [
                        'tipodoc', 'nrodoc', 'apellidos', 'nombres', 'fechanacimiento',
                        'genero', 'telefono', 'email', 'direccion', 'especialidad', 'precioatencion'
                    ];
                    requiredFields.forEach(field => {
                        const input = document.getElementById(field);
                        if (input) {
                            if (!input.value.trim()) {
                                markFieldAsInvalid(input);
                                addFieldHelpMessage(input, 'Este campo es obligatorio');
                            }
                        }
                    });

                    // Realizar validación completa
                    if (validatePersonalInfo() && validateProfessionalInfo()) {
                        // Actualizar el nombre del doctor antes de cambiar de pestaña
                        const apellidos = document.getElementById('apellidos').value;
                        const nombres = document.getElementById('nombres').value;
                        if (apellidos && nombres) {
                            document.getElementById('doctorNameText').textContent = `${nombres} ${apellidos}`;
                        }

                        // Ahora vamos directamente a la pestaña combinada
                        showTab('contrato'); // Usamos 'contrato' para identificar la pestaña combinada
                    } else {
                        // Si hay campos incompletos, mostrar alerta pero no cambiar de pestaña
                        Swal.fire({
                            icon: 'warning',
                            title: 'Campos incompletos',
                            text: 'Por favor, complete todos los campos marcados en rojo para continuar.'
                        });

                        // Hacer scroll al primer campo con error
                        const firstInvalidField = document.querySelector('.is-invalid');
                        if (firstInvalidField) {
                            firstInvalidField.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            firstInvalidField.focus();
                        }
                    }
                });
            }
            // Buscar el botón Siguiente en la pestaña combinada
            const btnSiguienteComplementaria = document.querySelector('#informacionComplementariaCard .btn-primary');
            if (btnSiguienteComplementaria) {
                btnSiguienteComplementaria.removeAttribute('onclick');
                btnSiguienteComplementaria.addEventListener('click', function() {
                    // Validar todos los campos de la pestaña combinada
                    if (validateInformacionComplementaria()) {
                        showTab('confirmacion');
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Información incompleta',
                            text: 'Por favor, complete correctamente todos los campos marcados en rojo para continuar.'
                        });

                        // Hacer scroll al primer campo con error
                        const firstInvalidField = document.querySelector('#informacionComplementariaCard .is-invalid');
                        if (firstInvalidField) {
                            firstInvalidField.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            firstInvalidField.focus();
                        }
                    }
                });
            }

            // Buscar y corregir todos los otros botones de siguiente y anterior
            const navigationButtons = document.querySelectorAll('button[onclick^="showTab"]');
            navigationButtons.forEach(button => {
                const onclickValue = button.getAttribute('onclick');
                if (onclickValue) {
                    const match = onclickValue.match(/showTab\(['"](.+?)['"]\)/);
                    if (match && match[1]) {
                        const targetTab = match[1];
                        button.removeAttribute('onclick');

                        // Determinar si es botón anterior o siguiente basado en su texto
                        if (button.innerHTML.includes('Anterior')) {
                            button.addEventListener("click", function() {
                                showTab(targetTab);
                            });
                        } else if (button.innerHTML.includes('Siguiente')) {
                            // Determinar qué validación usar según la pestaña actual
                            button.addEventListener("click", function() {
                                let isValid = true;

                                // Determinar la pestaña actual por el botón
                                if (button.closest('.card').id === 'informacionProfesionalCard') {
                                    isValid = validateProfessionalInfo();
                                } else if (button.closest('.card').id === 'contratoCard') {
                                    isValid = validateContratoInfo();
                                } else if (button.closest('.card').id === 'horarioCard') {
                                    isValid = validateHorarioInfo();
                                } else if (button.closest('.card').id === 'credencialesCard') {
                                    isValid = validateCredenciales();
                                }

                                if (isValid) {
                                    showTab(targetTab);
                                }
                            });
                        }
                    }
                }
            });

            // Setup form submit
            document.getElementById("doctorRegistrationForm").addEventListener("submit", function(e) {
                e.preventDefault();
                guardarDoctor();
            });

            // Setup botón de cancelar
            const btnCancelar = document.querySelector('button[onclick="resetForm()"]');
            if (btnCancelar) {
                btnCancelar.removeAttribute('onclick');
                btnCancelar.addEventListener("click", function() {
                    resetForm();
                });
            }
            // Asociar evento al botón de editar precio
            const btnEditarPrecio = document.getElementById('btnEditarPrecio');
            if (btnEditarPrecio) {
                btnEditarPrecio.addEventListener('click', function() {
                    // Verificar si hay una especialidad seleccionada
                    const especialidadSelect = document.getElementById('especialidad');
                    const precioInput = document.getElementById('precioatencion');

                    if (!especialidadSelect.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Seleccione una especialidad',
                            text: 'Debe seleccionar una especialidad antes de editar el precio'
                        });
                        return;
                    }

                    // Obtener el texto de la especialidad seleccionada
                    const especialidadTexto = especialidadSelect.options[especialidadSelect.selectedIndex].text;

                    // Configurar el modal
                    document.getElementById('especialidadActual').value = especialidadTexto;
                    document.getElementById('nuevoPrecioAtencion').value = precioInput.value;

                    // Mostrar el modal
                    const modalEditarPrecio = new bootstrap.Modal(document.getElementById('modalEditarPrecio'));
                    modalEditarPrecio.show();
                });
            }

            // Asociar evento al botón de guardar precio en el modal
            const btnGuardarPrecio = document.getElementById('btnGuardarPrecio');
            if (btnGuardarPrecio) {
                btnGuardarPrecio.addEventListener('click', function() {
                    const nuevoPrecio = document.getElementById('nuevoPrecioAtencion').value;

                    if (!nuevoPrecio || parseFloat(nuevoPrecio) <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Precio inválido',
                            text: 'El precio debe ser mayor a cero'
                        });
                        return;
                    }

                    // Mostrar loader mientras se actualiza
                    Swal.fire({
                        title: 'Actualizando...',
                        text: 'Por favor espere mientras se actualiza el precio.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Obtener ID de la especialidad seleccionada
                    const idEspecialidad = document.getElementById('especialidad').value;

                    // Actualizar en la base de datos
                    actualizarPrecioEspecialidad(idEspecialidad, nuevoPrecio);
                });
            }

            // Función para actualizar el precio de una especialidad en la base de datos
            function actualizarPrecioEspecialidad(idEspecialidad, nuevoPrecio) {
                const formData = new FormData();
                formData.append('idespecialidad', idEspecialidad);
                formData.append('precioatencion', nuevoPrecio);

                fetch('../../../controllers/especialidad.controller.php?op=actualizar_precio', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Cerrar el loader
                        Swal.close();

                        if (data.status) {
                            // Actualizar el precio en el formulario principal
                            const precioInput = document.getElementById('precioatencion');
                            precioInput.value = nuevoPrecio;
                            precioInput.readOnly = true;
                            markFieldAsValid(precioInput);
                            removeFieldHelpMessage(precioInput);

                            // Cerrar el modal
                            bootstrap.Modal.getInstance(document.getElementById('modalEditarPrecio')).hide();

                            // Mostrar mensaje de éxito
                            showSuccessToast('Precio actualizado correctamente en la base de datos');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al actualizar',
                                text: data.mensaje || 'No se pudo actualizar el precio de atención.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al actualizar precio:', error);
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'No se pudo completar la actualización del precio: ' + error.message
                        });
                    });
            }

            // Función para mostrar la pestaña correspondiente
            window.showTab = function(tab) {
                console.log("Cambiando a pestaña:", tab);

                // Ocultar todas las tarjetas
                const allCards = [
                    "personalInfoCard",
                    "informacionComplementariaCard", // Nueva tarjeta combinada
                    "confirmacionCard"
                ];

                allCards.forEach(card => {
                    const element = document.getElementById(card);
                    if (element) {
                        element.classList.add("d-none");
                    }
                });

                // Determinar el ID correcto de la tarjeta
                let cardId;
                switch (tab) {
                    case 'personalInfo':
                        cardId = 'personalInfoCard';
                        break;
                    case 'informacionProfesional': // Este caso redirige a personalInfoCard
                        cardId = 'personalInfoCard';
                        break;
                    case 'contrato':
                    case 'horario':
                    case 'credenciales':
                        // Todos estos casos ahora van a la misma tarjeta combinada
                        cardId = 'informacionComplementariaCard';
                        break;
                    case 'confirmacion':
                        cardId = 'confirmacionCard';
                        break;
                    default:
                        cardId = tab + 'Card';
                }

                // Mostrar la tarjeta seleccionada
                const targetCard = document.getElementById(cardId);
                if (targetCard) {
                    targetCard.classList.remove("d-none");
                    currentTab = tab;

                    // Actualizar barra de progreso
                    updateProgressBarForTab(tab);

                    // Si es la primera pestaña, ocultar el nombre del doctor
                    if (tab === 'personalInfo') {
                        document.getElementById('doctorNameDisplay').classList.add('d-none');
                    }
                    // Para cualquier otra pestaña, mostrar el nombre del doctor si ya está ingresado
                    else {
                        const apellidos = document.getElementById('apellidos').value;
                        const nombres = document.getElementById('nombres').value;

                        if (apellidos && nombres) {
                            document.getElementById('doctorNameText').textContent = `${nombres} ${apellidos}`;
                            document.getElementById('doctorNameDisplay').classList.remove('d-none');
                        }
                    }

                    // Si es la pestaña de confirmación, cargar los datos de resumen
                    if (tab === 'confirmacion') {
                        loadConfirmationData();
                    }
                } else {
                    console.error("No se encontró la tarjeta:", cardId);
                }
            }

            // Función para actualizar la barra de progreso según la pestaña
            function updateProgressBarForTab(tab) {
                const progressValues = {
                    'personalInfo': 0,
                    'informacionProfesional': 0, // Mismo valor que personalInfo
                    'contrato': 50, // Ahora todas las pestañas intermedias valen 50%
                    'horario': 50,
                    'credenciales': 50,
                    'confirmacion': 100
                };

                updateProgressBar(progressValues[tab] || 0);
            }


            // Función para actualizar la barra de progreso
            function updateProgressBar(percentage) {
                const progressBar = document.querySelector(".progress-bar");
                progressBar.style.width = percentage + "%";
                progressBar.setAttribute("aria-valuenow", percentage);
            }

            // Función para verificar si todos los campos personales están completos
            function checkPersonalFields() {
                const requiredFields = [
                    'tipodoc', 'nrodoc', 'apellidos', 'nombres', 'fechanacimiento',
                    'genero', 'telefono', 'email', 'direccion'
                ];

                let allFilled = true;

                // Verificar cada campo requerido
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input || !input.value.trim()) {
                        allFilled = false;

                        // Marcar como inválido solo si ya se intentó validar
                        if (input && (input.classList.contains('is-invalid') || input.classList.contains('is-valid'))) {
                            markFieldAsInvalid(input);
                            addFieldHelpMessage(input, 'Este campo es obligatorio');
                        }
                    } else {
                        // Validaciones específicas por tipo de campo
                        let isValid = true;

                        // Validar formato del documento
                        if (field === 'nrodoc') {
                            const tipodoc = document.getElementById('tipodoc').value;
                            if (tipodoc && documentoConfig[tipodoc] && !documentoConfig[tipodoc].pattern.test(input.value)) {
                                isValid = false;
                                addFieldHelpMessage(input, documentoConfig[tipodoc].message);
                            }
                        }

                        // Validar fecha de nacimiento - AÑADIDO
                        if (field === 'fechanacimiento') {
                            const validation = validateFechaNacimiento(input.value);
                            if (!validation.isValid) {
                                isValid = false;
                                addFieldHelpMessage(input, validation.message);
                            }
                        }

                        // Validar teléfono
                        if (field === 'telefono' && !/^9\d{8}$/.test(input.value)) {
                            isValid = false;
                            addFieldHelpMessage(input, 'El teléfono debe tener 9 dígitos y comenzar con 9');
                        }

                        // Validar email
                        if (field === 'email' && !validateEmail(input.value)) {
                            isValid = false;
                            addFieldHelpMessage(input, 'Ingrese un correo electrónico válido');
                        }

                        if (isValid) {
                            markFieldAsValid(input);
                            removeFieldHelpMessage(input);
                        } else {
                            markFieldAsInvalid(input);
                            allFilled = false;
                        }
                    }
                });

                return allFilled;
            }
            // Función para validar la fecha de nacimiento
            function validateFechaNacimiento(fechaNacimiento) {
                // Convertir a fecha
                const fechaNacimientoDate = new Date(fechaNacimiento);
                const hoy = new Date();

                // Calcular edad
                let edad = hoy.getFullYear() - fechaNacimientoDate.getFullYear();
                const meses = hoy.getMonth() - fechaNacimientoDate.getMonth();
                if (meses < 0 || (meses === 0 && hoy.getDate() < fechaNacimientoDate.getDate())) {
                    edad--;
                }

                // Verificar si la fecha es en el futuro
                if (fechaNacimientoDate > hoy) {
                    return {
                        isValid: false,
                        message: 'La fecha de nacimiento no puede ser en el futuro'
                    };
                }

                // Verificar si es menor de edad
                if (edad < 18) {
                    return {
                        isValid: false,
                        message: 'El doctor debe ser mayor de edad (al menos 18 años)'
                    };
                }

                // Verificar si tiene una edad razonable para ser doctor (menos de 90 años)
                if (edad > 90) {
                    return {
                        isValid: false,
                        message: 'La edad parece no ser válida para un doctor activo'
                    };
                }

                // Verificar si el año es válido (no muy antiguo)
                const añoMinimo = 1920;
                if (fechaNacimientoDate.getFullYear() < añoMinimo) {
                    return {
                        isValid: false,
                        message: `El año de nacimiento no puede ser anterior a ${añoMinimo}`
                    };
                }

                return {
                    isValid: true,
                    message: ''
                };
            }
            // Para integrar en el código existente
            document.getElementById('fechanacimiento').addEventListener('change', function() {
                const fechaNacimiento = this.value;

                if (fechaNacimiento) {
                    const validation = validateFechaNacimiento(fechaNacimiento);

                    if (validation.isValid) {
                        markFieldAsValid(this);
                        removeFieldHelpMessage(this);
                    } else {
                        markFieldAsInvalid(this);
                        addFieldHelpMessage(this, validation.message);

                        // Mostrar notificación en la esquina
                        showErrorToast(validation.message);
                    }
                } else {
                    markFieldAsInvalid(this);
                    addFieldHelpMessage(this, 'La fecha de nacimiento es requerida');
                }
            });
            // 3. Modificar la validación para la nueva pestaña combinada
            function validateInformacionComplementaria() {
                // Validar contrato
                const contratoValid = validateContratoInfo();

                // Validar horario
                const horarioValid = validateHorarioInfo();

                // Validar credenciales
                const credencialesValid = validateCredenciales();

                // Todos deben ser válidos para continuar
                return contratoValid && horarioValid && credencialesValid;
            }

            // Función para validar la información personal
            function validatePersonalInfo() {
                const requiredFields = [
                    'tipodoc', 'nrodoc', 'apellidos', 'nombres', 'fechanacimiento',
                    'genero', 'telefono', 'email', 'direccion'
                ];

                let isValid = true;
                let firstInvalidField = null;

                // Validar campos obligatorios
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input || !input.value.trim()) {
                        markFieldAsInvalid(input);
                        addFieldHelpMessage(input, 'Este campo es obligatorio');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = input;
                    } else {
                        // Validaciones específicas por tipo de campo
                        let fieldValid = true;

                        // Validar que la fecha de nacimiento no sea en el futuro
                        if (field === 'fechanacimiento') {
                            const fechaNacimientoDate = new Date(input.value);
                            const hoy = new Date();
                            if (fechaNacimientoDate > hoy) {
                                fieldValid = false;
                                addFieldHelpMessage(input, 'La fecha de nacimiento no puede ser en el futuro');
                            }
                        }

                        // Validar formato del documento según el tipo
                        if (field === 'nrodoc') {
                            const tipodoc = document.getElementById('tipodoc').value;
                            if (tipodoc && documentoConfig[tipodoc] && !documentoConfig[tipodoc].pattern.test(input.value)) {
                                fieldValid = false;
                                addFieldHelpMessage(input, documentoConfig[tipodoc].message);
                            }
                        }

                        // Validar teléfono
                        if (field === 'telefono' && !/^9\d{8}$/.test(input.value)) {
                            fieldValid = false;
                            addFieldHelpMessage(input, 'El teléfono debe tener 9 dígitos y comenzar con 9');
                        }

                        // Validar email
                        if (field === 'email' && !validateEmail(input.value)) {
                            fieldValid = false;
                            addFieldHelpMessage(input, 'Ingrese un correo electrónico válido');
                        }

                        if (fieldValid) {
                            markFieldAsValid(input);
                            removeFieldHelpMessage(input);
                        } else {
                            markFieldAsInvalid(input);
                            isValid = false;
                            if (!firstInvalidField) firstInvalidField = input;
                        }
                    }
                });
                // Validar que la fecha de nacimiento sea válida
                const fechaNacimiento = document.getElementById('fechanacimiento').value;
                if (fechaNacimiento) {
                    const validation = validateFechaNacimiento(fechaNacimiento);
                    if (!validation.isValid) {
                        markFieldAsInvalid(document.getElementById('fechanacimiento'));
                        addFieldHelpMessage(document.getElementById('fechanacimiento'), validation.message);
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = document.getElementById('fechanacimiento');
                    }
                }


                return isValid;
            }

            // Función para validar la información profesional
            function validateProfessionalInfo() {
                // Para el campo de especialidad
                const especialidad = document.getElementById('especialidad');
                const precio = document.getElementById('precioatencion');
                let isValid = true;

                // Validar especialidad seleccionada
                if (!especialidad || !especialidad.value) {
                    markFieldAsInvalid(especialidad);
                    addFieldHelpMessage(especialidad, 'Debe seleccionar una especialidad');
                    isValid = false;
                } else {
                    markFieldAsValid(especialidad);
                    removeFieldHelpMessage(especialidad);
                }

                // Validar que el precio tenga valor (aunque esté en readonly)
                if (!precio || !precio.value || parseFloat(precio.value) <= 0) {
                    markFieldAsInvalid(precio);
                    addFieldHelpMessage(precio, 'Debe seleccionar una especialidad con precio válido');
                    isValid = false;
                } else {
                    markFieldAsValid(precio);
                    removeFieldHelpMessage(precio);
                }

                if (!isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Información profesional incompleta',
                        text: 'Por favor, seleccione una especialidad para continuar'
                    });
                }

                return isValid;
            }

            // Agregar evento para controlar el comportamiento según el tipo de contrato
            document.getElementById('tipocontrato').addEventListener('change', function() {
                const tipoContrato = this.value;
                const fechaFinInput = document.getElementById('fechafin');

                // Restablecer estado
                fechaFinInput.disabled = false;
                fechaFinInput.required = true;
                fechaFinInput.value = '';
                removeFieldHelpMessage(fechaFinInput);
                fechaFinInput.classList.remove('is-valid', 'is-invalid');

                // Aplicar reglas según tipo de contrato
                if (tipoContrato === 'INDEFINIDO') {
                    fechaFinInput.disabled = true;
                    fechaFinInput.required = false;
                    addFieldHelpMessage(fechaFinInput, 'No aplica para contratos indefinidos');

                    // Marcar el campo de tipo contrato como válido
                    markFieldAsValid(this);
                } else {
                    // Actualizar la fecha mínima permitida
                    actualizarFechaMinima();

                    // Validar tipo de contrato
                    markFieldAsValid(this);

                    // Validar campo de fecha fin si ya tiene valor
                    if (fechaFinInput.value) {
                        validarFechaFin();
                    }
                }
            });
            document.getElementById('fechainicio').addEventListener('change', function() {
                // Validar fecha inicio (simplemente verificar que tenga un valor)
                if (this.value) {
                    markFieldAsValid(this);
                    removeFieldHelpMessage(this);
                } else {
                    markFieldAsInvalid(this);
                    addFieldHelpMessage(this, 'La fecha de inicio es requerida');
                }

                // Actualizar la fecha mínima para fecha fin
                actualizarFechaMinima();
            });
            // Agregar evento para validar la fecha fin en tiempo real
            document.getElementById('fechafin').addEventListener('change', function() {
                validarFechaFin();
            });

            // Función para actualizar la fecha mínima del campo fecha fin
            function actualizarFechaMinima() {
                const fechaInicioInput = document.getElementById('fechainicio');
                const fechaFinInput = document.getElementById('fechafin');

                if (fechaInicioInput.value) {
                    // Establecer fecha mínima para fecha fin (permitiendo el mismo día)
                    const fechaInicio = new Date(fechaInicioInput.value);
                    // Ya no añadimos un día
                    // fechaInicio.setDate(fechaInicio.getDate() + 1); // Esta línea se elimina

                    // Formatear la fecha al formato yyyy-mm-dd para el atributo min
                    const yyyy = fechaInicio.getFullYear();
                    const mm = String(fechaInicio.getMonth() + 1).padStart(2, '0');
                    const dd = String(fechaInicio.getDate()).padStart(2, '0');
                    const fechaMinima = `${yyyy}-${mm}-${dd}`;

                    // Establecer fecha mínima
                    fechaFinInput.setAttribute('min', fechaMinima);

                    // Si la fecha actual es menor que la mínima, limpiar el campo
                    if (fechaFinInput.value && new Date(fechaFinInput.value) < new Date(fechaMinima)) {
                        fechaFinInput.value = '';
                        removeFieldHelpMessage(fechaFinInput);
                        fechaFinInput.classList.remove('is-valid', 'is-invalid');
                    }

                    // Si hay un valor en fecha fin, validarlo
                    if (fechaFinInput.value) {
                        validarFechaFin();
                    }
                }
            }
            // Función para validar la fecha fin según el tipo de contrato
            function validarFechaFin() {
                const tipoContrato = document.getElementById('tipocontrato').value;
                const fechaInicioStr = document.getElementById('fechainicio').value;
                const fechaFinStr = document.getElementById('fechafin').value;
                const fechaFinInput = document.getElementById('fechafin');

                // Si es indefinido, no necesitamos validar
                if (tipoContrato === 'INDEFINIDO') {
                    return true;
                }

                // Si falta fecha inicio, no podemos validar correctamente
                if (!fechaInicioStr) {
                    markFieldAsInvalid(fechaFinInput);
                    addFieldHelpMessage(fechaFinInput, 'Primero debe seleccionar una fecha de inicio');
                    return false;
                }

                // Si no hay fecha fin, es inválido para todos los tipos excepto indefinido
                if (!fechaFinStr) {
                    markFieldAsInvalid(fechaFinInput);
                    addFieldHelpMessage(fechaFinInput, 'La fecha de fin es requerida para este tipo de contrato');
                    return false;
                }

                const fechaInicio = new Date(fechaInicioStr);
                const fechaFin = new Date(fechaFinStr);

                // Permitir que sean el mismo día
                if (fechaFin < fechaInicio) {
                    markFieldAsInvalid(fechaFinInput);
                    addFieldHelpMessage(fechaFinInput, 'La fecha de fin no puede ser anterior a la fecha de inicio');
                    return false;
                }

                // Calcular diferencia en días
                const diferenciaMs = fechaFin.getTime() - fechaInicio.getTime();
                const diferenciaDias = Math.floor(diferenciaMs / (1000 * 60 * 60 * 24));

                // Validaciones específicas por tipo de contrato
                switch (tipoContrato) {
                    case 'PLAZO FIJO':
                        // Validar que sea al menos 1 año (365 días)
                        if (diferenciaDias < 365) {
                            markFieldAsInvalid(fechaFinInput);
                            addFieldHelpMessage(fechaFinInput, 'Para contratos de plazo fijo, la duración mínima debe ser de 1 año');
                            return false;
                        }
                        break;

                    case 'PART TIME': // Temporal
                        // Validar que esté entre 1 y 6 meses (30 a 180 días)
                        if (diferenciaDias < 30 || diferenciaDias > 180) {
                            markFieldAsInvalid(fechaFinInput);
                            addFieldHelpMessage(fechaFinInput, 'Para contratos temporales, la duración debe ser entre 1 y 6 meses');
                            return false;
                        }
                        break;

                    case 'LOCACION': // Eventual
                        // Validar que esté entre 1 y 30 días
                        if (diferenciaDias < 1 || diferenciaDias > 30) {
                            markFieldAsInvalid(fechaFinInput);
                            addFieldHelpMessage(fechaFinInput, 'Para contratos eventuales, la duración debe ser entre 1 y 30 días');
                            return false;
                        }
                        break;
                }

                // Si llegamos aquí, la fecha es válida
                markFieldAsValid(fechaFinInput);
                removeFieldHelpMessage(fechaFinInput);
                return true;
            }

            // Función para la validación completa de contrato (para el botón Siguiente)
            function validateContratoInfo() {
                const tipoContratoInput = document.getElementById('tipocontrato');
                const fechaInicioInput = document.getElementById('fechainicio');
                let isValid = true;

                // Validar tipo de contrato
                if (!tipoContratoInput.value) {
                    markFieldAsInvalid(tipoContratoInput);
                    addFieldHelpMessage(tipoContratoInput, 'Debe seleccionar un tipo de contrato');
                    isValid = false;
                } else {
                    markFieldAsValid(tipoContratoInput);
                    removeFieldHelpMessage(tipoContratoInput);
                }

                // Validar fecha inicio
                if (!fechaInicioInput.value) {
                    markFieldAsInvalid(fechaInicioInput);
                    addFieldHelpMessage(fechaInicioInput, 'La fecha de inicio es requerida');
                    isValid = false;
                } else {
                    markFieldAsValid(fechaInicioInput);
                    removeFieldHelpMessage(fechaInicioInput);
                }

                // Validar fecha fin (solo si no es indefinido)
                if (tipoContratoInput.value !== 'INDEFINIDO') {
                    if (!validarFechaFin()) {
                        isValid = false;
                    }
                }

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Información de contrato incompleta',
                        text: 'Por favor, complete correctamente todos los campos obligatorios.'
                    });

                    // Hacer scroll al primer campo inválido
                    const firstInvalidField = document.querySelector('#contratoCard .is-invalid');
                    if (firstInvalidField) {
                        firstInvalidField.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstInvalidField.focus();
                    }
                }

                return isValid;
            }

            // Función para validar todos los horarios seleccionados
            function validateHorarioInfo() {
                let isValid = true;
                const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
                let alMenosUnDia = false;
                let firstInvalidField = null;

                diasSemana.forEach(dia => {
                    const atiende = document.getElementById(`atiende${dia}`);

                    if (atiende && atiende.checked) {
                        alMenosUnDia = true;
                        const modalidad = document.getElementById(`modalidad${dia}`).value;
                        const horaInicio1 = document.getElementById(`horaInicio${dia}1`);
                        const horaFin1 = document.getElementById(`horaFin${dia}1`);

                        // Validar primer rango horario (siempre requerido si el día está seleccionado)
                        if (!horaInicio1.value) {
                            markFieldAsInvalid(horaInicio1);
                            addFieldHelpMessage(horaInicio1, 'Ingrese una hora de inicio');
                            isValid = false;
                            if (!firstInvalidField) firstInvalidField = horaInicio1;
                        } else {
                            markFieldAsValid(horaInicio1);
                            removeFieldHelpMessage(horaInicio1);
                        }

                        if (!horaFin1.value) {
                            markFieldAsInvalid(horaFin1);
                            addFieldHelpMessage(horaFin1, 'Ingrese una hora de fin');
                            isValid = false;
                            if (!firstInvalidField) firstInvalidField = horaFin1;
                        } else {
                            markFieldAsValid(horaFin1);
                            removeFieldHelpMessage(horaFin1);
                        }

                        // Validar que hora fin sea posterior a hora inicio
                        if (horaInicio1.value && horaFin1.value) {
                            const inicio1Time = new Date(`2000-01-01T${horaInicio1.value}`);
                            const fin1Time = new Date(`2000-01-01T${horaFin1.value}`);

                            if (fin1Time <= inicio1Time) {
                                markFieldAsInvalid(horaFin1);
                                addFieldHelpMessage(horaFin1, 'La hora de fin debe ser posterior a la hora de inicio');
                                isValid = false;
                                if (!firstInvalidField) firstInvalidField = horaFin1;
                            }
                        }

                        // Si es tiempo completo, validar también el segundo rango horario (tarde)
                        if (modalidad === 'tiempoCompleto') {
                            const horaInicio2 = document.getElementById(`horaInicio${dia}2`);
                            const horaFin2 = document.getElementById(`horaFin${dia}2`);

                            if (!horaInicio2.value) {
                                markFieldAsInvalid(horaInicio2);
                                addFieldHelpMessage(horaInicio2, 'Ingrese una hora de inicio para el turno tarde');
                                isValid = false;
                                if (!firstInvalidField) firstInvalidField = horaInicio2;
                            } else {
                                markFieldAsValid(horaInicio2);
                                removeFieldHelpMessage(horaInicio2);
                            }

                            if (!horaFin2.value) {
                                markFieldAsInvalid(horaFin2);
                                addFieldHelpMessage(horaFin2, 'Ingrese una hora de fin para el turno tarde');
                                isValid = false;
                                if (!firstInvalidField) firstInvalidField = horaFin2;
                            } else {
                                markFieldAsValid(horaFin2);
                                removeFieldHelpMessage(horaFin2);
                            }

                            // Validar que hora fin sea posterior a hora inicio en el segundo rango
                            if (horaInicio2.value && horaFin2.value) {
                                const inicio2Time = new Date(`2000-01-01T${horaInicio2.value}`);
                                const fin2Time = new Date(`2000-01-01T${horaFin2.value}`);

                                if (fin2Time <= inicio2Time) {
                                    markFieldAsInvalid(horaFin2);
                                    addFieldHelpMessage(horaFin2, 'La hora de fin debe ser posterior a la hora de inicio');
                                    isValid = false;
                                    if (!firstInvalidField) firstInvalidField = horaFin2;
                                }
                            }

                            // Validar que el segundo horario sea posterior al primero - NUEVA VALIDACIÓN
                            if (horaFin1.value && horaInicio2.value) {
                                const fin1Time = new Date(`2000-01-01T${horaFin1.value}`);
                                const inicio2Time = new Date(`2000-01-01T${horaInicio2.value}`);

                                if (inicio2Time <= fin1Time) {
                                    markFieldAsInvalid(horaInicio2);
                                    addFieldHelpMessage(horaInicio2, `El horario de tarde debe comenzar después de las ${horaFin1.value}`);
                                    isValid = false;
                                    if (!firstInvalidField) firstInvalidField = horaInicio2;
                                }
                            }
                        }
                    }
                });

                if (!alMenosUnDia) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Horario requerido',
                        text: 'Debe seleccionar al menos un día de atención'
                    });
                    return false;
                }

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Horarios incompletos',
                        text: 'Por favor, complete correctamente todos los horarios seleccionados.'
                    });

                    // Hacer scroll al primer campo inválido
                    if (firstInvalidField) {
                        firstInvalidField.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstInvalidField.focus();
                    }
                }

                return isValid;
            }

            // Función para validar las credenciales
            function validateCredenciales() {
                const requiredFields = ['nomuser', 'passuser'];
                let isValid = true;
                let firstInvalidField = null;

                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input || !input.value.trim()) {
                        markFieldAsInvalid(input);
                        addFieldHelpMessage(input, 'Este campo es obligatorio');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = input;
                    } else {
                        markFieldAsValid(input);
                        removeFieldHelpMessage(input);
                    }
                });

                // Validación adicional para nombre de usuario
                const nomuser = document.getElementById('nomuser');
                if (nomuser && nomuser.value && !/^[a-zA-Z0-9_]{4,20}$/.test(nomuser.value)) {
                    markFieldAsInvalid(nomuser);
                    addFieldHelpMessage(nomuser, 'El nombre de usuario debe tener entre 4 y 20 caracteres alfanuméricos');
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = nomuser;
                }

                // Validación adicional para contraseña
                const passuser = document.getElementById('passuser');
                if (passuser && passuser.value && passuser.value.length < 6) {
                    markFieldAsInvalid(passuser);
                    addFieldHelpMessage(passuser, 'La contraseña debe tener al menos 6 caracteres');
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = passuser;
                }

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Credenciales incompletas',
                        text: 'Por favor, complete correctamente las credenciales de acceso.'
                    });

                    // Hacer scroll al primer campo inválido
                    if (firstInvalidField) {
                        firstInvalidField.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstInvalidField.focus();
                    }
                }

                return isValid;
            }

            // Función para validar todos los pasos
            function validateAllSteps() {
                if (!validatePersonalInfo() || !validateProfessionalInfo()) {
                    showTab('personalInfo');
                    updateProgressBar(0);
                    return false;
                }

                if (!validateInformacionComplementaria()) {
                    showTab('contrato'); // Usamos 'contrato' como identificador de la pestaña combinada
                    updateProgressBar(50);
                    return false;
                }

                return true;
            }

            // Función para validar formato de email
            function validateEmail(email) {
                // Expresión regular más completa para validar correos electrónicos
                const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return re.test(String(email).toLowerCase());
            }


            // Función para marcar campos como válidos
            function markFieldAsValid(field) {
                if (field) {
                    field.classList.add('is-valid');
                    field.classList.remove('is-invalid');
                }
            }

            // Función para marcar campos como inválidos
            function markFieldAsInvalid(field) {
                if (field) {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                }
            }

            // Función para añadir mensaje de ayuda junto al campo
            function addFieldHelpMessage(field, message) {
                if (!field) return;

                // Eliminar mensaje previo si existe
                removeFieldHelpMessage(field);

                // Crear nuevo mensaje
                const helpDiv = document.createElement('div');
                helpDiv.className = 'invalid-feedback';
                helpDiv.id = `help-${field.id}`;
                helpDiv.textContent = message;

                // Insertar después del campo
                if (field.parentNode) {
                    field.parentNode.appendChild(helpDiv);
                }
            }

            // Función para eliminar mensaje de ayuda
            function removeFieldHelpMessage(field) {
                if (!field) return;

                const helpDiv = document.getElementById(`help-${field.id}`);
                if (helpDiv && helpDiv.parentNode) {
                    helpDiv.parentNode.removeChild(helpDiv);
                }
            }

            // Función para mostrar toast de éxito
            function showSuccessToast(message) {
                if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });

                    Toast.fire({
                        icon: "success",
                        title: message
                    });
                } else {
                    console.log('Éxito:', message);
                }
            }

            // Función para mostrar toast de error
            function showErrorToast(message) {
                if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });

                    Toast.fire({
                        icon: "error",
                        title: message
                    });
                } else {
                    console.error('Error de validación:', message);
                    alert(message);
                }
            }

            // Estas funciones deberían estar definidas globalmente si no están ya disponibles
            window.markFieldAsValid = window.markFieldAsValid || function(field) {
                if (field) {
                    field.classList.add('is-valid');
                    field.classList.remove('is-invalid');
                }
            };

            window.markFieldAsInvalid = window.markFieldAsInvalid || function(field) {
                if (field) {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                }
            };

            window.addFieldHelpMessage = window.addFieldHelpMessage || function(field, message) {
                if (!field) return;

                // Eliminar mensaje previo si existe
                window.removeFieldHelpMessage(field);

                // Crear nuevo mensaje
                const helpDiv = document.createElement('div');
                helpDiv.className = 'invalid-feedback';
                helpDiv.id = `help-${field.id}`;
                helpDiv.textContent = message;

                // Insertar después del campo
                if (field.parentNode) {
                    field.parentNode.appendChild(helpDiv);
                }
            };

            window.removeFieldHelpMessage = window.removeFieldHelpMessage || function(field) {
                if (!field) return;

                const helpDiv = document.getElementById(`help-${field.id}`);
                if (helpDiv && helpDiv.parentNode) {
                    helpDiv.parentNode.removeChild(helpDiv);
                }
            };

            // Validar todos los días para la función validateHorarioInfo
            function validateAllDays() {
                const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
                let isValid = true;
                let errorMessages = [];
                let firstInvalidField = null;
                let totalDaysSelected = 0;
                let totalDaysValid = 0;

                diasSemana.forEach(dia => {
                    const atiende = document.getElementById(`atiende${dia}`);

                    if (atiende && atiende.checked) {
                        totalDaysSelected++;
                        // Para este día, validar el horario
                        const dayValid = validateScheduleForDay(dia);

                        if (dayValid) {
                            totalDaysValid++;
                        } else {
                            isValid = false;

                            // Añadir mensaje de error
                            errorMessages.push(`Horario inválido para el día ${dia}`);

                            // Encontrar el primer campo inválido para este día si aún no se ha encontrado
                            if (!firstInvalidField) {
                                const fields = [
                                    document.getElementById(`horaInicio${dia}1`),
                                    document.getElementById(`horaFin${dia}1`),
                                    document.getElementById(`horaInicio${dia}2`),
                                    document.getElementById(`horaFin${dia}2`)
                                ];

                                for (const field of fields) {
                                    if (field && field.classList.contains('is-invalid')) {
                                        firstInvalidField = field;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                });

                // Si la validación falló, mostrar un mensaje con todos los errores
                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Horarios inválidos',
                        html: errorMessages.join('<br>'),
                        confirmButtonText: 'Corregir'
                    });

                    // Desplazarse hasta el primer campo inválido
                    if (firstInvalidField) {
                        firstInvalidField.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstInvalidField.focus();
                    }
                } else if (totalDaysSelected > 0) {
                    // Si todos los días seleccionados son válidos, mostrar mensaje de éxito
                    showSuccessToast(`¡Todos los horarios están correctos! (${totalDaysValid} días configurados)`);
                }

                return isValid;
            }


            window.validateHorarioInfo = function() {
                // Primero verificar si se seleccionó al menos un día
                const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
                let alMenosUnDia = false;

                diasSemana.forEach(dia => {
                    const atiende = document.getElementById(`atiende${dia}`);
                    if (atiende && atiende.checked) {
                        alMenosUnDia = true;
                    }
                });

                if (!alMenosUnDia) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Horario requerido',
                        text: 'Debe seleccionar al menos un día de atención'
                    });
                    return false;
                }

                // Si se seleccionó al menos un día, validar todos los días
                return validateAllDays();
            };

            // Función para cargar las especialidades desde el servidor
            function cargarEspecialidades(seleccionarId = null) {
                // Verificar que la URL sea correcta
                fetch('../../../controllers/colaborador.controller.php?op=especialidades')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Datos de especialidades recibidos:", data); // Para depuración

                        if (data.status) {
                            const selectEspecialidad = document.getElementById('especialidad');
                            if (selectEspecialidad) {
                                selectEspecialidad.innerHTML = '<option value="">Seleccione...</option>';

                                if (data.data && Array.isArray(data.data)) {
                                    data.data.forEach(especialidad => {
                                        const option = document.createElement('option');
                                        option.value = especialidad.idespecialidad;
                                        option.textContent = especialidad.especialidad;
                                        selectEspecialidad.appendChild(option);

                                        // Si hay un ID para seleccionar automáticamente
                                        if (seleccionarId && especialidad.idespecialidad == seleccionarId) {
                                            option.selected = true;
                                            // Cargar el precio automáticamente
                                            cargarPrecioEspecialidad(seleccionarId);
                                        }
                                    });
                                } else {
                                    console.error('El formato de datos de especialidades no es válido:', data);
                                }
                            }
                        } else {
                            console.error('Error al obtener especialidades:', data.mensaje || 'Error desconocido');
                            showErrorToast('No se pudieron cargar las especialidades');
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar especialidades:', error);
                        showErrorToast('No se pudieron cargar las especialidades: ' + error.message);
                    });
            }

            // Función específica para validar el campo de email
            function validateEmailField(input) {
                // Limpiar cualquier estilo o mensaje previo
                input.classList.remove('is-valid', 'is-invalid', 'border-danger');
                removeFieldHelpMessage(input);

                if (!input.value.trim()) {
                    // Si está vacío
                    markFieldAsInvalid(input);
                    addFieldHelpMessage(input, 'El correo electrónico es obligatorio');
                    return false;
                } else if (!validateEmail(input.value)) {
                    // Si el formato es incorrecto
                    markFieldAsInvalid(input);
                    addFieldHelpMessage(input, 'Ingrese un correo electrónico válido');
                    return false;
                } else {
                    // Si es válido
                    markFieldAsValid(input);
                    removeFieldHelpMessage(input);
                    // Asegurarse de que no tenga el borde rojo
                    input.classList.remove('border-danger');
                    return true;
                }
            }

            // Función para configurar validaciones de campos
            function setupFieldValidations() {
                // Configurar validación de teléfono
                const telefono = document.getElementById('telefono');
                if (telefono) {
                    telefono.setAttribute('maxlength', 9);
                    telefono.addEventListener('input', function() {
                        // Eliminar cualquier carácter que no sea un número
                        this.value = this.value.replace(/\D/g, '');

                        // Asegurar que comience con 9
                        if (this.value.length > 0 && this.value.charAt(0) !== '9') {
                            this.value = '9' + this.value.substring(1);
                        }

                        // Validar el formato del teléfono
                        if (this.value.length === 9 && this.value.charAt(0) === '9') {
                            markFieldAsValid(this);
                            removeFieldHelpMessage(this);
                        } else if (this.value.length > 0) {
                            markFieldAsInvalid(this);
                            addFieldHelpMessage(this, 'El teléfono debe tener 9 dígitos y comenzar con 9');
                        }

                        // Verificar si todos los campos están completos
                        checkPersonalFields();
                    });
                }

                // Configurar validación del precio de atención
                const precioatencion = document.getElementById('precioatencion');
                if (precioatencion) {
                    precioatencion.addEventListener('input', function() {
                        // Permitir solo números y un punto decimal
                        this.value = this.value.replace(/[^0-9.]/g, '');

                        // Asegurar que solo hay un punto decimal
                        const parts = this.value.split('.');
                        if (parts.length > 2) {
                            this.value = parts[0] + '.' + parts.slice(1).join('');
                        }

                        // Validar que sea un número positivo
                        const valor = parseFloat(this.value);
                        if (!isNaN(valor) && valor > 0) {
                            markFieldAsValid(this);
                            removeFieldHelpMessage(this);
                        } else if (this.value) {
                            markFieldAsInvalid(this);
                            addFieldHelpMessage(this, 'El precio debe ser mayor a cero');
                        }
                    });
                }

                // Validación específica para fecha de nacimiento
                const fechaNacimientoInput = document.getElementById('fechanacimiento');
                if (fechaNacimientoInput) {
                    fechaNacimientoInput.addEventListener('change', function() {
                        validateFechaNacimientoField(this);
                    });

                    // También validar en caso de input para capturar cambios manuales
                    fechaNacimientoInput.addEventListener('input', function() {
                        validateFechaNacimientoField(this);
                    });
                }

                // Agregar validación a todos los campos requeridos (sin incluir fecha de nacimiento)
                const requiredFields = [
                    'apellidos', 'nombres', 'genero',
                    'email', 'direccion'
                ];

                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (input) {
                        input.addEventListener('blur', function() {
                            if (this.value.trim()) {
                                markFieldAsValid(this);
                                removeFieldHelpMessage(this);
                            } else {
                                markFieldAsInvalid(this);
                                addFieldHelpMessage(this, 'Este campo es obligatorio');
                            }

                            // Verificar campos personales
                            checkPersonalFields();
                        });

                        // Para campos de texto, validar al cambiar
                        if (input.type === 'text' || input.tagName === 'SELECT' || input.type === 'date' || input.type === 'email') {
                            input.addEventListener('input', function() {
                                if (this.value.trim()) {
                                    markFieldAsValid(this);
                                    removeFieldHelpMessage(this);
                                } else {
                                    markFieldAsInvalid(this);
                                    addFieldHelpMessage(this, 'Este campo es obligatorio');
                                }

                                // Verificar campos personales
                                checkPersonalFields();
                            });
                        }
                    }
                });
                const emailInput = document.getElementById('email');
                if (emailInput) {
                    emailInput.addEventListener('blur', function() {
                        validateEmailField(this);
                        // Verificar campos personales
                        checkPersonalFields();
                    });

                    emailInput.addEventListener('input', function() {
                        validateEmailField(this);
                        // Verificar campos personales
                        checkPersonalFields();
                    });
                }


                // Validar email
                const email = document.getElementById('email');
                if (email) {
                    email.addEventListener('blur', function() {
                        if (this.value.trim()) {
                            if (validateEmail(this.value)) {
                                markFieldAsValid(this);
                                removeFieldHelpMessage(this);
                            } else {
                                markFieldAsInvalid(this);
                                addFieldHelpMessage(this, 'Ingrese un correo electrónico válido');
                            }
                        } else {
                            markFieldAsInvalid(this);
                            addFieldHelpMessage(this, 'Este campo es obligatorio');
                        }

                        // Verificar si todos los campos están completos
                        checkPersonalFields();
                    });
                }

                // Setup para cambio de horarios
                const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

                diasSemana.forEach(dia => {
                    // Agregar listeners para validación en tiempo real
                    const horaFin1 = document.getElementById(`horaFin${dia}1`);
                    const horaInicio2 = document.getElementById(`horaInicio${dia}2`);

                    if (horaFin1 && horaInicio2) {
                        horaFin1.addEventListener('input', function() {
                            const modalidad = document.getElementById(`modalidad${dia}`).value;
                            if (modalidad === 'tiempoCompleto') {
                                validarHorarioSecuencial(dia);
                            }
                        });

                        horaInicio2.addEventListener('input', function() {
                            validarHorarioSecuencial(dia);
                        });
                    }
                });

            }
            // 3. Agregar una función específica para validar el campo de fecha de nacimiento
            function validateFechaNacimientoField(input) {
                if (!input.value.trim()) {
                    markFieldAsInvalid(input);
                    addFieldHelpMessage(input, 'Este campo es obligatorio');
                    return false;
                }

                const validation = validateFechaNacimiento(input.value);

                if (validation.isValid) {
                    markFieldAsValid(input);
                    removeFieldHelpMessage(input);

                    // Mostrar notificación de éxito en la esquina
                    showSuccessToast('La fecha de nacimiento es correcta');
                    return true;
                } else {
                    markFieldAsInvalid(input);
                    addFieldHelpMessage(input, validation.message);

                    // Mostrar notificación en la esquina
                    showErrorToast(validation.message);
                    return false;
                }
            }
            // Agregar eventos para actualizar el nombre del doctor cuando cambia el nombre o apellido
            const apellidosInput = document.getElementById('apellidos');
            const nombresInput = document.getElementById('nombres');

            if (apellidosInput && nombresInput) {
                const updateDoctorName = function() {
                    const apellidos = apellidosInput.value.trim();
                    const nombres = nombresInput.value.trim();

                    if (apellidos && nombres && currentTab !== 'personalInfo') {
                        document.getElementById('doctorNameText').textContent = `${nombres} ${apellidos}`;
                        document.getElementById('doctorNameDisplay').classList.remove('d-none');
                    }
                };

                apellidosInput.addEventListener('input', updateDoctorName);
                nombresInput.addEventListener('input', updateDoctorName);
            }

            // Función para habilitar/deshabilitar horarios según el día seleccionado
            window.toggleHorario = function(dia) {
                const checkbox = document.getElementById(`atiende${dia}`);
                const selectModalidad = document.getElementById(`modalidad${dia}`);
                const horaInicio1 = document.getElementById(`horaInicio${dia}1`);
                const horaFin1 = document.getElementById(`horaFin${dia}1`);
                const horaInicio2 = document.getElementById(`horaInicio${dia}2`);
                const horaFin2 = document.getElementById(`horaFin${dia}2`);
                const contenedorTarde = document.querySelector(`#atiende${dia}`).closest('tr').querySelector('.horario-tarde');

                if (checkbox && checkbox.checked) {
                    // Habilitar el selector de modalidad
                    if (selectModalidad) {
                        selectModalidad.disabled = false;
                    }

                    // Habilitar los inputs del primer horario (mañana)
                    if (horaInicio1) {
                        horaInicio1.disabled = false;
                        horaInicio1.required = true;

                        // Establecer hora de mañana predeterminada si está vacía
                        if (!horaInicio1.value) {
                            horaInicio1.value = '06:00';
                        }
                    }

                    if (horaFin1) {
                        horaFin1.disabled = false;
                        horaFin1.required = true;

                        // Establecer hora de fin de mañana predeterminada si está vacía
                        if (!horaFin1.value) {
                            horaFin1.value = '12:00';
                        }
                    }

                    // Verificar la modalidad seleccionada
                    if (selectModalidad && selectModalidad.value === 'tiempoCompleto') {
                        // Mostrar y habilitar campos del segundo horario (tarde)
                        contenedorTarde.classList.remove('d-none');

                        if (horaInicio2) {
                            horaInicio2.disabled = false;
                            horaInicio2.required = true;

                            // Establecer hora de inicio de tarde predeterminada si está vacía
                            if (!horaInicio2.value) {
                                horaInicio2.value = '14:00';
                            }
                        }

                        if (horaFin2) {
                            horaFin2.disabled = false;
                            horaFin2.required = true;

                            // Establecer hora de fin de tarde predeterminada si está vacía
                            if (!horaFin2.value) {
                                horaFin2.value = '20:00';
                            }
                        }
                    } else {
                        // Ocultar campos del segundo horario
                        contenedorTarde.classList.add('d-none');

                        if (horaInicio2) {
                            horaInicio2.disabled = true;
                            horaInicio2.required = false;
                            horaInicio2.value = '';
                        }

                        if (horaFin2) {
                            horaFin2.disabled = true;
                            horaFin2.required = false;
                            horaFin2.value = '';
                        }
                    }
                } else {
                    // Desactivar todo si el día no está seleccionado
                    if (selectModalidad) {
                        selectModalidad.disabled = true;
                        // AGREGAR ESTA LÍNEA: Resetear a "Medio tiempo" cuando se desmarca
                        selectModalidad.value = "medioTiempo";
                    }

                    // Deshabilitar y limpiar todos los campos de horario
                    if (horaInicio1) {
                        horaInicio1.disabled = true;
                        horaInicio1.required = false;
                        horaInicio1.value = '';
                        horaInicio1.classList.remove('is-valid', 'is-invalid');
                        removeFieldHelpMessage(horaInicio1);
                    }

                    if (horaFin1) {
                        horaFin1.disabled = true;
                        horaFin1.required = false;
                        horaFin1.value = '';
                        horaFin1.classList.remove('is-valid', 'is-invalid');
                        removeFieldHelpMessage(horaFin1);
                    }

                    // Ocultar contenedor del segundo horario
                    contenedorTarde.classList.add('d-none');

                    if (horaInicio2) {
                        horaInicio2.disabled = true;
                        horaInicio2.required = false;
                        horaInicio2.value = '';
                        horaInicio2.classList.remove('is-valid', 'is-invalid');
                        removeFieldHelpMessage(horaInicio2);
                    }

                    if (horaFin2) {
                        horaFin2.disabled = true;
                        horaFin2.required = false;
                        horaFin2.value = '';
                        horaFin2.classList.remove('is-valid', 'is-invalid');
                        removeFieldHelpMessage(horaFin2);
                    }
                }

                // Validar después de cambiar el día
                validateScheduleForDay(dia);
            };

            // Configuración para límites de turnos
            const SHIFT_CONFIG = {
                medioTiempo: {
                    maxHours: 8, // Máximo de horas para un turno de medio tiempo (de 6:00 a 14:00)
                    minHours: 2 // Mínimo de horas para un turno de medio tiempo
                },
                tiempoCompleto: {
                    maxHoursMorning: 8, // Máximo de horas para turno de mañana (de 6:00 a 14:00)
                    maxHoursAfternoon: 8, // Máximo de horas para turno de tarde
                    minHours: 2, // Mínimo de horas para cada turno
                    breakBetweenShifts: 0.5 // Mínimo descanso entre turnos en horas (30 minutos)
                }
            };

            // Función para configurar la validación de horarios cuando cambian los inputs
            function setupScheduleValidation() {
                const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

                diasSemana.forEach(dia => {
                    // Obtener todos los inputs para el día
                    const atiende = document.getElementById(`atiende${dia}`);
                    const modalidad = document.getElementById(`modalidad${dia}`);
                    const horaInicio1 = document.getElementById(`horaInicio${dia}1`);
                    const horaFin1 = document.getElementById(`horaFin${dia}1`);
                    const horaInicio2 = document.getElementById(`horaInicio${dia}2`);
                    const horaFin2 = document.getElementById(`horaFin${dia}2`);

                    if (atiende && modalidad && horaInicio1 && horaFin1 && horaInicio2 && horaFin2) {
                        // Añadir eventos de validación a todos los inputs de tiempo
                        [horaInicio1, horaFin1, horaInicio2, horaFin2].forEach(input => {
                            input.addEventListener('change', () => validateScheduleForDay(dia));
                            input.addEventListener('blur', () => validateScheduleForDay(dia));
                        });

                        // Añadir validación cuando cambia la modalidad
                        modalidad.addEventListener('change', () => {
                            validateScheduleForDay(dia);

                            // Establecer horarios predeterminados según la modalidad seleccionada
                            if (modalidad.value === 'medioTiempo' && atiende.checked) {
                                if (!horaInicio1.value) horaInicio1.value = '06:00';
                                if (!horaFin1.value) horaFin1.value = '12:00';
                            } else if (modalidad.value === 'tiempoCompleto' && atiende.checked) {
                                if (!horaInicio1.value) horaInicio1.value = '06:00';
                                if (!horaFin1.value) horaFin1.value = '12:00';
                                if (!horaInicio2.value) horaInicio2.value = '14:00';
                                if (!horaFin2.value) horaFin2.value = '20:00';
                            }

                            validateScheduleForDay(dia);
                        });

                        // Añadir validación cuando cambia el checkbox "atiende"
                        atiende.addEventListener('change', () => validateScheduleForDay(dia));
                    }
                });
            }

            // Función principal de validación para un día específico
            function validateScheduleForDay(dia) {
                const atiende = document.getElementById(`atiende${dia}`);
                if (!atiende || !atiende.checked) return true;

                const modalidad = document.getElementById(`modalidad${dia}`).value;
                const horaInicio1 = document.getElementById(`horaInicio${dia}1`);
                const horaFin1 = document.getElementById(`horaFin${dia}1`);
                const horaInicio2 = document.getElementById(`horaInicio${dia}2`);
                const horaFin2 = document.getElementById(`horaFin${dia}2`);

                let isValid = true;
                let previouslyValid = true; // Para verificar si ya era válido antes

                // Verificar si ya tenía validación previamente
                if (horaInicio1.classList.contains('is-valid') &&
                    horaFin1.classList.contains('is-valid') &&
                    (modalidad !== 'tiempoCompleto' ||
                        (horaInicio2.classList.contains('is-valid') &&
                            horaFin2.classList.contains('is-valid')))) {
                    previouslyValid = true;
                } else {
                    previouslyValid = false;
                }

                // Reiniciar estilos de validación
                [horaInicio1, horaFin1, horaInicio2, horaFin2].forEach(input => {
                    if (input) {
                        input.classList.remove('is-valid', 'is-invalid');
                        removeFieldHelpMessage(input);
                    }
                });

                // Validación básica de campos obligatorios
                if (!horaInicio1.value) {
                    markFieldAsInvalid(horaInicio1);
                    addFieldHelpMessage(horaInicio1, 'Hora de inicio requerida');
                    isValid = false;
                }

                if (!horaFin1.value) {
                    markFieldAsInvalid(horaFin1);
                    addFieldHelpMessage(horaFin1, 'Hora de fin requerida');
                    isValid = false;
                }

                // Si todos los campos obligatorios están completos, realizar validaciones de tiempo
                if (horaInicio1.value && horaFin1.value) {
                    // Para ambas modalidades, validar primer período de tiempo
                    const validation1 = validateTimePeriod(
                        horaInicio1.value,
                        horaFin1.value,
                        modalidad === 'medioTiempo' ?
                        SHIFT_CONFIG.medioTiempo.maxHours :
                        SHIFT_CONFIG.tiempoCompleto.maxHoursMorning,
                        SHIFT_CONFIG.medioTiempo.minHours,
                        'turno de mañana'
                    );

                    if (!validation1.isValid) {
                        markFieldAsInvalid(validation1.field === 'start' ? horaInicio1 : horaFin1);
                        addFieldHelpMessage(
                            validation1.field === 'start' ? horaInicio1 : horaFin1,
                            validation1.message
                        );
                        isValid = false;

                        // Mostrar toast de error
                        showErrorToast(validation1.message + ` (${dia})`);
                    } else {
                        markFieldAsValid(horaInicio1);
                        markFieldAsValid(horaFin1);
                    }
                }

                // Para tiempo completo, validar también el segundo período y el espacio entre períodos
                if (modalidad === 'tiempoCompleto') {
                    if (!horaInicio2.value) {
                        markFieldAsInvalid(horaInicio2);
                        addFieldHelpMessage(horaInicio2, 'Hora de inicio de tarde requerida');
                        isValid = false;
                    }

                    if (!horaFin2.value) {
                        markFieldAsInvalid(horaFin2);
                        addFieldHelpMessage(horaFin2, 'Hora de fin de tarde requerida');
                        isValid = false;
                    }

                    if (horaInicio2.value && horaFin2.value) {
                        // Validar segundo período de tiempo
                        const validation2 = validateTimePeriod(
                            horaInicio2.value,
                            horaFin2.value,
                            SHIFT_CONFIG.tiempoCompleto.maxHoursAfternoon,
                            SHIFT_CONFIG.tiempoCompleto.minHours,
                            'turno de tarde'
                        );

                        if (!validation2.isValid) {
                            markFieldAsInvalid(validation2.field === 'start' ? horaInicio2 : horaFin2);
                            addFieldHelpMessage(
                                validation2.field === 'start' ? horaInicio2 : horaFin2,
                                validation2.message
                            );
                            isValid = false;

                            // Mostrar toast de error
                            showErrorToast(validation2.message + ` (${dia})`);
                        } else {
                            markFieldAsValid(horaInicio2);
                            markFieldAsValid(horaFin2);
                        }
                    }

                    // Validar espacio entre períodos de tiempo
                    if (horaFin1.value && horaInicio2.value) {
                        const validationGap = validateTimeGap(
                            horaFin1.value,
                            horaInicio2.value,
                            SHIFT_CONFIG.tiempoCompleto.breakBetweenShifts
                        );

                        if (!validationGap.isValid) {
                            markFieldAsInvalid(horaInicio2);
                            addFieldHelpMessage(horaInicio2, validationGap.message);
                            isValid = false;

                            // Mostrar toast de error
                            showErrorToast(validationGap.message + ` (${dia})`);
                        }
                    }

                    // Validar que los períodos de tiempo no se superpongan
                    if (horaInicio1.value && horaFin1.value && horaInicio2.value && horaFin2.value) {
                        const validationOverlap = validateNoOverlap(
                            horaInicio1.value,
                            horaFin1.value,
                            horaInicio2.value,
                            horaFin2.value
                        );

                        if (!validationOverlap.isValid) {
                            markFieldAsInvalid(horaInicio2);
                            markFieldAsInvalid(horaFin1);
                            addFieldHelpMessage(horaInicio2, validationOverlap.message);
                            isValid = false;

                            // Mostrar toast de error
                            showErrorToast(validationOverlap.message + ` (${dia})`);
                        }
                    }
                }

                // Si es válido ahora pero no lo era antes, mostrar notificación de éxito
                if (isValid && !previouslyValid) {
                    // Mostrar toast de éxito
                    const modalidadText = modalidad === 'medioTiempo' ? 'Medio tiempo' : 'Tiempo completo';
                    showSuccessToast(`Horario de ${dia} (${modalidadText}) configurado correctamente`);
                }

                return isValid;
            }
            // Validar un período de tiempo (hora de inicio y fin)
            function validateTimePeriod(startTime, endTime, maxHours, minHours, periodName) {
                // Convertir horas a objetos Date para una comparación más fácil
                const start = new Date(`2000-01-01T${startTime}`);
                const end = new Date(`2000-01-01T${endTime}`);

                // Verificar si la hora de fin es posterior a la hora de inicio
                if (end <= start) {
                    return {
                        isValid: false,
                        field: 'end',
                        message: `La hora de fin debe ser posterior a la hora de inicio para el ${periodName}`
                    };
                }

                // Verificación especial para turno de mañana: hora de fin no puede exceder las 14:00
                if (periodName === 'turno de mañana') {
                    const maxEndTime = new Date(`2000-01-01T14:00`);
                    if (end > maxEndTime) {
                        return {
                            isValid: false,
                            field: 'end',
                            message: `El horario de mañana no puede terminar después de las 14:00 (2:00 PM)`
                        };
                    }
                }

                // Calcular duración en horas
                const durationHours = (end - start) / (1000 * 60 * 60);

                // Verificar si la duración excede el máximo
                if (durationHours > maxHours) {
                    return {
                        isValid: false,
                        field: 'end',
                        message: `El ${periodName} no puede durar más de ${maxHours} horas (actual: ${durationHours.toFixed(1)} horas)`
                    };
                }

                // Verificar si la duración es menor que el mínimo
                if (durationHours < minHours) {
                    return {
                        isValid: false,
                        field: 'end',
                        message: `El ${periodName} debe durar al menos ${minHours} horas (actual: ${durationHours.toFixed(1)} horas)`
                    };
                }

                return {
                    isValid: true
                };
            }




            // Validar el espacio entre dos períodos de tiempo
            function validateTimeGap(firstEnd, secondStart, minHours) {
                // Convertir horas a objetos Date
                const end = new Date(`2000-01-01T${firstEnd}`);
                const start = new Date(`2000-01-01T${secondStart}`);

                // Calcular duración del espacio en horas
                const gapHours = (start - end) / (1000 * 60 * 60);

                // Verificar si hay suficiente espacio
                if (gapHours < minHours) {
                    const minMinutes = minHours * 60;
                    return {
                        isValid: false,
                        message: `Debe haber al menos ${minMinutes} minutos de descanso entre turnos (actual: ${Math.floor(gapHours * 60)} minutos)`
                    };
                }

                // Verificar si el segundo período comienza después de que termine el primero
                if (gapHours <= 0) {
                    return {
                        isValid: false,
                        message: `El horario de tarde debe comenzar después del horario de mañana`
                    };
                }

                return {
                    isValid: true
                };
            }

            // Validar que los períodos de tiempo no se superpongan
            function validateNoOverlap(start1, end1, start2, end2) {
                const timeStart1 = new Date(`2000-01-01T${start1}`);
                const timeEnd1 = new Date(`2000-01-01T${end1}`);
                const timeStart2 = new Date(`2000-01-01T${start2}`);
                const timeEnd2 = new Date(`2000-01-01T${end2}`);

                // Verificar que inicio de turno tarde sea después de las 12:00
                const minStartAfternoon = new Date(`2000-01-01T12:00`);
                if (timeStart2 < minStartAfternoon) {
                    return {
                        isValid: false,
                        message: `El horario de tarde debe comenzar a partir de las 12:00 (mediodía)`
                    };
                }

                // Verificar superposición (tiempo de inicio tarde < tiempo fin mañana)
                if (timeStart2 <= timeEnd1) {
                    return {
                        isValid: false,
                        message: `Los horarios de mañana y tarde no pueden superponerse o ser consecutivos sin descanso`
                    };
                }

                return {
                    isValid: true
                };
            }

            setupScheduleValidation();
            // Función para manejar el cambio de modalidad (Medio tiempo / Tiempo completo)
            window.toggleModalidad = function(dia) {
                const selectModalidad = document.getElementById(`modalidad${dia}`);
                const horaInicio1 = document.getElementById(`horaInicio${dia}1`);
                const horaFin1 = document.getElementById(`horaFin${dia}1`);
                const horaInicio2 = document.getElementById(`horaInicio${dia}2`);
                const horaFin2 = document.getElementById(`horaFin${dia}2`);
                const contenedorTarde = document.querySelector(`#modalidad${dia}`).closest('tr').querySelector('.horario-tarde');

                if (selectModalidad && selectModalidad.value === 'tiempoCompleto') {
                    // Mostrar y habilitar campos del segundo horario (tarde)
                    contenedorTarde.classList.remove('d-none');

                    if (horaInicio2) {
                        horaInicio2.disabled = false;
                        horaInicio2.required = true;

                        // Sugerir una hora de inicio de tarde predeterminada si falta
                        if (!horaInicio2.value && horaFin1.value) {
                            // Establecer 30 minutos después de la hora de fin de la mañana por defecto
                            const morningEnd = new Date(`2000-01-01T${horaFin1.value}`);
                            morningEnd.setMinutes(morningEnd.getMinutes() + 30);
                            const hours = morningEnd.getHours().toString().padStart(2, '0');
                            const minutes = morningEnd.getMinutes().toString().padStart(2, '0');
                            horaInicio2.value = `${hours}:${minutes}`;
                        } else if (!horaInicio2.value) {
                            // Hora de inicio de tarde predeterminada si no hay hora de fin de mañana
                            horaInicio2.value = '14:00';
                        }
                    }

                    if (horaFin2) {
                        horaFin2.disabled = false;
                        horaFin2.required = true;

                        // Sugerir una hora de fin de tarde predeterminada si falta
                        if (!horaFin2.value && horaInicio2.value) {
                            // Establecer 4 horas después de inicio de tarde por defecto
                            const afternoonStart = new Date(`2000-01-01T${horaInicio2.value}`);
                            afternoonStart.setHours(afternoonStart.getHours() + 4);
                            const hours = afternoonStart.getHours().toString().padStart(2, '0');
                            const minutes = afternoonStart.getMinutes().toString().padStart(2, '0');
                            horaFin2.value = `${hours}:${minutes}`;
                        } else if (!horaFin2.value) {
                            // Hora de fin de tarde predeterminada
                            horaFin2.value = '19:00';
                        }
                    }
                } else {
                    // Ocultar y deshabilitar campos del segundo horario (tarde)
                    contenedorTarde.classList.add('d-none');

                    if (horaInicio2) {
                        horaInicio2.disabled = true;
                        horaInicio2.required = false;
                        horaInicio2.value = '';
                        horaInicio2.classList.remove('is-valid', 'is-invalid');
                        removeFieldHelpMessage(horaInicio2);
                    }

                    if (horaFin2) {
                        horaFin2.disabled = true;
                        horaFin2.required = false;
                        horaFin2.value = '';
                        horaFin2.classList.remove('is-valid', 'is-invalid');
                        removeFieldHelpMessage(horaFin2);
                    }
                }

                // Validar después de cambiar la modalidad
                validateScheduleForDay(dia);
            };

            // Nueva función para validar que el segundo horario sea posterior al primero
            function validarHorarioSecuencial(dia) {
                const horaFin1 = document.getElementById(`horaFin${dia}1`);
                const horaInicio2 = document.getElementById(`horaInicio${dia}2`);

                if (horaFin1 && horaInicio2 && horaFin1.value && horaInicio2.value) {
                    // Convertir a objetos Date para comparación
                    const fin1Time = new Date(`2000-01-01T${horaFin1.value}`);
                    const inicio2Time = new Date(`2000-01-01T${horaInicio2.value}`);

                    if (inicio2Time <= fin1Time) {
                        // El segundo horario comienza antes o igual que termina el primero
                        markFieldAsInvalid(horaInicio2);
                        addFieldHelpMessage(horaInicio2, `El horario de tarde debe comenzar después de que termine el horario de mañana (${horaFin1.value})`);

                        // Mostrar una notificación más visible
                        showErrorToast(`El horario de tarde del ${dia} debe comenzar después de las ${horaFin1.value}`);
                        return false;
                    } else {
                        // El horario es correcto
                        markFieldAsValid(horaInicio2);
                        removeFieldHelpMessage(horaInicio2);
                        return true;
                    }
                }
                return true; // Si no están completos ambos campos, se considera válido por ahora
            }

            // Función para cargar los datos en la pantalla de confirmación
            function loadConfirmationData() {
                // Datos personales
                const datosPersonales = {
                    tipodoc: document.getElementById('tipodoc').value,
                    nrodoc: document.getElementById('nrodoc').value,
                    apellidos: document.getElementById('apellidos').value,
                    nombres: document.getElementById('nombres').value,
                    fechanacimiento: document.getElementById('fechanacimiento').value,
                    genero: document.getElementById('genero').value === 'M' ? 'Masculino' : (document.getElementById('genero').value === 'F' ? 'Femenino' : 'Otro'),
                    telefono: document.getElementById('telefono').value,
                    email: document.getElementById('email').value,
                    direccion: document.getElementById('direccion').value
                };

                // Datos profesionales
                const especialidadSelect = document.getElementById('especialidad');
                const especialidadText = especialidadSelect ? especialidadSelect.options[especialidadSelect.selectedIndex].text : '';
                const datosProfesionales = {
                    especialidad: especialidadText,
                    precioatencion: document.getElementById('precioatencion') ? document.getElementById('precioatencion').value : ''
                };

                // Datos de contrato
                const tipocontratoSelect = document.getElementById('tipocontrato');
                const tipocontratoText = tipocontratoSelect ? tipocontratoSelect.options[tipocontratoSelect.selectedIndex].text : '';
                const datosContrato = {
                    tipocontrato: tipocontratoText,
                    fechainicio: document.getElementById('fechainicio') ? document.getElementById('fechainicio').value : '',
                    fechafin: document.getElementById('fechafin') && document.getElementById('fechafin').value ? document.getElementById('fechafin').value : 'No especificado'
                };

                // Datos de horario - VERSIÓN MEJORADA
                const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
                const horarios = [];

                diasSemana.forEach(dia => {
                    const atiende = document.getElementById(`atiende${dia}`);
                    if (atiende && atiende.checked) {
                        const modalidad = document.getElementById(`modalidad${dia}`).value;
                        const horaInicio1 = document.getElementById(`horaInicio${dia}1`).value;
                        const horaFin1 = document.getElementById(`horaFin${dia}1`).value;

                        // Horario principal (mañana)
                        const horarioData = {
                            dia: dia,
                            modalidad: modalidad === 'medioTiempo' ? 'Medio tiempo' : 'Tiempo completo',
                            horarios: [{
                                turno: 'Mañana',
                                inicio: horaInicio1,
                                fin: horaFin1
                            }]
                        };

                        // Si es tiempo completo, agregar también el horario de tarde
                        if (modalidad === 'tiempoCompleto') {
                            const horaInicio2 = document.getElementById(`horaInicio${dia}2`).value;
                            const horaFin2 = document.getElementById(`horaFin${dia}2`).value;

                            horarioData.horarios.push({
                                turno: 'Tarde',
                                inicio: horaInicio2,
                                fin: horaFin2
                            });
                        }

                        horarios.push(horarioData);
                    }
                });

                // Datos de credenciales
                const datosCredenciales = {
                    nomuser: document.getElementById('nomuser') ? document.getElementById('nomuser').value : ''
                };

                // Generar HTML de confirmación
                let html = `
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i> Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tipo de Documento:</strong> ${datosPersonales.tipodoc}</p>
                            <p><strong>Número de Documento:</strong> ${datosPersonales.nrodoc}</p>
                            <p><strong>Apellidos:</strong> ${datosPersonales.apellidos}</p>
                            <p><strong>Nombres:</strong> ${datosPersonales.nombres}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha de Nacimiento:</strong> ${formatDate(datosPersonales.fechanacimiento)}</p>
                            <p><strong>Género:</strong> ${datosPersonales.genero}</p>
                            <p><strong>Teléfono:</strong> ${datosPersonales.telefono}</p>
                            <p><strong>Email:</strong> ${datosPersonales.email}</p>
                            <p><strong>Dirección:</strong> ${datosPersonales.direccion}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-user-md me-2"></i> Información Profesional</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Especialidad:</strong> ${datosProfesionales.especialidad}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Precio de Atención:</strong> S/. ${datosProfesionales.precioatencion}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i> Información de Contrato</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Tipo de Contrato:</strong> ${datosContrato.tipocontrato}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Fecha de Inicio:</strong> ${formatDate(datosContrato.fechainicio)}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Fecha de Fin:</strong> ${datosContrato.fechafin === 'No especificado' ? datosContrato.fechafin : formatDate(datosContrato.fechafin)}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Horario de Atención</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Día</th>
                                    <th>Modalidad</th>
                                    <th>Turno</th>
                                    <th>Hora Inicio</th>
                                    <th>Hora Fin</th>
                                </tr>
                            </thead>
                            <tbody>`;

                if (horarios.length > 0) {
                    horarios.forEach(horario => {
                        // Para cada horario (que puede incluir mañana y tarde)
                        horario.horarios.forEach((turno, index) => {
                            html += `
                <tr>
                    ${index === 0 ? `<td rowspan="${horario.horarios.length}">${horario.dia}</td>` : ''}
                    ${index === 0 ? `<td rowspan="${horario.horarios.length}">${horario.modalidad}</td>` : ''}
                    <td>${turno.turno}</td>
                    <td>${formatTime(turno.inicio)}</td>
                    <td>${formatTime(turno.fin)}</td>
                </tr>`;
                        });
                    });
                } else {
                    html += `
                <tr>
                    <td colspan="5" class="text-center">No se ha configurado ningún horario</td>
                </tr>`;
                }

                html += `
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-key me-2"></i> Credenciales de Acceso</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre de Usuario:</strong> ${datosCredenciales.nomuser}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Contraseña:</strong> ********</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>`;

                const resumenRegistro = document.getElementById('resumenRegistro');
                if (resumenRegistro) {
                    resumenRegistro.innerHTML = html;
                }
            }
            // Función para buscar doctor por documento - Versión mejorada
            function buscarDoctorPorDocumento() {
                console.log("Función buscarDoctorPorDocumento iniciada");

                const tipodoc = document.getElementById('tipodoc').value;
                const nrodoc = document.getElementById('nrodoc').value;

                if (!tipodoc || !nrodoc) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Seleccione un tipo de documento e ingrese un número de documento para buscar.'
                    });
                    return;
                }

                // Validar formato según tipo de documento
                const config = documentoConfig[tipodoc];
                if (config && !config.pattern.test(nrodoc)) {
                    markFieldAsInvalid(document.getElementById('nrodoc'));
                    addFieldHelpMessage(document.getElementById('nrodoc'), config.message);
                    showErrorToast(config.message);
                    return;
                }

                console.log(`Buscando documento ${tipodoc}: ${nrodoc}`);

                // Mostrar loader con efecto de búsqueda
                Swal.fire({
                    title: 'Buscando...',
                    html: `
            <p>Verificando documento ${tipodoc}: ${nrodoc}</p>
            <div class="progress mt-3" style="height: 20px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" 
                     style="width: 100%" 
                     aria-valuenow="100" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                </div>
            </div>
            <p class="mt-2">Por favor espere mientras se busca el doctor.</p>
        `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Simular tiempo de carga mínimo para mostrar la animación (al menos 1 segundo)
                setTimeout(() => {
                    // *** PASO 1: Verificar si ya existe como doctor ***
                    const formData = new FormData();
                    formData.append('nrodoc', nrodoc);

                    fetch('../../../controllers/doctor.controller.php?op=verificar', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log("Respuesta verificación doctor:", data);

                            if (data.existe) {
                                // Ya existe como doctor - Mostrar mensaje y bloquear campos
                                Swal.close();

                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Doctor ya registrado',
                                    text: 'Este documento ya está registrado como doctor en el sistema.',
                                    showCancelButton: true,
                                    confirmButtonText: 'Ver detalles',
                                    cancelButtonText: 'Intentar otro documento'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redirigir a la vista de detalles del doctor
                                        window.location.href = '../ListarDoctor/listarDoctor.php';
                                    } else {
                                        // Limpiar el campo de documento para intentar con otro
                                        document.getElementById('nrodoc').value = '';
                                        document.getElementById('nrodoc').focus();
                                    }
                                });

                                // Mantener los campos bloqueados
                                bloquearCampos();
                                limpiarCampos(); // Limpiar campos al no encontrar datos

                                // Mostrar mensaje en toast
                                showErrorToast('Este documento ya está registrado como doctor');
                            } else {
                                // No existe como doctor, pero podría estar registrado como persona/paciente
                                // *** PASO 2: Verificar si existe como persona en el sistema ***
                                fetch(`../../../controllers/persona.controller.php?op=buscar_por_documento&nrodoc=${nrodoc}`)
                                    .then(response => response.json())
                                    .then(personaData => {
                                        Swal.close();
                                        console.log("Respuesta búsqueda persona:", personaData);

                                        if (personaData.status && personaData.persona) {
                                            // Existe como persona pero no como doctor - Mostrar sus datos en los campos
                                            Swal.fire({
                                                icon: 'info',
                                                title: 'Persona encontrada',
                                                text: 'Esta persona ya está registrada en el sistema. Se cargarán sus datos personales.',
                                                confirmButtonText: 'Continuar'
                                            });

                                            // Cargar datos de la persona en el formulario
                                            cargarDatosPersona(personaData.persona);

                                            // Bloquear campos de datos personales pero habilitar los profesionales
                                            bloquearCamposConDatos();

                                            // Habilitar botón siguiente para continuar con el registro como doctor
                                            const btnSiguiente = document.getElementById("btnSiguiente");
                                            if (btnSiguiente) {
                                                btnSiguiente.disabled = false;
                                                btnSiguiente.classList.remove("disabled");
                                            }

                                            showSuccessToast('Datos personales cargados. Complete la información profesional.');
                                        } else {
                                            // No existe en el sistema - Permitir el registro completo
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Documento disponible',
                                                text: 'Este documento no está registrado en el sistema. Puede continuar con el registro completo.',
                                                confirmButtonText: 'Continuar'
                                            });

                                            // Desbloquear los campos para el registro
                                            desbloquearCampos();
                                            limpiarCampos(); // Limpiar campos al no encontrar datos

                                            showSuccessToast('Documento disponible, puede continuar con el registro');
                                        }
                                    })
                                    .catch(error => {
                                        Swal.close();
                                        console.error('Error al buscar persona:', error);

                                        // En caso de error en la búsqueda, permitir el registro normal
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Error en la búsqueda',
                                            text: 'No se pudo verificar si la persona existe. Puede continuar con el registro.',
                                            confirmButtonText: 'Continuar'
                                        });

                                        // Desbloquear los campos para el registro
                                        desbloquearCampos();
                                        limpiarCampos(); // Limpiar campos al no encontrar datos
                                    });
                            }
                        })
                        .catch(error => {
                            Swal.close();
                            console.error('Error al verificar doctor:', error);

                            Swal.fire({
                                icon: 'error',
                                title: 'Error de conexión',
                                text: 'No se pudo verificar el documento. Intente nuevamente.'
                            });

                            // Mantener los campos bloqueados en caso de error
                            bloquearCampos();
                            limpiarCampos(); // Limpiar campos al no encontrar datos
                        });
                }, 1000);
            }
            // Función para verificar y habilitar campos incompletos
            function verificarCamposIncompletos(persona) {
                console.log("Verificando campos incompletos para:", persona);

                // Definir los campos obligatorios para doctor
                const camposObligatorios = [
                    'apellidos',
                    'nombres',
                    'fechanacimiento',
                    'genero',
                    'telefono',
                    'email',
                    'direccion'
                ];

                // Verificar cada campo
                camposObligatorios.forEach(campo => {
                    const input = document.getElementById(campo);

                    // Si el campo existe en el formulario
                    if (input) {
                        // Verificar si está vacío o es null/undefined en los datos de la persona
                        if (!persona[campo] || persona[campo].trim() === '') {
                            console.log(`Campo incompleto detectado: ${campo}`);

                            // Habilitar el campo para edición
                            input.disabled = false;

                            // Marcar visualmente como requerido
                            input.classList.add('border-danger');

                            // Agregar mensaje indicando que debe completarse
                            addFieldHelpMessage(input, 'Complete este campo obligatorio');

                            // Marcar como inválido
                            markFieldAsInvalid(input);
                        } else {
                            // Si tiene datos, mantenerlo deshabilitado pero marcarlo como válido
                            input.disabled = true;
                            markFieldAsValid(input);
                        }
                    }
                });
                const emailInput = document.getElementById('email');
                if (emailInput) {
                    if (!persona.email || persona.email.trim() === '') {
                        // Si no tiene email, habilitar para edición
                        emailInput.disabled = false;
                        emailInput.classList.add('border-danger');
                        addFieldHelpMessage(emailInput, 'Complete este campo obligatorio');
                        markFieldAsInvalid(emailInput);
                    } else if (!validateEmail(persona.email)) {
                        // Si tiene email pero es inválido
                        emailInput.disabled = false;
                        emailInput.classList.add('border-danger');
                        addFieldHelpMessage(emailInput, 'El formato del correo es incorrecto');
                        markFieldAsInvalid(emailInput);
                    } else {
                        // Si el email es válido
                        emailInput.disabled = true;
                        markFieldAsValid(emailInput);
                        removeFieldHelpMessage(emailInput);
                        // Asegurar que no tenga clase border-danger
                        emailInput.classList.remove('border-danger');
                    }
                }

                // Si hay al menos un campo habilitado, mostrar mensaje informativo
                const camposHabilitados = document.querySelectorAll('input.border-danger, select.border-danger');
                if (camposHabilitados.length > 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Información incompleta',
                        text: 'Se han habilitado algunos campos que requieren completarse para el registro del doctor.',
                        confirmButtonText: 'Entendido'
                    });

                    // Hacer scroll al primer campo incompleto
                    camposHabilitados[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }

            // Función para cargar los datos de la persona en el formulario
            function cargarDatosPersona(persona) {
                // Cargar datos básicos en los campos correspondientes
                document.getElementById('apellidos').value = persona.apellidos || '';
                document.getElementById('nombres').value = persona.nombres || '';
                document.getElementById('fechanacimiento').value = persona.fechanacimiento || '';
                document.getElementById('genero').value = persona.genero || '';
                document.getElementById('telefono').value = persona.telefono || '';
                document.getElementById('email').value = persona.email || '';
                document.getElementById('direccion').value = persona.direccion || '';

                // Marcar todos los campos como válidos inicialmente
                const campos = ['apellidos', 'nombres', 'fechanacimiento', 'genero', 'telefono', 'email', 'direccion'];
                campos.forEach(campo => {
                    const input = document.getElementById(campo);
                    if (input && input.value) {
                        markFieldAsValid(input);
                        removeFieldHelpMessage(input);
                    }
                });

                // Verificar si hay campos incompletos y habilitarlos
                verificarCamposIncompletos(persona);

                // Actualizar el nombre del doctor si no estamos en la pestaña de información personal
                if (currentTab !== 'personalInfo' && persona.nombres && persona.apellidos) {
                    document.getElementById('doctorNameText').textContent = `${persona.nombres} ${persona.apellidos}`;
                    document.getElementById('doctorNameDisplay').classList.remove('d-none');
                }
                // Validación específica para el correo electrónico
                const emailInput = document.getElementById('email');
                if (emailInput && persona.email) {
                    if (validateEmail(persona.email)) {
                        markFieldAsValid(emailInput);
                        removeFieldHelpMessage(emailInput);
                        emailInput.classList.remove('border-danger');
                    } else {
                        // Si el formato del correo es incorrecto
                        markFieldAsInvalid(emailInput);
                        addFieldHelpMessage(emailInput, 'El formato del correo es incorrecto');
                        emailInput.disabled = false; // Habilitar para corregir
                        emailInput.classList.add('border-danger');
                    }
                }

                console.log("Datos de persona cargados en el formulario con verificación de campos incompletos");
            }

            // Función para bloquear campos pero mostrando los datos (cuando se encuentra una persona)
            function bloquearCamposConDatos() {
                const camposABloquear = [
                    'apellidos', 'nombres', 'fechanacimiento', 'genero', 'telefono', 'email', 'direccion'
                ];

                camposABloquear.forEach(campo => {
                    const input = document.getElementById(campo);
                    if (input) {
                        // No bloquear si tiene la clase border-danger (fue marcado como incompleto)
                        if (!input.classList.contains('border-danger')) {
                            input.disabled = true;
                        }
                    }
                });

                // El campo de especialidad debe estar habilitado
                document.getElementById('especialidad').disabled = false;

                // El precio de atención siempre debe permanecer readonly, no disabled
                const precioInput = document.getElementById('precioatencion');
                if (precioInput) {
                    precioInput.readOnly = true;
                    precioInput.disabled = false;
                }

                // Habilitar botones relacionados con la especialidad
                document.getElementById('btnAgregarEspecialidad').disabled = false;
                document.getElementById('btnEditarPrecio').disabled = false;

                console.log("Campos bloqueados manteniendo habilitados los que faltan completar");
            }
            // Función para limpiar todos los campos del formulario
            function limpiarCampos() {
                const camposALimpiar = [
                    'apellidos', 'nombres', 'fechanacimiento', 'genero', 'telefono', 'email', 'direccion'
                ];

                camposALimpiar.forEach(campo => {
                    const input = document.getElementById(campo);
                    if (input) {
                        input.value = '';
                        input.classList.remove('is-valid', 'is-invalid');
                        removeFieldHelpMessage(input);
                    }
                });

                // Restablecer valores por defecto
                document.getElementById("genero").value = "M";

                console.log("Campos limpiados correctamente");
            }

            // Función para bloquear todos los campos excepto tipo y número de documento
            function bloquearCampos() {
                const camposABloquear = [
                    'apellidos', 'nombres', 'fechanacimiento',
                    'genero', 'telefono', 'email', 'direccion',
                    'especialidad', 'precioatencion'
                ];

                camposABloquear.forEach(campo => {
                    const input = document.getElementById(campo);
                    if (input) {
                        input.disabled = true;
                        input.classList.remove('is-valid', 'is-invalid');
                        removeFieldHelpMessage(input);
                    }
                });

                // Deshabilitar botones relacionados con la especialidad
                document.getElementById('btnAgregarEspecialidad').disabled = true;
                document.getElementById('btnEditarPrecio').disabled = true;

                // Deshabilitar el botón siguiente
                const btnSiguiente = document.getElementById("btnSiguiente");
                if (btnSiguiente) {
                    btnSiguiente.disabled = true;
                    btnSiguiente.classList.add("disabled");
                }
            }

            // Función para desbloquear todos los campos
            function desbloquearCampos() {
                const camposADesbloquear = [
                    'apellidos', 'nombres', 'fechanacimiento',
                    'genero', 'telefono', 'email', 'direccion',
                    'especialidad' // No incluir 'precioatencion'
                ];

                camposADesbloquear.forEach(campo => {
                    const input = document.getElementById(campo);
                    if (input) {
                        input.disabled = false;
                        // Agregar evento para verificar campos en tiempo real
                        input.addEventListener('input', checkPersonalFields);
                        input.addEventListener('change', checkPersonalFields);
                    }
                });

                // El precio de atención siempre debe permanecer readonly
                const precioInput = document.getElementById('precioatencion');
                if (precioInput) {
                    precioInput.readOnly = true;
                }

                // Habilitar botones relacionados con la especialidad
                document.getElementById('btnAgregarEspecialidad').disabled = false;
                document.getElementById('btnEditarPrecio').disabled = false;

                // Habilitar el botón siguiente
                const btnSiguiente = document.getElementById("btnSiguiente");
                if (btnSiguiente) {
                    btnSiguiente.disabled = false;
                    btnSiguiente.classList.remove("disabled");
                }
            }
            // Función para guardar doctor - Versión modificada que combina información personal y profesional
            function guardarDoctor() {
                // Validar todos los pasos antes de guardar
                if (!validateAllSteps()) {
                    return;
                }

                // Mostrar loader
                Swal.fire({
                    title: 'Guardando...',
                    html: 'Por favor espere mientras se registra el doctor.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Proceso de registro unificado
                registrarDoctorCompleto();
            }

            // Función para registrar la información personal y profesional en un solo paso
            function registrarDoctorCompleto() {
                const formData = new FormData();

                // Datos personales
                formData.append('apellidos', document.getElementById('apellidos').value);
                formData.append('nombres', document.getElementById('nombres').value);
                formData.append('tipodoc', document.getElementById('tipodoc').value);
                formData.append('nrodoc', document.getElementById('nrodoc').value);
                formData.append('telefono', document.getElementById('telefono').value);
                formData.append('fechanacimiento', document.getElementById('fechanacimiento').value);
                formData.append('genero', document.getElementById('genero').value);
                formData.append('direccion', document.getElementById('direccion').value);
                formData.append('email', document.getElementById('email').value);

                // Datos profesionales
                formData.append('idespecialidad', document.getElementById('especialidad').value);
                formData.append('precioatencion', document.getElementById('precioatencion').value);

                // Enviar datos unificados
                fetch('../../../controllers/doctor.controller.php?op=registrar', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Respuesta de registro doctor:", data);

                        if (data.status) {
                            // Actualizar mensaje de carga
                            Swal.update({
                                title: 'Registrando...',
                                html: 'Procesando información de contrato'
                            });

                            // Continuar con el registro del contrato
                            registrarContrato(data.idcolaborador);
                        } else {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al registrar',
                                text: data.mensaje || 'No se pudo registrar la información del doctor.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al registrar doctor:', error);
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'No se pudo completar el registro del doctor.'
                        });
                    });
            }

            // Función para deshabilitar inicialmente toda la sección de horarios
            function deshabilitarSeccionHorarios() {
                console.log("Deshabilitando sección de horarios");
                const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

                // Deshabilitar todos los switches de días
                diasSemana.forEach(dia => {
                    const atiende = document.getElementById(`atiende${dia}`);
                    if (atiende) {
                        atiende.checked = false;
                        atiende.disabled = true;

                        // Asegurarse que los selectores de modalidad y campos de horario estén deshabilitados
                        const modalidad = document.getElementById(`modalidad${dia}`);
                        if (modalidad) modalidad.disabled = true;

                        const horaInicio1 = document.getElementById(`horaInicio${dia}1`);
                        const horaFin1 = document.getElementById(`horaFin${dia}1`);
                        const horaInicio2 = document.getElementById(`horaInicio${dia}2`);
                        const horaFin2 = document.getElementById(`horaFin${dia}2`);

                        if (horaInicio1) horaInicio1.disabled = true;
                        if (horaFin1) horaFin1.disabled = true;
                        if (horaInicio2) horaInicio2.disabled = true;
                        if (horaFin2) horaFin2.disabled = true;
                    }
                });

                // Eliminar cualquier mensaje previo sobre días habilitados
                const mensajeDiasHabilitados = document.getElementById('mensajeDiasHabilitados');
                if (mensajeDiasHabilitados) {
                    mensajeDiasHabilitados.remove();
                }

                // Agregar mensaje indicando que se debe confirmar el contrato primero
                const tablaHorarios = document.querySelector('.table-responsive');
                if (tablaHorarios) {
                    // Eliminar mensaje previo si existe
                    const mensajeConfirmarPrevio = document.getElementById('mensajeConfirmarContrato');
                    if (mensajeConfirmarPrevio) {
                        mensajeConfirmarPrevio.remove();
                    }

                    // Crear nuevo mensaje
                    const mensajeInfo = document.createElement('div');
                    mensajeInfo.id = 'mensajeConfirmarContrato';
                    mensajeInfo.className = 'alert alert-warning mt-3 mb-3';
                    mensajeInfo.innerHTML = '<i class="fas fa-info-circle me-2"></i> Primero debe completar y confirmar la información del contrato para habilitar los horarios de atención.';

                    // Insertar antes de la tabla
                    tablaHorarios.parentNode.insertBefore(mensajeInfo, tablaHorarios);

                    // También agregar un estilo visual para indicar que está deshabilitado
                    tablaHorarios.classList.add('opacity-50');
                }
            }
            // Función corregida para habilitar los días según las fechas del contrato
            function habilitarDiasSegunFechasContrato() {
                console.log("Ejecutando habilitarDiasSegunFechasContrato()"); // Log para depuración

                const tipoContrato = document.getElementById('tipocontrato').value;
                const fechaInicioStr = document.getElementById('fechainicio').value;
                const fechaFinStr = document.getElementById('fechafin').value;

                if (!fechaInicioStr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe seleccionar una fecha de inicio para el contrato'
                    });
                    return false;
                }

                // Verificar si es contrato indefinido o si tiene fecha de fin válida
                if (tipoContrato !== 'INDEFINIDO' && !fechaFinStr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe seleccionar una fecha de fin para este tipo de contrato'
                    });
                    return false;
                }

                // Convertir fechas a objetos Date asegurando formato correcto
                const fechaInicio = new Date(fechaInicioStr + 'T00:00:00');
                let fechaFin = null;

                if (fechaFinStr) {
                    fechaFin = new Date(fechaFinStr + 'T23:59:59');
                }

                // Verificar si la fecha fin es anterior a la fecha inicio
                if (fechaFin && fechaFin < fechaInicio) {
                    // La fecha fin es anterior a la fecha inicio
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en fechas',
                        text: 'La fecha de fin no puede ser anterior a la fecha de inicio'
                    });

                    // También marcar visualmente el campo como inválido
                    const fechaFinInput = document.getElementById('fechafin');
                    markFieldAsInvalid(fechaFinInput);
                    addFieldHelpMessage(fechaFinInput, 'La fecha de fin no puede ser anterior a la fecha de inicio');

                    return false;
                }

                console.log("Fecha inicio:", fechaInicio.toISOString());
                console.log("Fecha fin:", fechaFin ? fechaFin.toISOString() : "Indefinido");

                // Obtener los días de la semana correspondientes
                const diasHabilitados = obtenerDiasHabilitados(fechaInicio, fechaFin);
                console.log("Días habilitados:", Array.from(diasHabilitados));

                // Remover el mensaje de confirmar contrato y la opacidad
                const mensajeConfirmar = document.getElementById('mensajeConfirmarContrato');
                if (mensajeConfirmar) {
                    mensajeConfirmar.remove();
                }

                const tablaHorarios = document.querySelector('.table-responsive');
                if (tablaHorarios) {
                    tablaHorarios.classList.remove('opacity-50');
                }

                // Habilitar/deshabilitar días según corresponda
                activarDiasHabilitados(diasHabilitados);

                // Mostrar un mensaje con los días habilitados
                mostrarMensajeDiasHabilitados(diasHabilitados);

                return true;
            }

            // Función corregida para obtener los días habilitados según las fechas
            function obtenerDiasHabilitados(fechaInicio, fechaFin) {
                // Nombres de días exactamente como se usan en la interfaz
                const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
                const diasHabilitados = new Set();

                // Si no hay fecha fin (contrato indefinido), habilitar todos los días
                if (!fechaFin) {
                    diasSemana.forEach(dia => diasHabilitados.add(dia));
                    return diasHabilitados;
                }

                // IMPORTANTE: Asegurar que las fechas tienen el formato correcto
                // y están completas con información de hora
                const inicio = new Date(fechaInicio);
                const fin = new Date(fechaFin);

                // Asegurar que las horas estén correctamente configuradas
                inicio.setHours(0, 0, 0, 0);
                fin.setHours(23, 59, 59, 999);

                console.log(`FECHA INICIO: ${inicio.toISOString()} - Día: ${diasSemana[inicio.getDay()]}`);
                console.log(`FECHA FIN: ${fin.toISOString()} - Día: ${diasSemana[fin.getDay()]}`);

                // Recorrer cada día en el rango, INCLUYENDO la fecha inicio y fin
                let currentDate = new Date(inicio);

                while (currentDate <= fin) {
                    // Obtener el día de la semana (0-6) y convertirlo al nombre del día
                    const nombreDia = diasSemana[currentDate.getDay()];
                    diasHabilitados.add(nombreDia);

                    // Log detallado para verificar cada día
                    console.log(`Habilitando: ${currentDate.toISOString()} - ${nombreDia}`);

                    // Avanzar un día
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                return diasHabilitados;
            }
            // Función corregida para activar solo los días habilitados
            function activarDiasHabilitados(diasHabilitados) {
                console.log("DÍAS A HABILITAR:", Array.from(diasHabilitados));

                const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

                // Primero, resetear todos los días (deshabilitar todos)
                diasSemana.forEach(dia => {
                    const atiende = document.getElementById(`atiende${dia}`);
                    if (atiende) {
                        atiende.disabled = true;
                        atiende.checked = false;

                        // Desactivar todos los campos relacionados
                        toggleHorario(dia);

                        // Eliminar marcas visuales
                        const fila = atiende.closest('tr');
                        if (fila) {
                            fila.classList.remove('bg-light-success', 'bg-light-secondary');
                        }
                    }
                });

                // Ahora, habilitar SOLO los días que corresponden al rango de fechas
                diasSemana.forEach(dia => {
                    const atiende = document.getElementById(`atiende${dia}`);
                    if (atiende) {
                        if (diasHabilitados.has(dia)) {
                            // Habilitar el checkbox
                            atiende.disabled = false;

                            // Añadir marca visual
                            const fila = atiende.closest('tr');
                            if (fila) {
                                fila.classList.add('bg-light-success');
                                fila.classList.remove('bg-light-secondary');
                                fila.setAttribute('title', 'Día incluido en el período del contrato');
                            }

                            console.log(`Habilitado día: ${dia}`);
                        } else {
                            // Añadir marca visual para días no disponibles
                            const fila = atiende.closest('tr');
                            if (fila) {
                                fila.classList.add('bg-light-secondary');
                                fila.classList.remove('bg-light-success');
                                fila.setAttribute('title', 'Día no incluido en el período del contrato');
                            }

                            console.log(`Deshabilitado día: ${dia}`);
                        }
                    }
                });
            }

            // Función para mostrar mensaje con los días habilitados
            function mostrarMensajeDiasHabilitados(diasHabilitados) {
                const diasArray = Array.from(diasHabilitados).sort((a, b) => {
                    const orden = {
                        'Lunes': 1,
                        'Martes': 2,
                        'Miercoles': 3,
                        'Jueves': 4,
                        'Viernes': 5,
                        'Sabado': 6,
                        'Domingo': 7
                    };
                    return orden[a] - orden[b];
                });

                let mensaje = '';

                if (diasArray.length === 7) {
                    mensaje = 'Se han habilitado todos los días de la semana.';
                } else {
                    mensaje = `Se han habilitado los siguientes días: <strong>${diasArray.join(', ')}</strong>.`;
                }

                // Eliminar mensaje anterior si existe
                const mensajeAnterior = document.getElementById('mensajeDiasHabilitados');
                if (mensajeAnterior) {
                    mensajeAnterior.remove();
                }

                // Añadir mensaje en la interfaz
                const tablaHorarios = document.querySelector('.table-responsive');
                if (tablaHorarios) {
                    // Crear nuevo mensaje
                    const mensajeInfo = document.createElement('div');
                    mensajeInfo.id = 'mensajeDiasHabilitados';
                    mensajeInfo.className = 'alert alert-info mt-3 mb-3';
                    mensajeInfo.innerHTML = `<i class="fas fa-calendar-check me-2"></i> ${mensaje}`;

                    // Insertar antes de la tabla
                    tablaHorarios.parentNode.insertBefore(mensajeInfo, tablaHorarios);
                }

                // También mostrar notificación
                showSuccessToast(`Horarios activados. ${diasArray.length === 7 ? 'Todos los días disponibles.' : `Días habilitados: ${diasArray.join(', ')}.`}`);
            }

            // Función para configurar el botón de confirmar contrato
            function setupConfirmarContratoButton() {
                const btnConfirmarContrato = document.getElementById('btnConfirmarContrato');
                if (btnConfirmarContrato) {
                    btnConfirmarContrato.addEventListener('click', function() {
                        console.log("Botón confirmar contrato presionado"); // Agregar log para depuración

                        // Validar que el contrato esté correctamente configurado
                        const tipoContrato = document.getElementById('tipocontrato').value;
                        const fechaInicio = document.getElementById('fechainicio').value;
                        const fechaFin = document.getElementById('fechafin').value;

                        console.log("Valores de contrato:", {
                            tipoContrato,
                            fechaInicio,
                            fechaFin
                        });

                        // Verificaciones básicas
                        if (!tipoContrato) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Contrato incompleto',
                                text: 'Debe seleccionar un tipo de contrato'
                            });
                            return;
                        }

                        if (!fechaInicio) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Contrato incompleto',
                                text: 'Debe seleccionar una fecha de inicio'
                            });
                            return;
                        }

                        // Si no es indefinido, verificar fecha de fin
                        if (tipoContrato !== 'INDEFINIDO' && !fechaFin) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Contrato incompleto',
                                text: 'Debe seleccionar una fecha de fin para este tipo de contrato'
                            });
                            return;
                        }

                        // CORRECCIÓN: No llamar a validateContratoInfo() ya que podría tener validaciones adicionales
                        // innecesarias que están causando problemas

                        // Habilitar los días según las fechas del contrato directamente
                        if (habilitarDiasSegunFechasContrato()) {
                            // Mostrar un indicador visual de confirmación
                            mostrarIndicadorContratoConfirmado();

                            // Mostrar una notificación toast
                            showSuccessToast('Contrato confirmado. Se han activado los horarios correspondientes.');

                            // Limpiar los checkboxes de horarios
                            limpiarSeleccionHorarios();

                            // Hacer scroll hasta la sección de horarios
                            const seccionHorarios = document.querySelector('.table-responsive');
                            if (seccionHorarios) {
                                seccionHorarios.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        }
                    });
                }
            }


            function mostrarIndicadorContratoConfirmado() {
                // Ya no mostraremos ningún indicador visual al lado del botón
                // Solo mostraremos la notificación toast
                showSuccessToast('Contrato confirmado. Se han activado los horarios correspondientes.');
            }

            // Función para limpiar todos los checkboxes de horarios
            function limpiarSeleccionHorarios() {
                const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

                diasSemana.forEach(dia => {
                    const atiende = document.getElementById(`atiende${dia}`);
                    if (atiende && !atiende.disabled) {
                        atiende.checked = false;
                        toggleHorario(dia); // Esto deshabilitará los campos de horario
                    }
                });
            }

            // Asegurar que no haya mensajes de advertencia duplicados al inicio
            limpiarMensajesAdvertencia();

            // Deshabilitar inicialmente la sección de horarios (con un solo mensaje)
            deshabilitarSeccionHorarios();

            // Configurar el botón de confirmar contrato
            setupConfirmarContratoButton();

            // Agregar clases necesarias para los estilos
            // Añadir estos estilos en línea para no tener que modificar el CSS
            const style = document.createElement('style');
            style.textContent = `
        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.15) !important;
        }
        .bg-light-secondary {
            background-color: rgba(173, 181, 189, 0.15) !important;
        }
        .opacity-50 {
            opacity: 0.5;
        }
        /* Animaciones para el indicador de contrato confirmado */
        .animate__animated {
            animation-duration: 1s;
            animation-fill-mode: both;
        }
        .animate__fadeIn {
            animation-name: fadeIn;
        }
        .animate__fadeOut {
            animation-name: fadeOut;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    `;
            document.head.appendChild(style);

            // Función para resetear el estado de los mensajes de horarios
            function resetearMensajesHorarios() {
                // Eliminar el mensaje "Se han habilitado todos los días de la semana"
                const mensajeDiasHabilitados = document.getElementById('mensajeDiasHabilitados');
                if (mensajeDiasHabilitados) {
                    mensajeDiasHabilitados.remove();
                }

                // Eliminar cualquier otro mensaje que pueda haber sido añadido
                const mensajeConfirmar = document.getElementById('mensajeConfirmarContrato');
                if (mensajeConfirmar) {
                    mensajeConfirmar.remove();
                }

                // Restaurar el mensaje de advertencia original
                const tablaHorarios = document.querySelector('.table-responsive');
                if (tablaHorarios) {
                    // Volver a añadir opacidad a la tabla
                    tablaHorarios.classList.add('opacity-50');

                    // Volver a añadir el mensaje de advertencia original si no existe
                    if (!document.getElementById('mensajeConfirmarContrato')) {
                        const mensajeInfo = document.createElement('div');
                        mensajeInfo.id = 'mensajeConfirmarContrato';
                        mensajeInfo.className = 'alert alert-warning mt-3 mb-3';
                        mensajeInfo.innerHTML = '<i class="fas fa-info-circle me-2"></i> Primero debe completar y confirmar la información del contrato para habilitar los horarios de atención.';

                        // Insertar antes de la tabla
                        tablaHorarios.parentNode.insertBefore(mensajeInfo, tablaHorarios);
                    }
                }
            }
            // Configurar evento de cambio de tipo de contrato para limpiar fechas
            const tipoContratoSelect = document.getElementById('tipocontrato');
            if (tipoContratoSelect) {
                tipoContratoSelect.addEventListener('change', function() {
                    // Limpiar campos de fecha
                    const fechaInicio = document.getElementById('fechainicio');
                    const fechaFin = document.getElementById('fechafin');

                    if (fechaInicio) fechaInicio.value = '';
                    if (fechaFin) fechaFin.value = '';

                    // Si es tipo indefinido, deshabilitar fecha fin
                    if (this.value === 'INDEFINIDO') {
                        if (fechaFin) {
                            fechaFin.disabled = true;
                            fechaFin.value = '';
                            // Agregar mensaje visual
                            const helpText = document.createElement('small');
                            helpText.id = 'helpTextFechaFin';
                            helpText.className = 'text-muted';
                            helpText.textContent = 'No aplica para contratos indefinidos';

                            // Eliminar mensaje previo si existe
                            const prevHelpText = document.getElementById('helpTextFechaFin');
                            if (prevHelpText) prevHelpText.remove();

                            // Agregar nuevo mensaje
                            if (fechaFin.parentNode) {
                                fechaFin.parentNode.appendChild(helpText);
                            }
                        }
                    } else {
                        // Para otros tipos, habilitar fecha fin
                        if (fechaFin) {
                            fechaFin.disabled = false;

                            // Eliminar mensaje si existe
                            const helpText = document.getElementById('helpTextFechaFin');
                            if (helpText) helpText.remove();
                        }
                    }

                    // Limpiar mensajes de advertencia para evitar duplicación
                    limpiarMensajesAdvertencia();

                    // Resetear los mensajes de horarios (ESTE ES EL CAMBIO)
                    resetearMensajesHorarios();

                    // Deshabilitar los horarios al cambiar el tipo de contrato (sin duplicar mensajes)
                    deshabilitarSeccionHorarios();

                    // Resetear todos los días a "Medio tiempo"
                    resetearHorariosAMedioTiempo();
                });
            }
            // Eventos para cuando se cambian las fechas
            const fechaInicioInput = document.getElementById('fechainicio');
            const fechaFinInput = document.getElementById('fechafin');

            if (fechaInicioInput) {
                fechaInicioInput.addEventListener('change', function() {
                    // Limpiar mensajes de advertencia para evitar duplicación
                    limpiarMensajesAdvertencia();

                    // Deshabilitar los horarios al cambiar la fecha de inicio
                    deshabilitarSeccionHorarios();

                    // Establecer fecha mínima para fecha fin (1 día después)
                    if (fechaFinInput && this.value) {
                        const fechaInicio = new Date(this.value);
                        fechaInicio.setDate(fechaInicio.getDate() + 1);

                        // Formatear fecha para atributo min
                        const yyyy = fechaInicio.getFullYear();
                        const mm = String(fechaInicio.getMonth() + 1).padStart(2, '0');
                        const dd = String(fechaInicio.getDate()).padStart(2, '0');

                        fechaFinInput.min = `${yyyy}-${mm}-${dd}`;

                        // Si la fecha fin es anterior a la nueva fecha mínima, limpiarla
                        if (fechaFinInput.value && new Date(fechaFinInput.value) < fechaInicio) {
                            fechaFinInput.value = '';
                        }
                    }
                });
            }

            if (fechaFinInput) {
                fechaFinInput.addEventListener('change', function() {
                    // Limpiar mensajes de advertencia para evitar duplicación
                    limpiarMensajesAdvertencia();

                    // Deshabilitar los horarios al cambiar la fecha de fin
                    deshabilitarSeccionHorarios();
                });
            }
            // Función para resetear todos los horarios a "Medio tiempo"
            function resetearHorariosAMedioTiempo() {
                const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

                diasSemana.forEach(dia => {
                    // Resetear checkbox (desmarcar)
                    const atiende = document.getElementById(`atiende${dia}`);
                    if (atiende) {
                        atiende.checked = false;
                    }

                    // Resetear modalidad a "Medio tiempo"
                    const modalidad = document.getElementById(`modalidad${dia}`);
                    if (modalidad) {
                        modalidad.value = 'medioTiempo';
                        modalidad.disabled = true; // Deshabilitar al resetear
                    }

                    // Limpiar y deshabilitar todos los campos de horario
                    const horaInicio1 = document.getElementById(`horaInicio${dia}1`);
                    const horaFin1 = document.getElementById(`horaFin${dia}1`);
                    const horaInicio2 = document.getElementById(`horaInicio${dia}2`);
                    const horaFin2 = document.getElementById(`horaFin${dia}2`);

                    const fieldsToReset = [horaInicio1, horaFin1, horaInicio2, horaFin2];

                    fieldsToReset.forEach(field => {
                        if (field) {
                            field.value = '';
                            field.disabled = true;
                            field.required = false;
                            field.classList.remove('is-valid', 'is-invalid');
                            removeFieldHelpMessage(field);
                        }
                    });

                    // Ocultar el contenedor de horario tarde
                    const contenedorTarde = document.querySelector(`#atiende${dia}`).closest('tr').querySelector('.horario-tarde');
                    if (contenedorTarde) {
                        contenedorTarde.classList.add('d-none');
                    }
                });

                console.log("Todos los horarios han sido reseteados a Medio tiempo");
            }
            // Asegurar que todos los horarios empiecen en "Medio tiempo"
            resetearHorariosAMedioTiempo();
            // Función para limpiar todos los mensajes de advertencia existentes
            function limpiarMensajesAdvertencia() {
                const mensajesExistentes = document.querySelectorAll('.alert.alert-warning');
                mensajesExistentes.forEach(msg => {
                    if (msg.id === 'mensajeConfirmarContrato') {
                        msg.remove();
                    }
                });
            }

            // Función para registrar el contrato
            function registrarContrato(idcolaborador) {
                const formData = new FormData();
                formData.append('idcolaborador', idcolaborador);
                formData.append('tipocontrato', document.getElementById('tipocontrato').value);
                formData.append('fechainicio', document.getElementById('fechainicio').value);
                formData.append('fechafin', document.getElementById('fechafin').value || null);

                // Enviar datos del contrato
                fetch('../../../controllers/contrato.controller.php?op=registrar', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Respuesta de registro de contrato:", data);

                        if (data.status) {
                            // Actualizar mensaje de carga
                            Swal.update({
                                title: 'Registrando...',
                                html: 'Procesando horarios'
                            });

                            // Guardar horarios
                            registrarHorarios(idcolaborador, data.idcontrato);
                        } else {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al registrar',
                                text: data.mensaje || 'No se pudo registrar la información del contrato.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al registrar contrato:', error);
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'No se pudo completar el registro del contrato.'
                        });
                    });
            }

            // Función para registrar horarios
            function registrarHorarios(idcolaborador, idcontrato) {
                const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
                const diasDB = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO'];
                let horariosPendientes = 0;
                let horariosExitosos = 0;
                let promesasHorarios = [];
                let horariosConError = [];

                // Contar los días seleccionados
                diasSemana.forEach((dia, index) => {
                    const atiende = document.getElementById(`atiende${dia}`);
                    if (atiende && atiende.checked) {
                        horariosPendientes++;
                    }
                });

                // Si no hay horarios para registrar, continuar con las credenciales
                if (horariosPendientes === 0) {
                    // Actualizar mensaje de carga
                    Swal.update({
                        title: 'Registrando...',
                        html: 'Procesando credenciales'
                    });

                    registrarCredenciales(idcontrato);
                    return;
                }

                // Actualizar mensaje de carga
                Swal.update({
                    title: 'Registrando...',
                    html: `Procesando horarios de atención (0/${horariosPendientes})`
                });

                // Registrar cada horario seleccionado
                diasSemana.forEach((dia, index) => {
                    const atiende = document.getElementById(`atiende${dia}`);
                    if (atiende && atiende.checked) {
                        const horaInicio = document.getElementById(`horaInicio${dia}`).value;
                        const horaFin = document.getElementById(`horaFin${dia}`).value;

                        const formData = new FormData();
                        formData.append('idcolaborador', idcolaborador);
                        formData.append('dia', diasDB[index]);
                        formData.append('horainicio', horaInicio);
                        formData.append('horafin', horaFin);

                        // Crear una promesa para cada solicitud
                        const promesa = fetch('../../../controllers/horario.controller.php?op=registrar', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log(`Respuesta de registro de horario ${dia}:`, data);

                                horariosExitosos++;

                                // Actualizar mensaje de carga
                                Swal.update({
                                    html: `Procesando horarios de atención (${horariosExitosos}/${horariosPendientes})`
                                });

                                if (!data.status) {
                                    horariosConError.push(dia);
                                    console.error(`Error al registrar horario de ${dia}:`, data.mensaje);
                                }

                                return data;
                            })
                            .catch(error => {
                                console.error(`Error al registrar horario de ${dia}:`, error);
                                horariosConError.push(dia);
                                return {
                                    status: false,
                                    mensaje: error.message
                                };
                            });

                        promesasHorarios.push(promesa);
                    }
                });

                // Esperar a que todos los horarios se registren
                Promise.all(promesasHorarios)
                    .then(resultados => {
                        // Actualizar mensaje de carga
                        Swal.update({
                            title: 'Registrando...',
                            html: 'Procesando credenciales'
                        });

                        // Continuar con el registro de credenciales sin mostrar alerta intermedia
                        registrarCredenciales(idcontrato, horariosConError.length > 0);
                    });
            }

            // Función para registrar credenciales
            function registrarCredenciales(idcontrato, huboErroresHorarios = false) {
                const formData = new FormData();
                formData.append('idcontrato', idcontrato);
                formData.append('nomuser', document.getElementById('nomuser').value);
                formData.append('passuser', document.getElementById('passuser').value);
                formData.append('rol', 'DOCTOR'); // Por defecto, rol de doctor

                // Actualizar mensaje de carga
                Swal.update({
                    title: 'Registrando...',
                    html: 'Procesando credenciales'
                });

                // Enviar datos de credenciales
                fetch('../../../controllers/credencial.controller.php?op=registrar', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Respuesta de registro de credenciales:", data);

                        // Añadir un retraso antes de mostrar el resultado final
                        setTimeout(() => {
                            Swal.close();

                            if (data.status) {
                                // Registro completo exitoso pero con posibles errores en horarios
                                if (huboErroresHorarios) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Registro exitoso!',
                                        text: 'El doctor ha sido registrado correctamente, aunque algunos horarios no pudieron ser registrados.',
                                        confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        resetFormAndRedirect();
                                    });
                                } else {
                                    // Registro completo exitoso sin errores
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Registro exitoso!',
                                        text: 'El doctor ha sido registrado correctamente.',
                                        confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        resetFormAndRedirect();
                                    });
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error al registrar',
                                    text: data.mensaje || 'No se pudieron registrar las credenciales de acceso.'
                                });
                            }
                        }, 2000); // Retraso de 2 segundos
                    })
                    .catch(error => {
                        console.error('Error al registrar credenciales:', error);

                        // Añadir retraso en caso de error también
                        setTimeout(() => {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de conexión',
                                text: 'No se pudo completar el registro de credenciales.'
                            });
                        }, 2000);
                    });
            }
            // Función para mostrar mensaje de éxito
            function mostrarExito() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Registro exitoso!',
                    text: 'El doctor ha sido registrado correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    resetFormAndRedirect();
                });
            }

            // Función para resetear formulario y redirigir
            function resetFormAndRedirect() {
                // Limpiar formulario
                document.getElementById('doctorRegistrationForm').reset();

                // Restablecer valores por defecto
                document.getElementById("tipodoc").value = "DNI";
                document.getElementById("genero").value = "M";

                // Importante: Restablecer maxlength para DNI
                document.getElementById("nrodoc").setAttribute("maxlength", "8");

                // Bloquear campos y botones
                bloquearCampos();

                // Redirigir a la lista de doctores
                window.location.href = '../ListarDoctor/listarDoctor.php';
            }

            // Función para resetear el formulario
            window.resetForm = function() {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: "Se perderán todos los datos ingresados.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'No, continuar editando'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('doctorRegistrationForm').reset();

                        // Restablecer valores por defecto
                        document.getElementById("tipodoc").value = "DNI";
                        document.getElementById("genero").value = "M";

                        // Importante: Restablecer maxlength para DNI
                        document.getElementById("nrodoc").setAttribute("maxlength", "8");

                        // Limpiar clases de validación
                        const inputs = document.querySelectorAll('.form-control, .form-select');
                        inputs.forEach(input => {
                            input.classList.remove('is-invalid', 'is-valid');
                            removeFieldHelpMessage(input);
                        });

                        // Bloquear campos y botones
                        bloquearCampos();

                        // Restablecer horarios
                        const diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
                        diasSemana.forEach(dia => {
                            const checkbox = document.getElementById(`atiende${dia}`);
                            if (checkbox) {
                                checkbox.checked = false;
                                toggleHorario(dia);
                            }
                        });

                        // Volver a la primera pestaña
                        showTab('personalInfo');
                        updateProgressBar(0);

                        // Mostrar mensaje
                        showSuccessToast('Formulario restablecido');
                    }
                });
            }

            // Funciones auxiliares para formateo
            function formatDate(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            function formatTime(timeString) {
                if (!timeString) return '';
                return timeString;
            }
        });
    </script>

</body>

</html>