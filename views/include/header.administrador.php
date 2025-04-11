<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario']) || !$_SESSION['usuario']['autenticado']) {
    header('Location: ../../login.php');
    exit();
}

$host = "http://localhost/sistemaclinica";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistema de gestión clínica" />
    <meta name="author" content="" />
    <title>Sistema Clínica</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="<?= $host ?>/css/estiloDashboard.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="<?= $host ?>/views/include/dashboard.administrador.php">Sistema Clínica</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Buscar..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="javascript:void(0)" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i> <?= $_SESSION['usuario']['nombres'] ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Mi Perfil</a></li>
                    <li><a class="dropdown-item" href="#!">Cambiar Contraseña</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="<?= $host ?>/controllers/usuario.controller.php?operacion=cerrar_sesion">Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Inicio</div>
                        <a class="nav-link" href="<?= $host ?>/views/include/dashboard.administrador.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Panel de Control
                        </a>

                        <div class="sb-sidenav-menu-heading">Gestión Clínica</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePersonas" aria-expanded="false" aria-controls="collapsePersonas">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Personas
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePersonas" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= $host ?>/views/Paciente/RegistrarPaciente/registrarPaciente.php">Registro de Pacientes</a>
                                <a class="nav-link" href="<?= $host ?>/views/Paciente/ListarPaciente/listarPaciente.php">Listado de Pacientes</a>
                                <a class="nav-link" href="<?= $host ?>/views/Doctor/RegistrarDoctor/registrarDoctor.php">Registro de Doctores</a>
                                <a class="nav-link" href="<?= $host ?>/views/Doctor/listarDoctor/listarDoctor.php">Listado de Doctores</a>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseHistorias" aria-expanded="false" aria-controls="collapseHistorias">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-medical"></i></div>
                            Historias Clínicas
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseHistorias" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= $host ?>/views/historias/registro.php">Nueva Historia</a>
                                <a class="nav-link" href="<?= $host ?>/views/historias/listado.php">Buscar Historias</a>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCitas" aria-expanded="false" aria-controls="collapseCitas">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                            Citas
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseCitas" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= $host ?>/views/citas/programarCita.php">Programar Citas</a>
                                <a class="nav-link" href="<?= $host ?>/views/citas/gestionCita.php">Gestión de Citas</a>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseConsultas" aria-expanded="false" aria-controls="collapseConsultas">
                            <div class="sb-nav-link-icon"><i class="fas fa-stethoscope"></i></div>
                            Consultas
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseConsultas" aria-labelledby="headingFour" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= $host ?>/views/consultas/registro.php">Registrar Consulta</a>
                                <a class="nav-link" href="<?= $host ?>/views/consultas/listado.php">Historial de Consultas</a>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseServicios" aria-expanded="false" aria-controls="collapseServicios">
                            <div class="sb-nav-link-icon"><i class="fas fa-flask"></i></div>
                            Servicios
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseServicios" aria-labelledby="headingFive" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= $host ?>/views/Servicios/catalogoservicios.php">Catálogo de Servicios</a>
                                <a class="nav-link" href="<?= $host ?>/views/servicios/resultados.php">Resultados</a>
                            </nav>
                        </div>

                        <div class="sb-sidenav-menu-heading">Ventas y Facturación</div>
                        <a class="nav-link" href="<?= $host ?>/views/ventas/registro.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-cash-register"></i></div>
                            Nueva Venta
                        </a>
                        <a class="nav-link" href="<?= $host ?>/views/ventas/listado.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            Historial de Ventas
                        </a>

                        <div class="sb-sidenav-menu-heading">Configuración</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                            <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                            Administración
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseAdmin" aria-labelledby="headingSix" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= $host ?>/views/admin/usuarios.php">Gestión de Usuarios</a>
                                <a class="nav-link" href="<?= $host ?>/views/admin/colaboradores.php">Colaboradores</a>
                                <a class="nav-link" href="<?= $host ?>/views/admin/especialidades.php">Especialidades</a>
                                <a class="nav-link" href="<?= $host ?>/views/admin/diagnosticos.php">Diagnósticos</a>
                                <a class="nav-link" href="<?= $host ?>/views/admin/Carruzel.php">Carrusel</a>
                                <a class="nav-link" href="<?= $host ?>/views/admin/Promociones.php">promociones</a>
                                <a class="nav-link" href="<?= $host ?>/views/admin/Footer.php">Footer</a>
                            </nav>
                        </div>

                        <a class="nav-link" href="<?= $host ?>/views/admin/reportes.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                            Reportes
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Conectado como:</div>
                    <?= $_SESSION['usuario']['nombres'] . ' ' . $_SESSION['usuario']['apellidos'] ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>