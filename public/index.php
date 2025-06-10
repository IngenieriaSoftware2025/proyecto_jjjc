<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\LoginController;
use Controllers\RegistroController;
use Controllers\PermisosController;
use Controllers\AplicacionController;
use Controllers\ClienteController;
use Controllers\MarcaCelularController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

//RUTAS LOGIN
$router->get('/login', [LoginController::class,'renderizarPagina']);

//RUTAS PARA REGISTRO
$router->get('/registro', [RegistroController::class,'renderizarPagina']);
$router->post('/registro/guardar', [RegistroController::class,'guardarAPI']);
$router->post('/registro/buscarUsuarios', [RegistroController::class,'buscarUsuariosAPI']);
$router->get('/registro/imagen', [RegistroController::class, 'mostrarImagen']);

//RUTAS PARA APLICACIONES
$router->get('/aplicaciones', [AplicacionController::class,'renderizarPagina']);
$router->post('/aplicaciones/guardar', [AplicacionController::class,'guardarAPI']);
$router->post('/aplicaciones/buscar', [AplicacionController::class,'buscarAPI']);
$router->post('/aplicaciones/modificar', [AplicacionController::class,'modificarAPI']);
$router->get('/aplicaciones/eliminar', [AplicacionController::class,'eliminarAPI']);

//RUTAS PARA PERMISOS
$router->get('/permisos', [PermisosController::class,'renderizarPagina']);
$router->post('/permisos/guardar', [PermisosController::class,'guardarAPI']);
$router->post('/permisos/buscar', [PermisosController::class,'buscarAPI']);
$router->post('/permisos/modificar', [PermisosController::class,'modificarAPI']);
$router->get('/permisos/eliminar', [PermisosController::class,'eliminarAPI']);
$router->post('/permisos/aplicaciones', [PermisosController::class,'obtenerAplicacionesAPI']);


//RUTAS PARA MARCAS DE CELULARES
$router->get('/marcas', [MarcaCelularController::class,'renderizarPagina']);
$router->post('/marcas/guardar', [MarcaCelularController::class,'guardarAPI']);
$router->post('/marcas/buscar', [MarcaCelularController::class,'buscarAPI']);
$router->post('/marcas/modificar', [MarcaCelularController::class,'modificarAPI']);
$router->get('/marcas/eliminar', [MarcaCelularController::class,'eliminarAPI']);

//RUTAS PARA CLIENTES
$router->get('/clientes', [ClienteController::class,'renderizarPagina']);
$router->post('/clientes/guardar', [ClienteController::class,'guardarAPI']);
$router->post('/clientes/buscar', [ClienteController::class,'buscarAPI']);
$router->post('/clientes/modificar', [ClienteController::class,'modificarAPI']);
$router->get('/clientes/eliminar', [ClienteController::class,'eliminarAPI']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();