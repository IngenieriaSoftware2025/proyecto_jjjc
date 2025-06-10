<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
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
    </style>
</head>
<body class="gradient-custom-3">
    <div class="container py-5">
        <!-- FORMULARIO PARA CLIENTES -->
        <div class="row justify-content-center mb-5">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">
                            <i class="bi bi-people me-2"></i>Gestión de Clientes
                        </h2>
                        
                        <form id="FormClientes" method="POST">
                            <input type="hidden" id="cli_id" name="cli_id">
                            
                            <!-- Fila 1: Nombres y Apellidos -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="cli_nombres" name="cli_nombres" class="form-control" maxlength="255" required />
                                        <label class="form-label" for="cli_nombres">Nombres</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="cli_apellidos" name="cli_apellidos" class="form-control" maxlength="255" required />
                                        <label class="form-label" for="cli_apellidos">Apellidos</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Fila 2: Teléfono y NIT -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="cli_telefono" name="cli_telefono" class="form-control" 
                                               maxlength="8" pattern="[0-9]{8}" title="Debe contener exactamente 8 dígitos" required />
                                        <label class="form-label" for="cli_telefono">Teléfono</label>
                                        <div class="form-text">8 dígitos (ejemplo: 12345678)</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="cli_nit" name="cli_nit" class="form-control" maxlength="15" />
                                        <label class="form-label" for="cli_nit">NIT (Opcional)</label>
                                        <div class="form-text">Número de identificación tributaria</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fila 3: Correo -->
                            <div class="form-outline mb-3">
                                <input type="email" id="cli_correo" name="cli_correo" class="form-control" maxlength="100" />
                                <label class="form-label" for="cli_correo">Correo Electrónico (Opcional)</label>
                            </div>

                            <!-- Fila 4: Dirección -->
                            <div class="form-outline mb-4">
                                <textarea id="cli_direccion" name="cli_direccion" class="form-control" maxlength="250" rows="2" required></textarea>
                                <label class="form-label" for="cli_direccion">Dirección</label>
                                <div class="form-text">Dirección completa del cliente</div>
                            </div>

                            <div class="d-flex justify-content-center gap-2">
                                <button type="submit" id="BtnGuardar" class="btn btn-success gradient-custom-4">
                                    <i class="bi bi-save me-1"></i>Guardar Cliente
                                </button>
                                <button type="button" id="BtnModificar" class="btn btn-warning d-none">
                                    <i class="bi bi-pencil me-1"></i>Modificar Cliente
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

        <!-- SECCIÓN PARA BUSCAR CLIENTES -->
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body">
                        <h3 class="text-center mb-4">
                            <i class="bi bi-list-ul me-2"></i>Clientes Registrados
                        </h3>
                        
                        <div class="d-flex justify-content-center mb-3">
                            <button type="button" id="BtnBuscarClientes" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Buscar Clientes
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Teléfono</th>
                                        <th>NIT</th>
                                        <th>Correo</th>
                                        <th>Fecha Registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="TablaClientes">
                                    <!-- Los clientes se cargarán aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/clientes/index.js') ?>"></script>
</body>
</html>