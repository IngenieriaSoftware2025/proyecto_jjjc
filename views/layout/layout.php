<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <title>Sistema de Celulares</title>
    <style>
        :root {
            --ancho-sidebar: 280px;
            --altura-header: 70px;
            --color-primario: #2563eb;
            --color-secundario: #1e40af;
            --color-acento: #3b82f6;
            --texto-claro: #f8fafc;
            --texto-oscuro: #1e293b;
            --fondo-claro: #f1f5f9;
            --sombra: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --sombra-grande: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--fondo-claro);
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--ancho-sidebar);
            background: linear-gradient(180deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: var(--texto-claro);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.3) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }

        .sidebar.oculto {
            transform: translateX(-100%);
        }

        /* LOGO DEL SIDEBAR */
        .logo-sidebar {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .logo-sidebar img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 0.5rem;
        }

        .logo-sidebar h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .logo-sidebar small {
            color: rgba(255,255,255,0.7);
            font-size: 0.8rem;
        }

        /* MENÚ DEL SIDEBAR */
        .menu-sidebar {
            padding: 1rem 0;
        }

        .item-menu {
            margin: 0.25rem 0;
        }

        .enlace-menu {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--texto-claro);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .enlace-menu:hover {
            background: rgba(255,255,255,0.1);
            color: var(--texto-claro);
            padding-left: 2rem;
        }

        .enlace-menu.activo {
            background: rgba(255,255,255,0.15);
            border-right: 3px solid #60a5fa;
        }

        .enlace-menu i {
            font-size: 1.1rem;
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .enlace-menu .texto {
            flex: 1;
            font-weight: 500;
        }

        .enlace-menu .flecha {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .enlace-menu.expandido .flecha {
            transform: rotate(90deg);
        }

        /* SUBMENÚ */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0,0,0,0.1);
        }

        .submenu.abierto {
            max-height: 500px;
        }

        .enlace-submenu {
            display: flex;
            align-items: center;
            padding: 0.6rem 1.5rem 0.6rem 3.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .enlace-submenu:hover {
            background: rgba(255,255,255,0.1);
            color: var(--texto-claro);
            padding-left: 4rem;
        }

        .enlace-submenu.activo {
            background: rgba(255,255,255,0.15);
            color: var(--texto-claro);
        }

        .enlace-submenu i {
            font-size: 0.9rem;
            margin-right: 0.75rem;
            width: 16px;
            text-align: center;
        }

        /* HEADER SUPERIOR */
        .header-superior {
            position: fixed;
            top: 0;
            left: var(--ancho-sidebar);
            right: 0;
            height: var(--altura-header);
            background: white;
            box-shadow: var(--sombra);
            z-index: 999;
            transition: left 0.3s ease;
            display: flex;
            align-items: center;
            padding: 0 2rem;
        }

        .header-superior.expandido {
            left: 0;
        }

        .boton-menu {
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--texto-oscuro);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background 0.3s ease;
        }

        .boton-menu:hover {
            background: var(--fondo-claro);
        }

        .info-usuario {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .dropdown-usuario {
            position: relative;
        }

        .boton-usuario {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .boton-usuario:hover {
            background: var(--fondo-claro);
        }

        .avatar-usuario {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--color-primario);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .menu-usuario {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 0.5rem;
            box-shadow: var(--sombra-grande);
            min-width: 200px;
            padding: 0.5rem 0;
            display: none;
            z-index: 1001;
        }

        .menu-usuario.abierto {
            display: block;
        }

        .item-usuario {
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--texto-oscuro);
            text-decoration: none;
        }

        .item-usuario:hover {
            background: var(--fondo-claro);
            color: var(--texto-oscuro);
        }

        .item-usuario.peligro:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        /* CONTENIDO PRINCIPAL */
        .contenido-principal {
            margin-left: var(--ancho-sidebar);
            margin-top: var(--altura-header);
            padding: 2rem;
            min-height: calc(100vh - var(--altura-header));
            transition: margin-left 0.3s ease;
        }

        .contenido-principal.expandido {
            margin-left: 0;
        }

        /* OVERLAY PARA MÓVIL - SIMPLIFICADO */
        .overlay {
            display: none;
        }

        /* BARRA DE PROGRESO */
        .barra-progreso {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: rgba(37, 99, 235, 0.2);
            z-index: 9999;
        }

        .progreso {
            height: 100%;
            background: var(--color-primario);
            width: 0%;
            transition: width 0.3s ease;
        }

        /* FOOTER */
        .footer-simple {
            margin-left: var(--ancho-sidebar);
            background: white;
            padding: 1rem 2rem;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 0.875rem;
            transition: margin-left 0.3s ease;
        }

        .footer-simple.expandido {
            margin-left: 0;
        }

        /* RESPONSIVE SIMPLIFICADO */
        @media (max-width: 768px) {
            .contenido-principal {
                padding: 1rem;
            }
        }

        /* ANIMACIONES */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .contenido-principal > * {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>
<body>
    <!-- BARRA DE PROGRESO -->
    <div class="barra-progreso">
        <div class="progreso" id="barraProgresoElemento"></div>
    </div>

    <!-- OVERLAY SIMPLIFICADO -->
    <div class="overlay" id="overlay"></div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <!-- LOGO -->
        <div class="logo-sidebar">
            <img src="<?= asset('./images/cit.png') ?>" alt="Logo">
            <h4>Sistema Celulares</h4>
            <small>Gestión Integral</small>
        </div>

        <!-- MENÚ -->
        <nav class="menu-sidebar">
            <!-- INICIO -->
            <<div class="item-menu">
    <a href="/proyecto_jjjc/inicio" class="enlace-menu">
        <i class="bi bi-house-fill"></i>
        <span class="texto">Inicio</span>
    </a>
</div>

            <!-- VENTAS -->
            <div class="item-menu">
                <button class="enlace-menu" onclick="alternarSubmenu('menuVentas')">
                    <i class="bi bi-cart3"></i>
                    <span class="texto">Ventas</span>
                    <i class="bi bi-chevron-right flecha"></i>
                </button>
                <div class="submenu" id="menuVentas">
                    <a href="/proyecto_jjjc/ventas" class="enlace-submenu">
                        <i class="bi bi-plus-circle"></i>
                        Nueva Venta
                    </a>
                    <a href="/proyecto_jjjc/reparaciones" class="enlace-submenu">
                        <i class="bi bi-wrench"></i>
                        Órdenes de Reparación
                    </a>
                </div>
            </div>

            <!-- INVENTARIO -->
            <div class="item-menu">
                <a href="/proyecto_jjjc/inventario" class="enlace-menu">
                    <i class="bi bi-boxes"></i>
                    <span class="texto">Inventario</span>
                </a>
            </div>

            <!-- CLIENTES -->
            <div class="item-menu">
                <a href="/proyecto_jjjc/clientes" class="enlace-menu">
                    <i class="bi bi-people"></i>
                    <span class="texto">Clientes</span>
                </a>
            </div>

            <!-- CATÁLOGOS -->
            <div class="item-menu">
                <button class="enlace-menu" onclick="alternarSubmenu('menuCatalogos')">
                    <i class="bi bi-list-ul"></i>
                    <span class="texto">Catálogos</span>
                    <i class="bi bi-chevron-right flecha"></i>
                </button>
                <div class="submenu" id="menuCatalogos">
                    <a href="/proyecto_jjjc/marcas" class="enlace-submenu">
                        <i class="bi bi-phone"></i>
                        Marcas de Celulares
                    </a>
                    <a href="/proyecto_jjjc/empleados" class="enlace-submenu">
                        <i class="bi bi-person-badge"></i>
                        Empleados
                    </a>
                    <a href="/proyecto_jjjc/servicios" class="enlace-submenu">
                        <i class="bi bi-tools"></i>
                        Tipos de Servicios
                    </a>
                </div>
            </div>

            <!-- REPORTES -->
            <div class="item-menu">
                <button class="enlace-menu" onclick="alternarSubmenu('menuReportes')">
                    <i class="bi bi-graph-up"></i>
                    <span class="texto">Reportes</span>
                    <i class="bi bi-chevron-right flecha"></i>
                </button>
                <div class="submenu" id="menuReportes">
                    <a href="/proyecto_jjjc/estadisticas" class="enlace-submenu">
                        <i class="bi bi-pie-chart"></i>
                        Estadisticas
                    </a>
                </div>
            </div>

            <!-- ADMINISTRACIÓN -->
            <div class="item-menu">
                <button class="enlace-menu" onclick="alternarSubmenu('menuAdmin')">
                    <i class="bi bi-shield-lock"></i>
                    <span class="texto">Administración</span>
                    <i class="bi bi-chevron-right flecha"></i>
                </button>
                <div class="submenu" id="menuAdmin">
                    <a href="/proyecto_jjjc/registro" class="enlace-submenu">
                        <i class="bi bi-person-plus"></i>
                        Registrar Usuario
                    </a>
                    <a href="/proyecto_jjjc/aplicaciones" class="enlace-submenu">
                        <i class="bi bi-app"></i>
                        Aplicaciones
                    </a>
                    <a href="/proyecto_jjjc/permisos" class="enlace-submenu">
                        <i class="bi bi-shield-check"></i>
                        Permisos
                    </a>
                    <a href="/proyecto_jjjc/asigPermisos" class="enlace-submenu">
                        <i class="bi bi-person-check"></i>
                        Asignar Permisos
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- HEADER SUPERIOR -->
    <header class="header-superior" id="headerSuperior">
        <button class="boton-menu" id="botonMenu">
            <i class="bi bi-list"></i>
        </button>

        <div class="info-usuario">
            <div class="dropdown-usuario">
                <button class="boton-usuario" id="botonUsuario">
                    <div class="avatar-usuario">
                        <i class="bi bi-person"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: 0.9rem;">Usuario</div>
                        <div style="font-size: 0.75rem; color: #64748b;">Administrador</div>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="menu-usuario" id="menuUsuario">
                    <a href="/proyecto_jjjc/perfil" class="item-usuario">
                        <i class="bi bi-person"></i>
                        Mi Perfil
                    </a>
                    <a href="/proyecto_jjjc/configuracion" class="item-usuario">
                        <i class="bi bi-gear"></i>
                        Configuración
                    </a>
                    <div style="height: 1px; background: #e2e8f0; margin: 0.5rem 0;"></div>
                    <a href="/proyecto_jjjc/login" class="item-usuario">
                        <i class="bi bi-box-arrow-left"></i>
                        Iniciar Sesión
                    </a>
                    <a href="/proyecto_jjjc/logout" class="item-usuario peligro">
                        <i class="bi bi-power"></i>
                        Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenido-principal" id="contenidoPrincipal">
        <?php echo $contenido; ?>
    </main>

    <!-- FOOTER -->
    <footer class="footer-simple" id="footerSimple">
        <div>
            <strong>Sistema de Gestión de Celulares</strong> - 
            Comando de Informática y Tecnología © <?= date('Y') ?>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // VARIABLES GLOBALES
        let sidebarAbierto = true;

        // ELEMENTOS DEL DOM
        const sidebar = document.getElementById('sidebar');
        const headerSuperior = document.getElementById('headerSuperior');
        const contenidoPrincipal = document.getElementById('contenidoPrincipal');
        const footerSimple = document.getElementById('footerSimple');
        const botonMenu = document.getElementById('botonMenu');
        const overlay = document.getElementById('overlay');
        const botonUsuario = document.getElementById('botonUsuario');
        const menuUsuario = document.getElementById('menuUsuario');
        const barraProgreso = document.getElementById('barraProgresoElemento');

        // FUNCIÓN PARA ALTERNAR SIDEBAR
        function alternarSidebar() {
            sidebarAbierto = !sidebarAbierto;
            
            if (sidebarAbierto) {
                sidebar.classList.remove('oculto');
                headerSuperior.classList.remove('expandido');
                contenidoPrincipal.classList.remove('expandido');
                footerSimple.classList.remove('expandido');
            } else {
                sidebar.classList.add('oculto');
                headerSuperior.classList.add('expandido');
                contenidoPrincipal.classList.add('expandido');  
                footerSimple.classList.add('expandido');
            }
        }

        // FUNCIÓN PARA ALTERNAR SUBMENÚS
        function alternarSubmenu(idSubmenu) {
            const submenu = document.getElementById(idSubmenu);
            const boton = submenu.previousElementSibling;
            
            // Cerrar otros submenús
            document.querySelectorAll('.submenu').forEach(sub => {
                if (sub.id !== idSubmenu && sub.classList.contains('abierto')) {
                    sub.classList.remove('abierto');
                    sub.previousElementSibling.classList.remove('expandido');
                }
            });
            
            // Alternar el submenú actual
            submenu.classList.toggle('abierto');
            boton.classList.toggle('expandido');
        }

        // FUNCIÓN PARA ALTERNAR MENÚ DE USUARIO
        function alternarMenuUsuario() {
            menuUsuario.classList.toggle('abierto');
        }

        // FUNCIÓN PARA MARCAR ENLACE ACTIVO
        function marcarEnlaceActivo() {
            const urlActual = window.location.pathname;
            const enlaces = document.querySelectorAll('.enlace-menu, .enlace-submenu');
            
            enlaces.forEach(enlace => {
                enlace.classList.remove('activo');
                if (enlace.getAttribute('href') === urlActual) {
                    enlace.classList.add('activo');
                    
                    if (enlace.classList.contains('enlace-submenu')) {
                        const submenu = enlace.closest('.submenu');
                        submenu.classList.add('abierto');
                        submenu.previousElementSibling.classList.add('expandido');
                    }
                }
            });
        }

        // FUNCIÓN PARA SIMULAR BARRA DE PROGRESO
        function simularBarraProgreso() {
            barraProgreso.style.width = '0%';
            
            setTimeout(() => barraProgreso.style.width = '30%', 100);
            setTimeout(() => barraProgreso.style.width = '60%', 300);
            setTimeout(() => barraProgreso.style.width = '100%', 800);
            setTimeout(() => barraProgreso.style.width = '0%', 1200);
        }

        // EVENT LISTENERS
        botonMenu.addEventListener('click', alternarSidebar);
        botonUsuario.addEventListener('click', alternarMenuUsuario);

        // Cerrar menú de usuario al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!botonUsuario.contains(e.target) && !menuUsuario.contains(e.target)) {
                menuUsuario.classList.remove('abierto');
            }
        });

        // INICIALIZACIÓN
        document.addEventListener('DOMContentLoaded', function() {
            marcarEnlaceActivo();
            simularBarraProgreso();
        });

        // Simular barra de progreso en navegación
        document.querySelectorAll('a').forEach(enlace => {
            enlace.addEventListener('click', function(e) {
                if (this.href && this.href !== '#') {
                    simularBarraProgreso();
                }
            });
        });
    </script>
    
    <script src="<?= asset('build/js/app.js') ?>"></script>
</body>
</html>