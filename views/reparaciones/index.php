<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes de Reparación</title>
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

        .estado-recibido {
            background-color: #e1f5fe !important;
        }
        
        .estado-proceso {
            background-color: #fff3e0 !important;
        }
        
        .estado-terminado {
            background-color: #e8f5e8 !important;
        }

        .estado-entregado {
            background-color: #f3e5f5 !important;
        }

        .precio-display {
            font-weight: bold;
            color: #28a745;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 8px 12px;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.2rem;
        }

        .d-flex.gap-1 > .btn {
            margin-right: 0.25rem;
        }

        .d-flex.gap-1 > .btn:last-child {
            margin-right: 0;
        }

        .btn-sm i {
            font-size: 0.8rem;
        }
    </style>
</head>
<body class="gradient-custom-3">
    <div class="container py-5">
        <!-- FORMULARIO PARA ÓRDENES DE REPARACIÓN -->
        <div class="row justify-content-center mb-5">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">
                            <i class="bi bi-tools me-2"></i>Órdenes de Reparación
                        </h2>
                        
                        <form id="FormReparaciones" method="POST">
                            <input type="hidden" id="orden_id" name="orden_id">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <select id="orden_cli_id" name="orden_cli_id" class="form-select" required>
                                            <option value="">Seleccione un cliente</option>
                                        </select>
                                        <label class="form-label" for="orden_cli_id">Cliente</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <select id="orden_empleado_id" name="orden_empleado_id" class="form-select" required>
                                            <option value="">Seleccione un empleado</option>
                                        </select>
                                        <label class="form-label" for="orden_empleado_id">Empleado Asignado</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <select id="orden_serv_id" name="orden_serv_id" class="form-select" required>
                                            <option value="">Seleccione un servicio</option>
                                        </select>
                                        <label class="form-label" for="orden_serv_id">Tipo de Servicio</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <select id="orden_estado" name="orden_estado" class="form-select">
                                            <option value="">Seleccione un estado</option>
                                        </select>
                                        <label class="form-label" for="orden_estado">Estado de la Orden</label>
                                        <div class="form-text">Opcional - por defecto será "Recibido"</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="orden_marca_celular" name="orden_marca_celular" 
                                               class="form-control" maxlength="100" required />
                                        <label class="form-label" for="orden_marca_celular">Marca del Celular</label>
                                        <div class="form-text">Ejemplo: Samsung, Apple, Xiaomi</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="orden_modelo_celular" name="orden_modelo_celular" 
                                               class="form-control" maxlength="100" required />
                                        <label class="form-label" for="orden_modelo_celular">Modelo del Celular</label>
                                        <div class="form-text">Ejemplo: Galaxy S23, iPhone 14</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="mostrar_precio" class="precio-display" readonly />
                                        <label class="form-label" for="mostrar_precio">Precio del Servicio</label>
                                        <div class="form-text">Se actualiza automáticamente al seleccionar servicio</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-outline mb-3">
                                <textarea id="orden_motivo_ingreso" name="orden_motivo_ingreso" 
                                          class="form-control" maxlength="250" rows="2" required></textarea>
                                <label class="form-label" for="orden_motivo_ingreso">Motivo de Ingreso</label>
                                <div class="form-text">Descripción del problema reportado por el cliente</div>
                            </div>

                            <div class="form-outline mb-3">
                                <textarea id="orden_diagnostico" name="orden_diagnostico" 
                                          class="form-control" maxlength="250" rows="2"></textarea>
                                <label class="form-label" for="orden_diagnostico">Diagnóstico Técnico (Opcional)</label>
                                <div class="form-text">Diagnóstico realizado por el técnico</div>
                            </div>

                            <div class="form-outline mb-4">
                                <textarea id="orden_observaciones" name="orden_observaciones" 
                                          class="form-control" maxlength="250" rows="2"></textarea>
                                <label class="form-label" for="orden_observaciones">Observaciones Adicionales (Opcional)</label>
                                <div class="form-text">Notas importantes sobre la reparación</div>
                            </div>

                            <div class="d-flex justify-content-center gap-2">
                                <button type="submit" id="BtnGuardar" class="btn btn-success gradient-custom-4">
                                    <i class="bi bi-save me-1"></i>Crear Orden
                                </button>
                                <button type="button" id="BtnModificar" class="btn btn-warning d-none">
                                    <i class="bi bi-pencil me-1"></i>Actualizar Orden
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
                            <i class="bi bi-list-check me-2"></i>Órdenes de Reparación Registradas
                        </h3>
                        
                        <div class="d-flex justify-content-center mb-3">
                            <button type="button" id="BtnBuscarOrdenes" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Actualizar Lista de Órdenes
                            </button>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <small class="text-muted">
                                    <span class="badge estado-recibido text-dark me-2">Recibido</span>
                                    <span class="badge estado-proceso text-dark me-2">En Proceso</span>
                                    <span class="badge estado-terminado text-dark me-2">Terminado</span>
                                    <span class="badge estado-entregado text-dark">Entregado</span>
                                </small>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Cliente</th>
                                        <th>Celular</th>
                                        <th>Servicio</th>
                                        <th>Empleado</th>
                                        <th>Estado</th>
                                        <th>Precio</th>
                                        <th>Fecha Ingreso</th>
                                        <th style="width: 150px; min-width: 150px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="TablaOrdenes">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/reparaciones/index.js') ?>"></script>
</body>
</html>