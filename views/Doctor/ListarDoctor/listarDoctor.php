<?php
require_once '../../include/header.administrador.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Doctores</title>
    <link rel="stylesheet" href="../../../css/listarDoctor.css">
    <link rel="stylesheet" href="../../../css/doctorDetalles.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0"><i class="fas fa-user-md me-2"></i>Listado de Doctores</h2>
                        <a href="../RegistrarDoctor/registrarDoctor.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Registrar Nuevo Doctor
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="filtroNombre" class="form-label">Nombre</label>
                                <input type="text" id="filtroNombre" class="form-control" placeholder="Buscar por nombre">
                            </div>
                            <div class="col-md-3">
                                <label for="filtroEspecialidad" class="form-label">Especialidad</label>
                                <select id="filtroEspecialidad" class="form-select">
                                    <option value="">Todas las Especialidades</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filtroGenero" class="form-label">Género</label>
                                <select id="filtroGenero" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="OTRO">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filtroEstado" class="form-label">Estado</label>
                                <select id="filtroEstado" class="form-select">
                                    <option value="">Todos los Estados</option>
                                    <option value="ACTIVO">Activo</option>
                                    <option value="INACTIVO">Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tablaDoctores" class="table table-striped table-hover dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Tipo Doc</th>
                                        <th>N° Documento</th>
                                        <th>Nombres y Apellidos</th>
                                        <th>Especialidad</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Género</th>
                                        <th>Estado</th>
                                        <th>Cambiar Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTablaDoctores">
                                    <!-- Los datos se cargarán dinámicamente con JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Ver Detalles -->
    <div class="modal fade" id="modalDetallesDoctor" tabindex="-1" aria-labelledby="modalDetallesDoctorLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalDetallesDoctorLabel">
                        <i class="fas fa-user-md me-2"></i> Detalles del Doctor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenidoDetallesDoctor">
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando información del doctor...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-info" id="btnImprimirDoctor">
                        <i class="fas fa-print me-2"></i>Imprimir Datos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Doctor -->
    <div class="modal fade" id="modalEditarDoctor" tabindex="-1" aria-labelledby="modalEditarDoctorLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalEditarDoctorLabel">
                        <i class="fas fa-user-edit me-2"></i> Editar Doctor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenidoEditarDoctor">
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando formulario de edición...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarEdicion">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para seleccionar sección de edición -->
    <div class="modal fade" id="modalSeleccionEdicion" tabindex="-1" aria-labelledby="modalSeleccionEdicionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSeleccionEdicionLabel">Seleccione sección a editar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <input type="hidden" id="nroDocSeleccionado">
                    <div class="d-grid gap-3">
                        <button id="btnInfoDoctor" class="btn btn-info btn-lg">
                            <i class="fas fa-user-md me-2"></i> Información del Doctor
                        </button>
                        <button id="btnDatosProfesionales" class="btn btn-success btn-lg">
                            <i class="fas fa-briefcase me-2"></i> Datos Profesionales
                        </button>
                        <button id="btnInfoContrato" class="btn btn-warning btn-lg">
                            <i class="fas fa-file-contract me-2"></i> Información de Contrato
                        </button>
                        <button id="btnHorarioAtencion" class="btn btn-danger btn-lg">
                            <i class="fas fa-clock me-2"></i> Horario de Atención
                        </button>
                        <button id="btnCredenciales" class="btn btn-dark btn-lg">
                            <i class="fas fa-key me-2"></i> Credenciales de Acceso
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Información del Doctor (corregido) -->
    <div class="modal fade" id="modalInfoDoctor" tabindex="-1" aria-labelledby="modalInfoDoctorLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalInfoDoctorLabel"><i class="fas fa-user-md me-2"></i> Información del Doctor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="contenidoInfoDoctor">
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando información del doctor...</p>
                        </div>
                    </div>
                </div>
                <!-- Se eliminan los botones duplicados del footer -->
            </div>
        </div>
    </div>

    <!-- Modal para Datos Profesionales (corregido) -->
    <div class="modal fade" id="modalDatosProfesionales" tabindex="-1" aria-labelledby="modalDatosProfesionalesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalDatosProfesionalesLabel"><i class="fas fa-briefcase me-2"></i> Datos Profesionales</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="contenidoDatosProfesionales">
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando datos profesionales...</p>
                        </div>
                    </div>
                </div>
                <!-- Se eliminan los botones duplicados del footer -->
            </div>
        </div>
    </div>

    <!-- Modal para Información de Contrato (corregido) -->
    <div class="modal fade" id="modalInfoContrato" tabindex="-1" aria-labelledby="modalInfoContratoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalInfoContratoLabel"><i class="fas fa-file-contract me-2"></i> Información de Contrato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="contenidoInfoContrato">
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando información de contrato...</p>
                        </div>
                    </div>
                </div>
                <!-- Se eliminan los botones duplicados del footer -->
            </div>
        </div>
    </div>

    <!-- Modal para Horario de Atención (corregido) -->
    <div class="modal fade" id="modalHorarioAtencion" tabindex="-1" aria-labelledby="modalHorarioAtencionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalHorarioAtencionLabel"><i class="fas fa-clock me-2"></i> Horario de Atención</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="contenidoHorarioAtencion">
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando horarios de atención...</p>
                        </div>
                    </div>
                </div>
                <!-- Se eliminan los botones duplicados del footer -->
            </div>
        </div>
    </div>

    <!-- Modal para Credenciales (corregido) -->
    <div class="modal fade" id="modalCredenciales" tabindex="-1" aria-labelledby="modalCredencialesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalCredencialesLabel"><i class="fas fa-key me-2"></i> Credenciales de Acceso</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="contenidoCredenciales">
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando credenciales de acceso...</p>
                        </div>
                    </div>
                </div>
                <!-- Se eliminan los botones duplicados del footer -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            let tablaDoctores;
            let formEditarDoctor;

            // Función para cargar especialidades en el filtro
            function cargarEspecialidades() {
                $.ajax({
                    url: '../../../controllers/especialidad.controller.php?op=listar',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            const selectEspecialidad = $('#filtroEspecialidad');
                            selectEspecialidad.empty();
                            selectEspecialidad.append('<option value="">Todas las Especialidades</option>');

                            response.data.forEach(especialidad => {
                                selectEspecialidad.append(`
                                <option value="${especialidad.especialidad}">
                                    ${especialidad.especialidad}
                                </option>
                            `);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.mensaje || 'No se pudieron cargar las especialidades'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Conexión',
                            text: 'No se pudo conectar con el servidor para cargar especialidades'
                        });
                    }
                });
            }

            // Función para cargar doctores
            function cargarDoctores() {
                // Mostrar un indicador de carga
                $('#cuerpoTablaDoctores').html(`
                <tr>
                    <td colspan="11" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando listado de doctores...</p>
                    </td>
                </tr>
            `);

                $.ajax({
                    url: '../../../controllers/doctor.controller.php?op=listar',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            // Destruir la tabla existente si ya está inicializada
                            if (tablaDoctores) {
                                tablaDoctores.destroy();
                            }

                            // Verificar si hay datos
                            if (!response.data || response.data.length === 0) {
                                $('#cuerpoTablaDoctores').html(`
                                <tr>
                                    <td colspan="11" class="text-center">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>No hay doctores registrados
                                        </div>
                                    </td>
                                </tr>
                            `);
                                return;
                            }

                            // Inicializar la tabla
                            tablaDoctores = $('#tablaDoctores').DataTable({
                                responsive: true,
                                language: {
                                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                                },
                                data: response.data,
                                columns: [{
                                        data: null,
                                        render: function(data, type, row, meta) {
                                            return meta.row + 1;
                                        }
                                    },
                                    {
                                        data: 'tipodoc'
                                    },
                                    {
                                        data: 'nrodoc'
                                    },
                                    {
                                        data: null,
                                        render: function(data, type, row) {
                                            return `${row.nombres} ${row.apellidos}`;
                                        }
                                    },
                                    {
                                        data: 'especialidad'
                                    },
                                    {
                                        data: 'email',
                                        render: function(data) {
                                            return data || 'Sin correo electrónico';
                                        }
                                    },
                                    {
                                        data: 'telefono'
                                    },
                                    {
                                        data: 'genero',
                                        render: function(data) {
                                            if (data === 'M') return 'Masculino';
                                            if (data === 'F') return 'Femenino';
                                            return data || 'No especificado';
                                        }
                                    },
                                    {
                                        data: 'estado',
                                        render: function(data) {
                                            return data === 'ACTIVO' ?
                                                '<span class="badge bg-success">Activo</span>' :
                                                '<span class="badge bg-danger">Inactivo</span>';
                                        }
                                    },
                                    {
                                        data: null,
                                        render: function(data, type, row) {
                                            const isActive = row.estado === 'ACTIVO';
                                            const buttonClass = isActive ? 'btn-danger' : 'btn-success';
                                            const buttonText = isActive ? 'Desactivar' : 'Activar';
                                            const buttonIcon = isActive ? 'fa-toggle-off' : 'fa-toggle-on';

                                            return `
                                        <button type="button" class="btn ${buttonClass} btn-sm btnCambiarEstado" 
                                            data-nrodoc="${row.nrodoc}" 
                                            data-estado="${row.estado}">
                                            <i class="fas ${buttonIcon}"></i> ${buttonText}
                                        </button>`;
                                        }
                                    },
                                    {
                                        data: null,
                                        render: function(data, type, row) {
                                            return `
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-info btn-sm btnVerDetalles"
                                                data-nrodoc="${row.nrodoc}" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm btnEditar" 
                                                data-nrodoc="${row.nrodoc}" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btnEliminar" 
                                                data-nrodoc="${row.nrodoc}" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>`;
                                        }
                                    }
                                ],
                                columnDefs: [{
                                        width: "4%",
                                        targets: 0
                                    },
                                    {
                                        width: "7%",
                                        targets: [1, 2]
                                    },
                                    {
                                        width: "13%",
                                        targets: 3
                                    },
                                    {
                                        width: "10%",
                                        targets: 4
                                    },
                                    {
                                        width: "10%",
                                        targets: 5
                                    },
                                    {
                                        width: "7%",
                                        targets: 6
                                    },
                                    {
                                        width: "7%",
                                        targets: 7
                                    }, // Columna de Género
                                    {
                                        width: "7%",
                                        targets: 8
                                    }, // Estado
                                    {
                                        width: "10%",
                                        targets: 9
                                    }, // Cambiar Estado
                                    {
                                        width: "10%",
                                        targets: 10
                                    } // Acciones
                                ]
                            });

                            // Inicializar tooltips para los botones
                            $('[data-bs-toggle="tooltip"], [title]').tooltip();

                            // Aplicar filtros
                            $('#filtroNombre, #filtroEspecialidad, #filtroGenero, #filtroEstado').on('change keyup', function() {
                                aplicarFiltros();
                            });
                        } else {
                            $('#cuerpoTablaDoctores').html(`
                            <tr>
                                <td colspan="11" class="text-center">
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        ${response.mensaje || 'Error al cargar los doctores'}
                                    </div>
                                </td>
                            </tr>
                        `);

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.mensaje || 'No se pudieron cargar los doctores'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#cuerpoTablaDoctores').html(`
                        <tr>
                            <td colspan="11" class="text-center">
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Error de conexión: ${status} - ${error}
                                </div>
                            </td>
                        </tr>
                    `);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Conexión',
                            text: 'No se pudo conectar con el servidor'
                        });
                    }
                });
            }

            // Función para aplicar filtros
            function aplicarFiltros() {
                const filtroNombre = $('#filtroNombre').val().toLowerCase();
                const filtroEspecialidad = $('#filtroEspecialidad').val();
                const filtroGenero = $('#filtroGenero').val();
                const filtroEstado = $('#filtroEstado').val();

                // Filtro de nombre (columna 3)
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        const nombreCompleto = data[3].toLowerCase();
                        if (filtroNombre && !nombreCompleto.includes(filtroNombre)) {
                            return false;
                        }
                        return true;
                    }
                );

                // Filtro de especialidad
                tablaDoctores.columns(4).search(filtroEspecialidad ? filtroEspecialidad : '');

                // Filtro de género
                if (filtroGenero) {
                    tablaDoctores.columns(7).search(filtroGenero === 'M' ? 'Masculino' :
                        (filtroGenero === 'F' ? 'Femenino' : 'Otro'));
                } else {
                    tablaDoctores.columns(7).search('');
                }

                // Filtro de estado
                tablaDoctores.columns(8).search(filtroEstado ? filtroEstado : '');

                tablaDoctores.draw();

                // Limpiar filtro personalizado después de aplicarlo
                $.fn.dataTable.ext.search.pop();
            }

            // Cargar especialidades
            cargarEspecialidades();

            // Cargar doctores al iniciar
            cargarDoctores();

            // Evento para Cambiar Estado (nuevo botón)
            $(document).on('click', '.btnCambiarEstado', function() {
                const nroDoc = $(this).data('nrodoc');
                const estadoActual = $(this).data('estado');
                const nuevoEstado = estadoActual === 'ACTIVO' ? 'Desactivar' : 'Activar';

                Swal.fire({
                    title: `¿${nuevoEstado} doctor?`,
                    text: `Esta acción cambiará el estado del doctor a ${estadoActual === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO'}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: `Sí, ${nuevoEstado.toLowerCase()}`,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Procesando',
                            text: 'Cambiando estado del doctor...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: '../../../controllers/doctor.controller.php?op=cambiar_estado',
                            type: 'POST',
                            data: {
                                nrodoc: nroDoc
                            },
                            dataType: 'json',
                            success: function(response) {
                                Swal.close();

                                if (response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Éxito',
                                        text: response.mensaje || 'Estado del doctor actualizado correctamente'
                                    });
                                    // Recargar la tabla
                                    cargarDoctores();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.mensaje || 'No se pudo actualizar el estado del doctor'
                                    });
                                }
                            },
                            error: function() {
                                Swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de Conexión',
                                    text: 'No se pudo conectar con el servidor'
                                });
                            }
                        });
                    }
                });
            });

            // Evento para Ver Detalles
            $(document).on('click', '.btnVerDetalles', function() {
                const nroDoc = $(this).data('nrodoc');

                // Mostrar el spinner de carga
                $('#contenidoDetallesDoctor').html(`
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando información del doctor...</p>
                </div>
            `);

                // Mostrar el modal
                const modalDetalles = new bootstrap.Modal(document.getElementById('modalDetallesDoctor'));
                modalDetalles.show();

                // Cargar el contenido desde el archivo externo
                $.ajax({
                    url: `VerDetalles.php?nrodoc=${nroDoc}`,
                    type: 'GET',
                    success: function(response) {
                        $('#contenidoDetallesDoctor').html(response);
                    },
                    error: function(xhr, status, error) {
                        $('#contenidoDetallesDoctor').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error al cargar los datos del doctor: ${xhr.status} ${xhr.statusText}
                        </div>
                    `);
                    }
                });
            });

            // Evento para Editar
            $(document).on('click', '.btnEditar', function() {
                const nroDoc = $(this).data('nrodoc');

                // Guardar el número de documento seleccionado
                $('#nroDocSeleccionado').val(nroDoc);

                // Mostrar el modal de selección
                const modalSeleccion = new bootstrap.Modal(document.getElementById('modalSeleccionEdicion'));
                modalSeleccion.show();
            });

            // Configurar botones de edición
            $('#btnInfoDoctor').on('click', function() {
                const nroDoc = $('#nroDocSeleccionado').val();

                // Cerrar modal de selección
                bootstrap.Modal.getInstance(document.getElementById('modalSeleccionEdicion')).hide();

                // Mostrar el modal con spinner de carga
                const modalInfoDoctor = new bootstrap.Modal(document.getElementById('modalInfoDoctor'));
                modalInfoDoctor.show();

                // Cargar contenido desde el endpoint
                $.ajax({
                    url: `editarInfoDoctor.php?nrodoc=${nroDoc}`,
                    type: 'GET',
                    success: function(response) {
                        $('#contenidoInfoDoctor').html(response);
                    },
                    error: function() {
                        $('#contenidoInfoDoctor').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error al cargar la información del doctor.
                        </div>
                    `);
                    }
                });
            });

            $('#btnDatosProfesionales').on('click', function() {
                const nroDoc = $('#nroDocSeleccionado').val();

                // Cerrar modal de selección
                bootstrap.Modal.getInstance(document.getElementById('modalSeleccionEdicion')).hide();

                // Mostrar el modal con spinner de carga
                const modalDatosProfesionales = new bootstrap.Modal(document.getElementById('modalDatosProfesionales'));
                modalDatosProfesionales.show();

                // Cargar contenido desde el endpoint
                $.ajax({
                    url: `editarDatosProfesionales.php?nrodoc=${nroDoc}`,
                    type: 'GET',
                    success: function(response) {
                        $('#contenidoDatosProfesionales').html(response);
                    },
                    error: function() {
                        $('#contenidoDatosProfesionales').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error al cargar los datos profesionales.
                        </div>
                    `);
                    }
                });
            });

            $('#btnInfoContrato').on('click', function() {
                const nroDoc = $('#nroDocSeleccionado').val();

                // Cerrar modal de selección
                bootstrap.Modal.getInstance(document.getElementById('modalSeleccionEdicion')).hide();

                // Mostrar el modal con spinner de carga
                const modalInfoContrato = new bootstrap.Modal(document.getElementById('modalInfoContrato'));
                modalInfoContrato.show();

                // Cargar contenido desde el endpoint
                $.ajax({
                    url: `editarInfoContrato.php?nrodoc=${nroDoc}`,
                    type: 'GET',
                    success: function(response) {
                        $('#contenidoInfoContrato').html(response);
                    },
                    error: function() {
                        $('#contenidoInfoContrato').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error al cargar la información de contrato.
                        </div>
                    `);
                    }
                });
            });

            $('#btnHorarioAtencion').on('click', function() {
                const nroDoc = $('#nroDocSeleccionado').val();

                // Cerrar modal de selección
                bootstrap.Modal.getInstance(document.getElementById('modalSeleccionEdicion')).hide();

                // Mostrar el modal con spinner de carga
                const modalHorarioAtencion = new bootstrap.Modal(document.getElementById('modalHorarioAtencion'));
                modalHorarioAtencion.show();

                // Cargar contenido desde el endpoint
                $.ajax({
                    url: `editarHorarioAtencion.php?nrodoc=${nroDoc}`,
                    type: 'GET',
                    success: function(response) {
                        $('#contenidoHorarioAtencion').html(response);
                    },
                    error: function() {
                        $('#contenidoHorarioAtencion').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error al cargar los horarios de atención.
                        </div>
                    `);
                    }
                });
            });

            $('#btnCredenciales').on('click', function() {
                const nroDoc = $('#nroDocSeleccionado').val();

                // Cerrar modal de selección
                bootstrap.Modal.getInstance(document.getElementById('modalSeleccionEdicion')).hide();

                // Mostrar el modal con spinner de carga
                const modalCredenciales = new bootstrap.Modal(document.getElementById('modalCredenciales'));
                modalCredenciales.show();

                // Cargar contenido desde el endpoint
                $.ajax({
                    url: `editarCredenciales.php?nrodoc=${nroDoc}`,
                    type: 'GET',
                    success: function(response) {
                        $('#contenidoCredenciales').html(response);
                    },
                    error: function() {
                        $('#contenidoCredenciales').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error al cargar las credenciales de acceso.
                        </div>
                    `);
                    }
                });
            });

            // Eventos para botones de guardar cambios
            $('#btnGuardarInfoDoctor').on('click', function() {
                // Simular el envío del formulario
                $('#formEditarInfoDoctor').submit();
            });

            $('#btnGuardarDatosProfesionales').on('click', function() {
                // Simular el envío del formulario
                $('#formEditarDatosProfesionales').submit();
            });

            $('#btnGuardarInfoContrato').on('click', function() {
                // Simular el envío del formulario
                $('#formEditarInfoContrato').submit();
            });

            $('#btnGuardarHorarioAtencion').on('click', function() {
                // Simular el envío del formulario
                $('#formEditarHorarioAtencion').submit();
            });

            $('#btnGuardarCredenciales').on('click', function() {
                // Simular el envío del formulario
                $('#formEditarCredenciales').submit();
            });

            // Función para inicializar el formulario de edición
            function inicializarFormularioEdicion() {
                // Evento para guardar cambios desde el modal
                $('#btnGuardarEdicion').off('click').on('click', function() {
                    // Simular el envío del formulario
                    formEditarDoctor.submit();
                });

                // Sobrescribir el comportamiento del formulario para usar AJAX
                formEditarDoctor.off('submit').on('submit', function(e) {
                    e.preventDefault();

                    // Crear FormData para enviar archivos
                    const formData = new FormData(this);

                    // Mostrar loading
                    Swal.fire({
                        title: 'Procesando',
                        text: 'Guardando cambios...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '../../../controllers/doctor.controller.php?op=editar',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response) {
                            Swal.close();

                            if (response.status) {
                                // Cerrar el modal
                                bootstrap.Modal.getInstance(document.getElementById('modalEditarDoctor')).hide();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: response.mensaje || 'Doctor actualizado correctamente'
                                });

                                // Recargar la tabla
                                cargarDoctores();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.mensaje || 'No se pudo actualizar el doctor'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de Conexión',
                                text: 'No se pudo conectar con el servidor'
                            });
                        }
                    });
                });

                // Evento para la vista previa de la foto
                $('#foto').change(function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#previewFoto').attr('src', e.target.result);
                            $('#previewFoto').removeClass('d-none');
                            $('#fotoPlaceholder').addClass('d-none');
                        }
                        reader.readAsDataURL(file);
                    } else {
                        $('#previewFoto').addClass('d-none');
                        $('#fotoPlaceholder').removeClass('d-none');
                    }
                });
            }

            // Evento para imprimir los datos del doctor
            $('#btnImprimirDoctor').on('click', function() {
                const contenido = $('#contenidoDetallesDoctor').html();
                const ventanaImpresion = window.open('', '_blank');

                ventanaImpresion.document.write(`
                <html>
                <head>
                    <title>Datos del Doctor</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
                    <link rel="stylesheet" href="../../../css/doctorDetalles.css">
                    <style>
                        body { padding: 20px; }
                        @media print {
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3>Datos del Doctor</h3>
                            <div class="text-end no-print">
                                <button onclick="window.print()" class="btn btn-primary">
                                    <i class="fas fa-print me-2"></i>Imprimir
                                </button>
                            </div>
                        </div>
                        <hr>
                        ${contenido}
                    </div>
                </body>
                </html>
            `);

                ventanaImpresion.document.close();
            });

            // Evento para Eliminar
            $(document).on('click', '.btnEliminar', function() {
                const nroDoc = $(this).data('nrodoc');

                Swal.fire({
                    title: '¿Está seguro de que desea eliminar este doctor?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Procesando',
                            text: 'Eliminando doctor...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: '../../../controllers/doctor.controller.php?op=eliminar',
                            type: 'POST',
                            data: {
                                nrodoc: nroDoc
                            },
                            dataType: 'json',
                            success: function(response) {
                                Swal.close();

                                if (response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Éxito',
                                        text: response.mensaje || 'Doctor eliminado correctamente'
                                    });
                                    // Recargar la tabla
                                    cargarDoctores();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.mensaje || 'No se pudo eliminar el doctor'
                                    });
                                }
                            },
                            error: function() {
                                Swal.close();

                                // Intentar con GET como método alternativo
                                $.ajax({
                                    url: `../../../controllers/doctor.controller.php?op=eliminar&nrodoc=${nroDoc}`,
                                    type: 'GET',
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.status) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Éxito',
                                                text: response.mensaje || 'Doctor eliminado correctamente'
                                            });
                                            // Recargar la tabla
                                            cargarDoctores();
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: response.mensaje || 'No se pudo eliminar el doctor'
                                            });
                                        }
                                    },
                                    error: function() {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error de Conexión',
                                            text: 'No se pudo conectar con el servidor'
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    <style>
        /* Estilos para la tabla y botones */
        .table thead th {
            background-color: #f8f9fa;
            font-weight: bold;
            vertical-align: middle;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .btn-group .btn {
            margin: 0 2px;
            width: 38px;
            height: 38px;
            padding: 6px 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Badge de estado */
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            font-size: 85%;
        }

        /* Botón de cambio de estado */
        .btnCambiarEstado {
            white-space: nowrap;
            width: auto !important;
            height: auto !important;
            padding: 0.25rem 0.5rem !important;
        }

        /* Estilos para los modales */
        .modal-lg {
            max-width: 800px;
        }

        .modal-xl {
            max-width: 1140px;
        }

        .modal-header {
            padding: 12px 16px;
        }

        .modal-body {
            padding: 20px;
        }

        /* Mejoras para DataTables */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px;
            padding: 0.4rem 0.75rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 6px;
            padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px;
            margin: 0 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #0d6efd;
            border-color: #0d6efd;
            color: white !important;
        }

        /* Estilos para los botones de selección */
        #btnInfoDoctor,
        #btnDatosProfesionales,
        #btnInfoContrato,
        #btnHorarioAtencion,
        #btnCredenciales {
            padding: 15px;
            font-size: 18px;
            transition: all 0.3s;
        }

        #btnInfoDoctor:hover,
        #btnDatosProfesionales:hover,
        #btnInfoContrato:hover,
        #btnHorarioAtencion:hover,
        #btnCredenciales:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Mejoras para móviles */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }

            .btn-group .btn {
                width: 35px;
                height: 35px;
            }

            .form-label {
                margin-top: 10px;
            }
        }
    </style>
</body>

</html>

<?php require_once '../../include/footer.php'; ?>