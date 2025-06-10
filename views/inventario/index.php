<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Celulares</title>
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

        .stock-bajo {
            background-color: #ffebee !important;
        }
        
        .stock-medio {
            background-color: #fff3e0 !important;
        }
        
        .stock-alto {
            background-color: #e8f5e8 !important;
        }
    </style>
</head>
<body class="gradient-custom-3">
    <div class="container py-5">
        <!-- FORMULARIO PARA INVENTARIO -->
        <div class="row justify-content-center mb-5">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">
                            <i class="bi bi-phone me-2"></i>Inventario de Celulares
                        </h2>
                        
                        <form id="FormInventario" method="POST">
                            <input type="hidden" id="invent_id" name="invent_id">
                            
                            <!-- Fila 1: Marca y Modelo -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <select id="invent_marca_id" name="invent_marca_id" class="form-select" required>
                                            <option value="">Seleccione una marca</option>
                                        </select>
                                        <label class="form-label" for="invent_marca_id">Marca</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="invent_modelo" name="invent_modelo" class="form-control" maxlength="100" required />
                                        <label class="form-label" for="invent_modelo">Modelo</label>
                                        <div class="form-text">Ejemplo: Galaxy S23, iPhone 14, etc.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fila 2: Precios -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="number" id="invent_precio_compra" name="invent_precio_compra" 
                                               class="form-control" step="0.01" min="0.01" required />
                                        <label class="form-label" for="invent_precio_compra">Precio de Compra (Q.)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="number" id="invent_precio_venta" name="invent_precio_venta" 
                                               class="form-control" step="0.01" min="0.01" required />
                                        <label class="form-label" for="invent_precio_venta">Precio de Venta (Q.)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Fila 3: Cantidad y Ganancia calculada -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="number" id="invent_cantidad_disponible" name="invent_cantidad_disponible" 
                                               class="form-control" min="0" required />
                                        <label class="form-label" for="invent_cantidad_disponible">Cantidad Disponible</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="ganancia_calculada" class="form-control" readonly />
                                        <label class="form-label" for="ganancia_calculada">Ganancia por Unidad (Q.)</label>
                                        <div class="form-text">Se calcula automáticamente</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fila 4: Descripción -->
                            <div class="form-outline mb-4">
                                <textarea id="invent_descripcion" name="invent_descripcion" class="form-control" maxlength="250" rows="2" required></textarea>
                                <label class="form-label" for="invent_descripcion">Descripción</label>
                                <div class="form-text">Características del celular</div>
                            </div>

                            <div class="d-flex justify-content-center gap-2">
                                <button type="submit" id="BtnGuardar" class="btn btn-success gradient-custom-4">
                                    <i class="bi bi-save me-1"></i>Agregar al Inventario
                                </button>
                                <button type="button" id="BtnModificar" class="btn btn-warning d-none">
                                    <i class="bi bi-pencil me-1"></i>Modificar Producto
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

        <!-- SECCIÓN PARA INVENTARIO -->
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body">
                        <h3 class="text-center mb-4">
                            <i class="bi bi-boxes me-2"></i>Inventario de Productos
                        </h3>
                        
                        <div class="d-flex justify-content-center mb-3">
                            <button type="button" id="BtnBuscarInventario" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Actualizar Inventario
                            </button>
                        </div>

                        <!-- Leyenda de colores -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <small class="text-muted">
                                    <span class="badge stock-alto text-dark me-2">Stock Alto (10+)</span>
                                    <span class="badge stock-medio text-dark me-2">Stock Medio (5-9)</span>
                                    <span class="badge stock-bajo text-dark">Stock Bajo (0-4)</span>
                                </small>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Precio Compra</th>
                                        <th>Precio Venta</th>
                                        <th>Ganancia</th>
                                        <th>Stock</th>
                                        <th>Valor Total</th>
                                        <th>Fecha Ingreso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="TablaInventario">
                                    <!-- Los productos se cargarán aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/inventario/index.js') ?>"></script>
</body>
</html>