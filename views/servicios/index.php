<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de Servicios</title>
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

        .precio-alto {
            background-color: #ffebee !important;
        }
        
        .precio-medio {
            background-color: #fff3e0 !important;
        }
        
        .precio-bajo {
            background-color: #e8f5e8 !important;
        }
    </style>
</head>
<body class="gradient-custom-3">
    <div class="container py-5">
        <!-- FORMULARIO PARA SERVICIOS -->
        <div class="row justify-content-center mb-5">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">
                            <i class="bi bi-tools me-2"></i>Tipos de Servicios
                        </h2>
                        
                        <form id="FormServicios" method="POST">
                            <input type="hidden" id="serv_id" name="serv_id">
                            
                            <div class="form-outline mb-3">
                                <input type="text" id="serv_nombre" name="serv_nombre" class="form-control" maxlength="100" required />
                                <label class="form-label" for="serv_nombre">Nombre del Servicio</label>
                                <div class="form-text">Ejemplo: Cambio de Pantalla, Formateo, etc.</div>
                            </div>

                            <div class="form-outline mb-3">
                                <input type="number" id="serv_precio" name="serv_precio" class="form-control" 
                                       step="0.01" min="0.01" required />
                                <label class="form-label" for="serv_precio">Precio del Servicio (Q.)</label>
                                <div class="form-text">Precio en quetzales</div>
                            </div>

                            <div class="form-outline mb-4">
                                <textarea id="serv_descripcion" name="serv_descripcion" class="form-control" 
                                          maxlength="250" rows="3" required></textarea>
                                <label class="form-label" for="serv_descripcion">Descripción del Servicio</label>
                                <div class="form-text">Detalles del servicio que se ofrece</div>
                            </div>

                            <div class="d-flex justify-content-center gap-2">
                                <button type="submit" id="BtnGuardar" class="btn btn-success gradient-custom-4">
                                    <i class="bi bi-save me-1"></i>Guardar Servicio
                                </button>
                                <button type="button" id="BtnModificar" class="btn btn-warning d-none">
                                    <i class="bi bi-pencil me-1"></i>Modificar Servicio
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

        <!-- SECCIÓN PARA BUSCAR SERVICIOS -->
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body">
                        <h3 class="text-center mb-4">
                            <i class="bi bi-list-check me-2"></i>Servicios Disponibles
                        </h3>
                        
                        <div class="d-flex justify-content-center mb-3">
                            <button type="button" id="BtnBuscarServicios" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Buscar Servicios
                            </button>
                        </div>

                        <!-- Leyenda de precios -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <small class="text-muted">
                                    <span class="badge precio-bajo text-dark me-2">Precio Económico (Q.1 - Q.149)</span>
                                    <span class="badge precio-medio text-dark me-2">Precio Moderado (Q.150 - Q.399)</span>
                                    <span class="badge precio-alto text-dark">Precio Premium (Q.400+)</span>
                                </small>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Servicio</th>
                                        <th>Precio</th>
                                        <th>Descripción</th>
                                        <th>Categoría</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="TablaServicios">
                                    <!-- Los servicios se cargarán aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/servicios/index.js') ?>"></script>
</body>
</html>