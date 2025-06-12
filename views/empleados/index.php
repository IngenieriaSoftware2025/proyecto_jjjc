<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
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
        <!-- FORMULARIO PARA EMPLEADOS -->
        <div class="row justify-content-center mb-5">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">
                            <i class="bi bi-person-badge me-2"></i>Gestión de Empleados
                        </h2>
                        
                        <form id="FormEmpleados" method="POST">
                            <input type="hidden" id="empleado_id" name="empleado_id">
                            
                            <!-- Fila 1: Nombres y Apellidos -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="empleado_nombres" name="empleado_nombres" class="form-control" maxlength="255" required />
                                        <label class="form-label" for="empleado_nombres">Nombres</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="empleado_apellidos" name="empleado_apellidos" class="form-control" maxlength="255" required />
                                        <label class="form-label" for="empleado_apellidos">Apellidos</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Fila 2: Teléfono y Especialidad -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <input type="text" id="empleado_telefono" name="empleado_telefono" class="form-control" 
                                               maxlength="8" pattern="[0-9]{8}" title="Debe contener exactamente 8 dígitos" required />
                                        <label class="form-label" for="empleado_telefono">Teléfono</label>
                                        <div class="form-text">8 dígitos (ejemplo: 12345678)</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <select id="empleado_especialidad" name="empleado_especialidad" class="form-select" required>
                                            <option value="">Seleccione especialidad</option>
                                            <option value="Reparacion de Pantallas">Reparación de Pantallas</option>
                                            <option value="Software y Formateo">Software y Formateo</option>
                                            <option value="Cambio de Baterias">Cambio de Baterías</option>
                                            <option value="Reparacion de Placas">Reparación de Placas</option>
                                            <option value="Liberacion de Equipos">Liberación de Equipos</option>
                                            <option value="Reparacion General">Reparación General</option>
                                            <option value="Tecnico Senior">Técnico Senior</option>
                                        </select>
                                        <label class="form-label" for="empleado_especialidad">Especialidad</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center gap-2">
                                <button type="submit" id="BtnGuardar" class="btn btn-success gradient-custom-4">
                                    <i class="bi bi-save me-1"></i>Guardar Empleado
                                </button>
                                <button type="button" id="BtnModificar" class="btn btn-warning d-none">
                                    <i class="bi bi-pencil me-1"></i>Modificar Empleado
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

        <!-- SECCIÓN PARA BUSCAR EMPLEADOS -->
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body">
                        <h3 class="text-center mb-4">
                            <i class="bi bi-people me-2"></i>Empleados Registrados
                        </h3>
                        
                        <div class="d-flex justify-content-center mb-3">
                            <button type="button" id="BtnBuscarEmpleados" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Buscar Empleados
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
                                        <th>Especialidad</th>
                                        <th>Fecha Ingreso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="TablaEmpleados">
                                    <!-- Los empleados se cargarán aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/empleados/index.js') ?>"></script>
</body>
</html>