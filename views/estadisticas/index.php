<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas del Sistema</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .gradient-custom-3 {
            background: #84fab0;
            background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));
            background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));
        }
        
        .tarjeta-grafico {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            height: 500px;
        }
        
        .titulo-pagina {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            margin-bottom: 2rem;
        }
        
        .boton-actualizar {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .boton-actualizar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            color: white;
        }
        
        .canvas-contenedor {
            position: relative;
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body class="gradient-custom-3">
    <div class="container py-5">
        
0        <div class="text-center mb-5">
            <h1 class="titulo-pagina">
                <i class="bi bi-graph-up me-3"></i>Estadísticas del Sistema
            </h1>
            <p class="lead text-muted">Dashboard completo de ventas, productos y clientes</p>
            <button type="button" id="BtnActualizarEstadisticas" class="boton-actualizar">
                <i class="bi bi-arrow-clockwise me-2"></i>Actualizar Datos
            </button>
        </div>

        <div style="display: none;">
            <div id="total-ventas">0</div>
            <div id="total-productos">0</div>
            <div id="total-clientes">0</div>
            <div id="total-reparaciones">0</div>
            <div id="ingresos-totales">0</div>
            <div id="cantidad-inventario">0</div>
        </div>

        <!-- GRÁFICOS -->
        <div class="row">
            <!-- Gráfico 1: Productos más vendidos (Barras) -->
            <div class="col-lg-6 col-md-12">
                <div class="tarjeta-grafico">
                    <h4 class="text-center mb-4">
                        <i class="bi bi-bar-chart me-2"></i>Productos Más Vendidos
                    </h4>
                    <div class="canvas-contenedor">
                        <canvas id="grafico1"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico 2: Distribución de productos (Pie) -->
            <div class="col-lg-6 col-md-12">
                <div class="tarjeta-grafico">
                    <h4 class="text-center mb-4">
                        <i class="bi bi-pie-chart me-2"></i>Distribución de Productos
                    </h4>
                    <div class="canvas-contenedor">
                        <canvas id="grafico2"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gráfico 3: Clientes con más compras (Doughnut) -->
            <div class="col-lg-6 col-md-12">
                <div class="tarjeta-grafico">
                    <h4 class="text-center mb-4">
                        <i class="bi bi-person-check me-2"></i>Top Clientes
                    </h4>
                    <div class="canvas-contenedor">
                        <canvas id="grafico3"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico 4: Ventas por mes (Línea) -->
            <div class="col-lg-6 col-md-12">
                <div class="tarjeta-grafico">
                    <h4 class="text-center mb-4">
                        <i class="bi bi-graph-up me-2"></i>Ventas Mensuales <span id="anio-actual"></span>
                    </h4>
                    <div class="canvas-contenedor">
                        <canvas id="grafico4"></canvas>
                    </div>
                </div>
            </div>
        </div>

        
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= asset('build/js/estadisticas/index.js') ?>"></script>
</body>
</html>