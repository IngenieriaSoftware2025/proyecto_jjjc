<style>
    :root {
        --color-primario: #2563eb;
        --color-secundario: #1e40af;
        --color-acento: #3b82f6;
        --color-exito: #059669;
        --color-advertencia: #d97706;
        --color-peligro: #dc2626;
        --color-info: #0891b2;
        --fondo-claro: #f8fafc;
        --texto-oscuro: #1e293b;
        --sombra-suave: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --sombra-media: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --sombra-fuerte: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .contenedor-principal {
        padding: 2rem 0;
    }

    /* HEADER BIENVENIDA */
    .header-bienvenida {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 3rem 2rem;
        margin-bottom: 3rem;
        box-shadow: var(--sombra-fuerte);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .header-bienvenida::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(37,99,235,0.1) 0%, transparent 70%);
        animation: rotarFondo 20s linear infinite;
    }

    @keyframes rotarFondo {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .header-bienvenida h1 {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--color-primario), var(--color-secundario));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }

    .header-bienvenida .subtitulo {
        font-size: 1.3rem;
        color: var(--texto-oscuro);
        margin-bottom: 2rem;
        position: relative;
        z-index: 2;
    }

    .logo-empresa {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 2rem;
        background: linear-gradient(135deg, var(--color-primario), var(--color-acento));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        box-shadow: var(--sombra-media);
        animation: pulsar 3s ease-in-out infinite;
        position: relative;
        z-index: 2;
    }

    @keyframes pulsar {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* TARJETAS ESTADÍSTICAS */
    .tarjeta-estadistica {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--sombra-media);
        border-left: 5px solid var(--color-acento);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .tarjeta-estadistica::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, transparent 0%, rgba(37,99,235,0.03) 100%);
        pointer-events: none;
    }

    .tarjeta-estadistica:hover {
        transform: translateY(-5px);
        box-shadow: var(--sombra-fuerte);
        border-left-color: var(--color-primario);
    }

    .tarjeta-estadistica.exito {
        border-left-color: var(--color-exito);
    }

    .tarjeta-estadistica.advertencia {
        border-left-color: var(--color-advertencia);
    }

    .tarjeta-estadistica.peligro {
        border-left-color: var(--color-peligro);
    }

    .tarjeta-estadistica.info {
        border-left-color: var(--color-info);
    }

    .icono-estadistica {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .icono-estadistica.primario { color: var(--color-primario); }
    .icono-estadistica.exito { color: var(--color-exito); }
    .icono-estadistica.advertencia { color: var(--color-advertencia); }
    .icono-estadistica.peligro { color: var(--color-peligro); }
    .icono-estadistica.info { color: var(--color-info); }

    .numero-estadistica {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--texto-oscuro);
        margin-bottom: 0.5rem;
    }

    .titulo-estadistica {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--texto-oscuro);
        margin-bottom: 0.5rem;
    }

    .descripcion-estadistica {
        color: #64748b;
        font-size: 0.9rem;
    }

    /* ACCESOS RÁPIDOS */
    .tarjeta-acceso {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        box-shadow: var(--sombra-suave);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        display: block;
    }

    .tarjeta-acceso::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s ease;
    }
    .contenedor-principal {
        padding: 2rem 0;
    }

    /* HEADER BIENVENIDA */
    .header-bienvenida {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        text-align: center;
    }

    .header-bienvenida h1 {
        color: #2563eb;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .header-bienvenida p {
        color: #64748b;
        font-size: 1.1rem;
        margin: 0;
    }

    /* TARJETAS ESTADÍSTICAS */
    .tarjeta-estadistica {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.2s ease;
    }

    .tarjeta-estadistica:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .icono-estadistica {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .icono-estadistica.primario { color: #2563eb; }
    .icono-estadistica.exito { color: #059669; }
    .icono-estadistica.info { color: #0891b2; }
    .icono-estadistica.advertencia { color: #d97706; }

    .numero-estadistica {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .titulo-estadistica {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .descripcion-estadistica {
        color: #64748b;
        font-size: 0.9rem;
    }

    /* ACCESOS RÁPIDOS */
    .tarjeta-acceso {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
        display: block;
        margin-bottom: 1rem;
    }

    .tarjeta-acceso:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        color: inherit;
        text-decoration: none;
    }

    .icono-acceso {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .icono-acceso.ventas { color: #059669; }
    .icono-acceso.inventario { color: #0891b2; }
    .icono-acceso.clientes { color: #2563eb; }
    .icono-acceso.reportes { color: #d97706; }
    .icono-acceso.empleados { color: #7c3aed; }
    .icono-acceso.configuracion { color: #dc2626; }

    .titulo-acceso {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1e293b;
    }

    .descripcion-acceso {
        color: #64748b;
        font-size: 0.9rem;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .header-bienvenida h1 {
            font-size: 2rem;
        }
        
        .numero-estadistica {
            font-size: 1.5rem;
        }
        
        .contenedor-principal {
            padding: 1rem 0;
        }
    }
</style>

<div class="container contenedor-principal">
    
    <!-- HEADER DE BIENVENIDA -->
    <div class="header-bienvenida">
        <h1><i class="bi bi-phone me-3"></i>Sistema de Celulares</h1>
        <p>Panel de control para gestión de tu empresa</p>
    </div>

    <!-- ESTADÍSTICAS PRINCIPALES -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="tarjeta-estadistica">
                <i class="bi bi-phone icono-estadistica primario"></i>
                <div class="numero-estadistica">1,250</div>
                <div class="titulo-estadistica">Teléfonos en Stock</div>
                <div class="descripcion-estadistica">Inventario disponible</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="tarjeta-estadistica">
                <i class="bi bi-cart-check icono-estadistica exito"></i>
                <div class="numero-estadistica">2,847</div>
                <div class="titulo-estadistica">Ventas del Mes</div>
                <div class="descripcion-estadistica">+15% vs mes anterior</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="tarjeta-estadistica">
                <i class="bi bi-people icono-estadistica info"></i>
                <div class="numero-estadistica">9,632</div>
                <div class="titulo-estadistica">Clientes Activos</div>
                <div class="descripcion-estadistica">Base de clientes</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="tarjeta-estadistica">
                <i class="bi bi-tools icono-estadistica advertencia"></i>
                <div class="numero-estadistica">156</div>
                <div class="titulo-estadistica">Reparaciones</div>
                <div class="descripcion-estadistica">En proceso hoy</div>
            </div>
        </div>
    </div>

    <!-- ACCESOS RÁPIDOS -->
    <div class="row">
        <div class="col-12">
            <h3 class="text-center mb-4" style="color: #2563eb;">Accesos Rápidos</h3>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="/proyecto_jjjc/ventas" class="tarjeta-acceso">
                <i class="bi bi-cart-plus icono-acceso ventas"></i>
                <div class="titulo-acceso">Nueva Venta</div>
                <div class="descripcion-acceso">Registrar venta de equipos</div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="/proyecto_jjjc/inventario" class="tarjeta-acceso">
                <i class="bi bi-boxes icono-acceso inventario"></i>
                <div class="titulo-acceso">Inventario</div>
                <div class="descripcion-acceso">Gestionar stock de celulares</div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="/proyecto_jjjc/clientes" class="tarjeta-acceso">
                <i class="bi bi-person-plus icono-acceso clientes"></i>
                <div class="titulo-acceso">Clientes</div>
                <div class="descripcion-acceso">Gestionar información de clientes</div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="/proyecto_jjjc/estadisticas" class="tarjeta-acceso">
                <i class="bi bi-graph-up icono-acceso reportes"></i>
                <div class="titulo-acceso">Reportes</div>
                <div class="descripcion-acceso">Ver estadísticas del negocio</div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="/proyecto_jjjc/empleados" class="tarjeta-acceso">
                <i class="bi bi-person-badge icono-acceso empleados"></i>
                <div class="titulo-acceso">Empleados</div>
                <div class="descripcion-acceso">Gestionar personal técnico</div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="/proyecto_jjjc/aplicaciones" class="tarjeta-acceso">
                <i class="bi bi-gear icono-acceso configuracion"></i>
                <div class="titulo-acceso">Configuración</div>
                <div class="descripcion-acceso">Ajustes del sistema</div>
            </a>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/inicio.js') ?>"></script>