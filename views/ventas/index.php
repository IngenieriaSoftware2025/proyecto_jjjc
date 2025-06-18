<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Ventas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .gradient-custom-3 {
            background: #84fab0;
            background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));
            background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));
        }
        
        .gradient-custom-4 {
            background: #84fab0;
            background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));
            background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));
        }
        
        .form-outline {
            margin-bottom: 1rem;
        }

        .producto-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
        }

        .total-venta {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body class="gradient-custom-3">
    <div class="container py-5">
        <!-- FORMULARIO PARA NUEVA VENTA -->
        <div class="row justify-content-center mb-5">
            <div class="col-12">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">
                            <i class="bi bi-cart-plus me-2"></i>Nueva Venta
                        </h2>
                        
                        <form id="FormVentas" method="POST">
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <select id="venta_cliente_id" name="venta_cliente_id" class="form-select" required>
                                            <option value="">Seleccione un cliente</option>
                                        </select>
                                        <label class="form-label" for="venta_cliente_id">Cliente</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <select id="producto_selector" class="form-select">
                                            <option value="">Seleccione un producto</option>
                                        </select>
                                        <label class="form-label" for="producto_selector">Agregar Producto</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5>Productos en la Venta:</h5>
                                    <div id="productos_venta">
                                        <p class="text-muted">No hay productos agregados</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12 text-end">
                                    <div class="total-venta">
                                        Total: Q. <span id="total_venta">0.00</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center gap-2">
                                <button type="submit" id="BtnGuardar" class="btn btn-success gradient-custom-4">
                                    <i class="bi bi-save me-1"></i>Procesar Venta
                                </button>
                                <button type="button" id="BtnLimpiar" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body">
                        <h3 class="text-center mb-4">
                            <i class="bi bi-clock-history me-2"></i>Historial de Ventas
                        </h3>
                        
                        <div class="d-flex justify-content-center mb-3">
                            <button type="button" id="BtnBuscarVentas" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Actualizar Historial
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Cliente</th>
                                        <th>Total</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Tipo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="TablaVentas">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalDetalleVenta" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="contenido_detalle">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/ventas/index.js') ?>"></script>
</body>
</html>