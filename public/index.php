<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\LoginController;
use Controllers\RegistroController;
use Controllers\PermisosController;
use Controllers\AplicacionController;
use Controllers\AsigPermisosController;
use Controllers\ClienteController;
use Controllers\EmpleadoController;
use Controllers\EstadisticasController;
use Controllers\InventarioCelularController;
use Controllers\MarcaCelularController;
use Controllers\TipoServicioController;
use Controllers\VentaController;
use Controllers\ReparacionController; 

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

// RUTAS PRINCIPALES
$router->get('/', [AppController::class,'index']);
$router->get('/inicio', [AppController::class,'inicio']);

// RUTAS LOGIN
$router->get('/login', [LoginController::class,'renderizarPagina']);
$router->post('/login/iniciar', [LoginController::class,'login']);
$router->get('/logout', [LoginController::class,'logout']);

// RUTAS PARA REGISTRO
$router->get('/registro', [RegistroController::class,'renderizarPagina']);
$router->post('/registro/guardar', [RegistroController::class,'guardarAPI']);
$router->post('/registro/buscarUsuarios', [RegistroController::class,'buscarUsuariosAPI']);
$router->get('/registro/imagen', [RegistroController::class, 'mostrarImagen']);

// RUTAS PARA APLICACIONES
$router->get('/aplicaciones', [AplicacionController::class,'renderizarPagina']);
$router->post('/aplicaciones/guardar', [AplicacionController::class,'guardarAPI']);
$router->post('/aplicaciones/buscar', [AplicacionController::class,'buscarAPI']);
$router->post('/aplicaciones/modificar', [AplicacionController::class,'modificarAPI']);
$router->get('/aplicaciones/eliminar', [AplicacionController::class,'eliminarAPI']);

// RUTAS PARA PERMISOS
$router->get('/permisos', [PermisosController::class,'renderizarPagina']);
$router->post('/permisos/guardar', [PermisosController::class,'guardarAPI']);
$router->post('/permisos/buscar', [PermisosController::class,'buscarAPI']);
$router->post('/permisos/modificar', [PermisosController::class,'modificarAPI']);
$router->get('/permisos/eliminar', [PermisosController::class,'eliminarAPI']);
$router->post('/permisos/aplicaciones', [PermisosController::class,'obtenerAplicacionesAPI']);

// RUTAS PARA ASIGNACIÓN DE PERMISOS
$router->get('/asigPermisos', [AsigPermisosController::class,'renderizarPagina']);
$router->post('/asigPermisos/guardar', [AsigPermisosController::class,'guardarAPI']);
$router->post('/asigPermisos/buscar', [AsigPermisosController::class,'buscarAPI']);
$router->post('/asigPermisos/modificar', [AsigPermisosController::class,'modificarAPI']);
$router->get('/asigPermisos/eliminar', [AsigPermisosController::class,'eliminarAPI']);
$router->post('/asigPermisos/usuarios', [AsigPermisosController::class,'obtenerUsuariosAPI']);
$router->post('/asigPermisos/aplicaciones', [AsigPermisosController::class,'obtenerAplicacionesAPI']);
$router->get('/asigPermisos/permisos', [AsigPermisosController::class,'obtenerPermisosPorAppAPI']);

// RUTAS PARA MARCAS DE CELULARES
$router->get('/marcas', [MarcaCelularController::class,'renderizarPagina']);
$router->post('/marcas/guardar', [MarcaCelularController::class,'guardarAPI']);
$router->post('/marcas/buscar', [MarcaCelularController::class,'buscarAPI']);
$router->post('/marcas/modificar', [MarcaCelularController::class,'modificarAPI']);
$router->get('/marcas/eliminar', [MarcaCelularController::class,'eliminarAPI']);

// RUTAS PARA CLIENTES
$router->get('/clientes', [ClienteController::class,'renderizarPagina']);
$router->post('/clientes/guardar', [ClienteController::class,'guardarAPI']);
$router->post('/clientes/buscar', [ClienteController::class,'buscarAPI']);
$router->post('/clientes/modificar', [ClienteController::class,'modificarAPI']);
$router->get('/clientes/eliminar', [ClienteController::class,'eliminarAPI']);

// RUTAS PARA INVENTARIO
$router->get('/inventario', [InventarioCelularController::class,'renderizarPagina']);
$router->post('/inventario/guardar', [InventarioCelularController::class,'guardarAPI']);
$router->post('/inventario/buscar', [InventarioCelularController::class,'buscarAPI']);
$router->post('/inventario/modificar', [InventarioCelularController::class,'modificarAPI']);
$router->get('/inventario/eliminar', [InventarioCelularController::class,'eliminarAPI']);
$router->post('/inventario/marcas', [InventarioCelularController::class,'obtenerMarcasAPI']);

// RUTAS PARA EMPLEADOS
$router->get('/empleados', [EmpleadoController::class,'renderizarPagina']);
$router->post('/empleados/guardar', [EmpleadoController::class,'guardarAPI']);
$router->post('/empleados/buscar', [EmpleadoController::class,'buscarAPI']);
$router->post('/empleados/modificar', [EmpleadoController::class,'modificarAPI']);
$router->get('/empleados/eliminar', [EmpleadoController::class,'eliminarAPI']);

// RUTAS PARA SERVICIOS
$router->get('/servicios', [TipoServicioController::class,'renderizarPagina']);
$router->post('/servicios/guardar', [TipoServicioController::class,'guardarAPI']);
$router->post('/servicios/buscar', [TipoServicioController::class,'buscarAPI']);
$router->post('/servicios/modificar', [TipoServicioController::class,'modificarAPI']);
$router->get('/servicios/eliminar', [TipoServicioController::class,'eliminarAPI']);

// RUTAS PARA VENTAS
$router->get('/ventas', [VentaController::class,'renderizarPagina']);
$router->post('/ventas/guardar', [VentaController::class,'guardarAPI']);
$router->post('/ventas/buscar', [VentaController::class,'buscarAPI']);
$router->post('/ventas/clientes', [VentaController::class,'obtenerClientesAPI']);
$router->post('/ventas/productos', [VentaController::class,'obtenerProductosAPI']);
$router->get('/ventas/detalle', [VentaController::class,'detalleVentaAPI']);

// RUTAS PARA REPARACIONES
$router->get('/reparaciones', [ReparacionController::class,'renderizarPagina']);
$router->post('/reparaciones/guardar', [ReparacionController::class,'guardarAPI']);
$router->post('/reparaciones/buscar', [ReparacionController::class,'buscarAPI']);
$router->post('/reparaciones/modificar', [ReparacionController::class,'modificarAPI']);
$router->get('/reparaciones/eliminar', [ReparacionController::class,'eliminarAPI']);
$router->post('/reparaciones/clientes', [ReparacionController::class,'obtenerClientesAPI']);
$router->post('/reparaciones/empleados', [ReparacionController::class,'obtenerEmpleadosAPI']);
$router->post('/reparaciones/servicios', [ReparacionController::class,'obtenerServiciosAPI']);
$router->post('/reparaciones/estados', [ReparacionController::class,'obtenerEstadosAPI']);


// RUTAS PARA ESTADÍSTICAS
$router->get('/estadisticas', [EstadisticasController::class,'renderizarPagina']);
$router->get('/estadisticas/buscar', [EstadisticasController::class,'buscarAPI']);
$router->get('/estadisticas/resumen', [EstadisticasController::class,'resumenVentasAPI']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();