<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Cliente;
use Model\Empleado;
use Model\TipoServicio;

class ReparacionController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('reparaciones/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
        
        try {
            // Validar cliente
            if (empty($_POST['orden_cli_id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un cliente'
                ]);
                exit;
            }
            
            // Validar empleado
            if (empty($_POST['orden_empleado_id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un empleado'
                ]);
                exit;
            }
            
            // Validar servicio
            if (empty($_POST['orden_serv_id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un servicio'
                ]);
                exit;
            }
            
            // Obtener precio del servicio
            $consultaPrecio = "SELECT serv_precio FROM tipo_servicio WHERE serv_id = {$_POST['orden_serv_id']}";
            $datosServicio = TipoServicio::fetchFirst($consultaPrecio);
            
            if (!$datosServicio) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Servicio no encontrado'
                ]);
                exit;
            }
            
            $precioServicio = $datosServicio['serv_precio'];
            
            // Sanitizar datos
            $_POST['orden_modelo_celular'] = ucwords(strtolower(trim(htmlspecialchars($_POST['orden_modelo_celular']))));
            $_POST['orden_marca_celular'] = ucwords(strtolower(trim(htmlspecialchars($_POST['orden_marca_celular']))));
            $_POST['orden_motivo_ingreso'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['orden_motivo_ingreso']))));
            $_POST['orden_diagnostico'] = !empty($_POST['orden_diagnostico']) ? ucfirst(strtolower(trim(htmlspecialchars($_POST['orden_diagnostico'])))) : '';
            $_POST['orden_observaciones'] = !empty($_POST['orden_observaciones']) ? ucfirst(strtolower(trim(htmlspecialchars($_POST['orden_observaciones'])))) : '';
            
            $consultaCrearOrden = "INSERT INTO ordenes_reparacion 
                (orden_cli_id, orden_empleado_id, orden_serv_id, orden_modelo_celular, orden_marca_celular, 
                 orden_motivo_ingreso, orden_diagnostico, orden_precio_servicio, orden_estado, orden_observaciones, orden_situacion)
                VALUES 
                ({$_POST['orden_cli_id']}, {$_POST['orden_empleado_id']}, {$_POST['orden_serv_id']}, 
                 '{$_POST['orden_modelo_celular']}', '{$_POST['orden_marca_celular']}', 
                 '{$_POST['orden_motivo_ingreso']}', '{$_POST['orden_diagnostico']}', 
                 {$precioServicio}, 'RECIBIDO', '{$_POST['orden_observaciones']}', 1)";
            
            $resultadoOrden = ActiveRecord::getDB()->exec($consultaCrearOrden);
            
            if ($resultadoOrden) {
                $consultaCrearVenta = "INSERT INTO rep_ventas 
                    (venta_cliente_id, venta_total, venta_estado, venta_tipo)
                    VALUES 
                    ({$_POST['orden_cli_id']}, {$precioServicio}, 'COMPLETADA', 'REPARACION')";
                
                $resultadoVenta = ActiveRecord::getDB()->exec($consultaCrearVenta);
                
                if ($resultadoVenta) {
                    http_response_code(200);
                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Orden de reparación creada correctamente'
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Error al crear la venta de reparación'
                    ]);
                }
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al crear la orden de reparación'
                ]);
            }
            
        } catch (Exception $errorGeneral) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al procesar la reparación: ' . $errorGeneral->getMessage()
            ]);
        }
        exit;
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            $consultaOrdenes = "SELECT o.orden_id, o.orden_cli_id, o.orden_empleado_id, o.orden_serv_id,
                           o.orden_modelo_celular, o.orden_marca_celular, o.orden_motivo_ingreso,
                           o.orden_diagnostico, o.orden_precio_servicio, o.orden_fecha_ingreso,
                           o.orden_estado, o.orden_observaciones, o.orden_situacion,
                           c.cli_nombres, c.cli_apellidos,
                           e.empleado_nombres, e.empleado_apellidos,
                           ts.serv_nombre
                    FROM ordenes_reparacion o
                    INNER JOIN clientes c ON o.orden_cli_id = c.cli_id
                    INNER JOIN empleados e ON o.orden_empleado_id = e.empleado_id
                    INNER JOIN tipo_servicio ts ON o.orden_serv_id = ts.serv_id
                    WHERE o.orden_situacion = 1
                    ORDER BY o.orden_id DESC";
            
            $ordenesEncontradas = ActiveRecord::fetchArray($consultaOrdenes);
            
            // Formatear datos para la vista
            if (!empty($ordenesEncontradas)) {
                foreach ($ordenesEncontradas as &$orden) {
                    $orden['cliente_completo'] = $orden['cli_nombres'] . ' ' . $orden['cli_apellidos'];
                    $orden['empleado_completo'] = $orden['empleado_nombres'] . ' ' . $orden['empleado_apellidos'];
                    $orden['celular_completo'] = $orden['orden_marca_celular'] . ' ' . $orden['orden_modelo_celular'];
                    $orden['precio_formateado'] = $orden['orden_precio_servicio'];
                    
                    if (!empty($orden['orden_fecha_ingreso'])) {
                        $orden['fecha_formateada'] = date('d/m/Y H:i', strtotime($orden['orden_fecha_ingreso']));
                    } else {
                        $orden['fecha_formateada'] = date('d/m/Y H:i');
                    }
                }
            }
            
            if (!empty($ordenesEncontradas)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Órdenes encontradas: ' . count($ordenesEncontradas),
                    'data' => $ordenesEncontradas
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron órdenes de reparación',
                    'data' => []
                ]);
            }
            
        } catch (Exception $errorBusqueda) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar órdenes: ' . $errorBusqueda->getMessage()
            ]);
        }
        exit;
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        
        if (empty($_POST['orden_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de la orden es requerido'
            ]);
            exit;
        }
        
        try {
            // Sanitizar datos
            $_POST['orden_modelo_celular'] = ucwords(strtolower(trim(htmlspecialchars($_POST['orden_modelo_celular']))));
            $_POST['orden_marca_celular'] = ucwords(strtolower(trim(htmlspecialchars($_POST['orden_marca_celular']))));
            $_POST['orden_motivo_ingreso'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['orden_motivo_ingreso']))));
            $_POST['orden_diagnostico'] = !empty($_POST['orden_diagnostico']) ? ucfirst(strtolower(trim(htmlspecialchars($_POST['orden_diagnostico'])))) : '';
            $_POST['orden_observaciones'] = !empty($_POST['orden_observaciones']) ? ucfirst(strtolower(trim(htmlspecialchars($_POST['orden_observaciones'])))) : '';
            
            $consultaPrecio = "SELECT serv_precio FROM tipo_servicio WHERE serv_id = {$_POST['orden_serv_id']}";
            $datosServicio = TipoServicio::fetchFirst($consultaPrecio);
            $precioServicio = $datosServicio ? $datosServicio['serv_precio'] : 0;
            
            // Actualizar orden
            $consultaActualizar = "UPDATE ordenes_reparacion SET 
                orden_cli_id = {$_POST['orden_cli_id']},
                orden_empleado_id = {$_POST['orden_empleado_id']},
                orden_serv_id = {$_POST['orden_serv_id']},
                orden_modelo_celular = '{$_POST['orden_modelo_celular']}',
                orden_marca_celular = '{$_POST['orden_marca_celular']}',
                orden_motivo_ingreso = '{$_POST['orden_motivo_ingreso']}',
                orden_diagnostico = '{$_POST['orden_diagnostico']}',
                orden_precio_servicio = {$precioServicio},
                orden_estado = '{$_POST['orden_estado']}',
                orden_observaciones = '{$_POST['orden_observaciones']}'
                WHERE orden_id = {$_POST['orden_id']} AND orden_situacion = 1";
            
            $resultado = ActiveRecord::getDB()->exec($consultaActualizar);
            
            if ($resultado >= 0) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Orden modificada correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar la orden'
                ]);
            }
            
        } catch (Exception $errorModificar) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $errorModificar->getMessage()
            ]);
        }
        exit;
    }

    public static function eliminarAPI()
    {
        getHeadersApi();
        
        $idOrden = $_GET['id'] ?? null;
        
        if (!$idOrden) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de la orden es requerido'
            ]);
            exit;
        }
        
        try {
            // Verificar que la orden exista y esté activa antes de eliminar
            $consultaVerificar = "SELECT orden_id, orden_estado, orden_situacion 
                                FROM ordenes_reparacion 
                                WHERE orden_id = $idOrden AND orden_situacion = 1";
            $ordenExistente = ActiveRecord::fetchFirst($consultaVerificar);
            
            if (!$ordenExistente) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La orden de reparación no fue encontrada o ya está eliminada'
                ]);
                exit;
            }
            
            // Verificar si la orden ya está entregada (no se puede eliminar)
            if ($ordenExistente['orden_estado'] === 'ENTREGADO') {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se puede eliminar una orden que ya ha sido entregada'
                ]);
                exit;
            }
            
            // Eliminación lógica: cambiar situación a 0 
            $consultaEliminarLogico = "UPDATE ordenes_reparacion 
                                     SET orden_situacion = 0 
                                     WHERE orden_id = $idOrden AND orden_situacion = 1";
            $resultadoEliminacion = ActiveRecord::getDB()->exec($consultaEliminarLogico);
            
            if ($resultadoEliminacion > 0) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Orden de reparación eliminada correctamente (situación cambiada a inactiva)'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo eliminar la orden (puede que ya esté eliminada)'
                ]);
            }
            
        } catch (Exception $errorEliminacion) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la orden: ' . $errorEliminacion->getMessage()
            ]);
        }
        exit;
    }

    public static function obtenerClientesAPI()
    {
        getHeadersApi();
        
        try {
            $clientes = Cliente::where('cli_estado', 1);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Clientes encontrados',
                'data' => $clientes
            ]);
        } catch (Exception $errorClientes) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener clientes: ' . $errorClientes->getMessage()
            ]);
        }
        exit;
    }

    public static function obtenerEmpleadosAPI()
    {
        getHeadersApi();
        
        try {
            $empleados = Empleado::where('empleado_estado', 1);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Empleados encontrados',
                'data' => $empleados
            ]);
        } catch (Exception $errorEmpleados) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener empleados: ' . $errorEmpleados->getMessage()
            ]);
        }
        exit;
    }

    public static function obtenerServiciosAPI()
    {
        getHeadersApi();
        
        try {
            $servicios = TipoServicio::where('serv_estado', 1);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Servicios encontrados',
                'data' => $servicios
            ]);
        } catch (Exception $errorServicios) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener servicios: ' . $errorServicios->getMessage()
            ]);
        }
        exit;
    }

    public static function obtenerEstadosAPI()
    {
        getHeadersApi();
        
        try {
            $estados = [
                ['id' => 'RECIBIDO', 'nombre' => 'Recibido'],
                ['id' => 'EN_PROCESO', 'nombre' => 'En Proceso'],
                ['id' => 'TERMINADO', 'nombre' => 'Terminado'],
                ['id' => 'ENTREGADO', 'nombre' => 'Entregado']
            ];
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estados disponibles',
                'data' => $estados
            ]);
        } catch (Exception $errorEstados) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener estados: ' . $errorEstados->getMessage()
            ]);
        }
        exit;
    }
}