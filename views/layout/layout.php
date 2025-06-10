<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="build/js/app.js"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>Sistema de Celulares</title>
    <style>
        /* Navbar con gradiente sencillo */
        .navbar {
            background: linear-gradient(45deg, #4a90e2, #357abd) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        /* Logo y marca */
        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }
        
        /* Enlaces del menú */
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            margin: 0 8px;
            border-radius: 8px;
            padding: 8px 15px !important;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.2);
            color: white !important;
        }
        
        /* Dropdowns */
        .dropdown-menu {
            background-color: #2c3e50;
            border: none;
            border-radius: 10px;
        }
        
        .dropdown-item {
            color: #ecf0f1 !important;
            padding: 10px 20px;
        }
        
        .dropdown-item:hover {
            background-color: #34495e;
            color: white !important;
        }
        
        /* Headers de dropdown */
        .dropdown-header {
            color: #3498db !important;
            font-weight: bold;
        }
        
        /* Contenido principal */
        .container-fluid {
            margin-top: 20px;
        }
        
        /* Footer sencillo */
        .footer-simple {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 15px 0;
        }
    </style>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <!-- Logo y nombre -->
        <a class="navbar-brand" href="/proyecto_jjjc/">
            <img src="<?= asset('./images/cit.png') ?>" width="35px" alt="logo">
            Sistema Celulares
        </a>

        <!-- Botón para móvil -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menú principal -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                
                <!-- INICIO -->
                <li class="nav-item">
                    <a class="nav-link" href="/proyecto_jjjc/">
                        <i class="bi bi-house-fill me-1"></i>Inicio
                    </a>
                </li>

                <!-- MÓDULO DE VENTAS -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="ventasDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-cart3 me-1"></i>Ventas
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <h6 class="dropdown-header"><i class="bi bi-shop me-1"></i>Ventas</h6>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/ventas">
                                <i class="bi bi-plus-circle me-2"></i>Nueva Venta
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/historial-ventas">
                                <i class="bi bi-clock-history me-2"></i>Historial de Ventas
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <h6 class="dropdown-header"><i class="bi bi-tools me-1"></i>Reparaciones</h6>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/reparaciones">
                                <i class="bi bi-wrench me-2"></i>Órdenes de Reparación
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- INVENTARIO -->
                <li class="nav-item">
                    <a class="nav-link" href="/proyecto_jjjc/inventario">
                        <i class="bi bi-boxes me-1"></i>Inventario
                    </a>
                </li>

                <!-- CLIENTES -->
                <li class="nav-item">
                    <a class="nav-link" href="/proyecto_jjjc/clientes">
                        <i class="bi bi-people me-1"></i>Clientes
                    </a>
                </li>

                <!-- CATÁLOGOS -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="catalogosDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-list-ul me-1"></i>Catálogos
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <h6 class="dropdown-header"><i class="bi bi-gear me-1"></i>Productos</h6>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/marcas">
                                <i class="bi bi-phone me-2"></i>Marcas de Celulares
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/empleados">
                                <i class="bi bi-person-badge me-2"></i>Empleados
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/servicios">
                                <i class="bi bi-tools me-2"></i>Tipos de Servicios
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- REPORTES -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="reportesDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-graph-up me-1"></i>Reportes
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <h6 class="dropdown-header"><i class="bi bi-bar-chart me-1"></i>Estadísticas</h6>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/estadisticas">
                                <i class="bi bi-pie-chart me-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/reportes-ventas">
                                <i class="bi bi-file-earmark-bar-graph me-2"></i>Reporte de Ventas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/reportes-inventario">
                                <i class="bi bi-boxes me-2"></i>Reporte de Inventario
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ADMINISTRACIÓN -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-shield-lock me-1"></i>Administración
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <h6 class="dropdown-header"><i class="bi bi-people me-1"></i>Usuarios y Permisos</h6>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/registro">
                                <i class="bi bi-person-plus me-2"></i>Registrar Usuario
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/aplicaciones">
                                <i class="bi bi-app me-2"></i>Aplicaciones
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/permisos">
                                <i class="bi bi-shield-check me-2"></i>Permisos
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

            <!-- Usuario y Logout -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i>Usuario
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/perfil">
                                <i class="bi bi-person me-2"></i>Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/configuracion">
                                <i class="bi bi-gear me-2"></i>Configuración
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="/proyecto_jjjc/login">
                                <i class="bi bi-box-arrow-left me-2"></i>Iniciar Sesión
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="/proyecto_jjjc/logout">
                                <i class="bi bi-power me-2"></i>Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Barra de progreso -->
<div class="progress fixed-bottom" style="height: 6px;">
    <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" 
         aria-valuemin="0" aria-valuemax="100"></div>
</div>

<!-- Contenido principal -->
<div class="container-fluid pt-4 mb-4" style="min-height: 85vh">
    <?php echo $contenido; ?>
</div>

<!-- Footer -->
<div class="footer-simple">
    <div class="container-fluid">
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="margin: 0; font-size: 14px;">
                    <strong>Sistema de Gestión de Celulares</strong> - 
                    Comando de Informática y Tecnología © <?= date('Y') ?>
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>