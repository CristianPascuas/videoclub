<?php
session_start();
include("../include/conex.php");
$conexion = Conectarse();

// Verificar si el usuario está logueado, toma usuario_id de la sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="text/javascript" src="../js/encargosPrueba.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/menustyles.css">
</head>
<body>
<button class="btn btn-primary btn-menu-toggle d-md-none" id="toggleMenu" aria-label="Abrir menú"><i class="fas fa-bars"></i></button>

<div class="sidebar" id="sidebarMenu">
    <div class="sidebar-header d-flex align-items-center justify-content-between px-3 py-2">
        <span class="fw-semibold text-truncate">Videoclub</span>
        <button class="btn btn-sm btn-outline-light d-md-none" id="closeMenu" aria-label="Cerrar menú">&times;</button>
    </div>
    <div class="accordion accordion-flush" id="menuAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingInicio">
              <a class="accordion-button single-link" href="menu.php">Inicio</a>
            </h2>
        </div>
        <?php
        include("../controlador/ctrlMenu.php");
        ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSalir">
              <a class="accordion-button single-link" href="../cerrar_sesion.php">Salir</a>
            </h2>
        </div>
    </div>
</div>

<main class="app-content">
    <div class="container-fluid py-4">
        <h1 class="h4 fw-semibold mb-3">Panel principal</h1>
        <p class="text-muted small">Bienvenido <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
    </div>
</main>

<!-- Modal Roles -->
<div class="modal fade" id="permisos" tabindex="-1" aria-labelledby="permisosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permisosLabel">Roles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- se muestran los roles -->
                <div id="listaRoles"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Asignar Permisos -->
<div class="modal fade" id="asignarPermisos" tabindex="-1" aria-labelledby="asignarPermisosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asignarPermisosLabel">Asigna Permisos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-1">
                    <div class="col">
                        <input class="form-control mb-2" type="text" id="idrol" placeholder="ID Rol" disabled hidden>
                        <input class="form-control" type="text" id="nombrerol" placeholder="Nombre del rol" disabled hidden>
                    </div>
                </div>
                <!-- se muestran los permisos -->
                <div id="listaAsignarPermisos"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="backdrop-sidebar d-md-none" id="sidebarBackdrop"></div>

<script type="text/javascript" src="../js/menuctr.js"></script>
</body>
</html>