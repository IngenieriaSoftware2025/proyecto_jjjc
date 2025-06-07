<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Usuario</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
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
        
        .bg-image {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .form-outline {
            margin-bottom: 1rem;
        }
        
        .card-body {
            padding: 2rem !important;
        }
        
        h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem !important;
        }

        .mensaje-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .mensaje-exito {
            color: #198754;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-control:focus {
            border-color: #84fab0;
            box-shadow: 0 0 0 0.2rem rgba(132, 250, 176, 0.25);
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <section class="min-vh-100 bg-image py-5" style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
        <div class="mask d-flex align-items-center gradient-custom-3">
            <div class="container">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Crear Cuenta de Usuario</h2>
                                
                                <div id="mensaje_resultado"></div>
                                
                                <form id="formUsuario" method="POST" enctype="multipart/form-data">
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-outline">
                                                <input type="text" id="usuario_nom1" name="usuario_nom1" class="form-control" maxlength="50" required />
                                                <label class="form-label" for="usuario_nom1">Primer Nombre</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline">
                                                <input type="text" id="usuario_nom2" name="usuario_nom2" class="form-control" maxlength="50" />
                                                <label class="form-label" for="usuario_nom2">Segundo Nombre (Opcional)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-outline">
                                                <input type="text" id="usuario_ape1" name="usuario_ape1" class="form-control" maxlength="50" required />
                                                <label class="form-label" for="usuario_ape1">Primer Apellido</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline">
                                                <input type="text" id="usuario_ape2" name="usuario_ape2" class="form-control" maxlength="50" />
                                                <label class="form-label" for="usuario_ape2">Segundo Apellido (Opcional)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-outline">
                                                <input type="text" id="usuario_dpi" name="usuario_dpi" class="form-control" maxlength="13" pattern="[0-9]{13}" title="Debe contener exactamente 13 dígitos" required />
                                                <label class="form-label" for="usuario_dpi">DPI (13 dígitos)</label>
                                                <div class="form-text">Solo números, sin espacios ni guiones</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline">
                                                <input type="text" id="usuario_tel" name="usuario_tel" class="form-control" maxlength="8" pattern="[0-9]{8}" title="Debe contener exactamente 8 dígitos" required />
                                                <label class="form-label" for="usuario_tel">Teléfono (8 dígitos)</label>
                                                <div class="form-text">Solo números, sin espacios ni guiones</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-outline mb-3">
                                        <textarea id="usuario_direc" name="usuario_direc" class="form-control" maxlength="150" rows="2" required></textarea>
                                        <label class="form-label" for="usuario_direc">Dirección</label>
                                    </div>

                                    <div class="form-outline mb-3">
                                        <input type="email" id="usuario_correo" name="usuario_correo" class="form-control" maxlength="100" required />
                                        <label class="form-label" for="usuario_correo">Correo Electrónico</label>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-outline">
                                                <input type="password" id="usuario_contra" name="usuario_contra" class="form-control" minlength="8" maxlength="50" required />
                                                <label class="form-label" for="usuario_contra">Contraseña</label>
                                                <div class="form-text">Mínimo 8 caracteres</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline">
                                                <input type="password" id="confirmar_contra" name="confirmar_contra" class="form-control" minlength="8" maxlength="50" required />
                                                <label class="form-label" for="confirmar_contra">Confirmar Contraseña</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-outline mb-3">
                                        <input type="file" id="usuario_fotografia" name="usuario_fotografia" class="form-control" accept="image/jpeg,image/jpg,image/png" />
                                        <label class="form-label" for="usuario_fotografia">Fotografía (Opcional)</label>
                                        <div class="form-text">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 2MB</div>
                                    </div>

                                    <div class="form-check d-flex justify-content-center mb-4">
                                        <input class="form-check-input me-2" type="checkbox" value="" id="terminos" required />
                                        <label class="form-check-label" for="terminos">
                                            Acepto los <a href="#!" class="text-body"><u>Términos y Condiciones</u></a>
                                        </label>
                                    </div>

                                    <div class="d-flex justify-content-center mb-3">
                                        <button type="submit" id="BtnGuardar" class="btn btn-success btn-lg gradient-custom-4 text-body">
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            <span class="btn-text">Registrar</span>
                                        </button>
                                    </div>

                                    <p class="text-center text-muted mb-0">
                                        ¿Ya tienes cuenta? 
                                        <a href="/proyecto_jjjc/login" class="fw-bold text-body"><u>Inicia sesión aquí</u></a>
                                    </p>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('build/js/registro/index.js') ?>"></script>
</body>
</html>