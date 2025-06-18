<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\DetalleVenta;
use Model\Cliente;
use Model\InventarioCelular;

class VentaController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('ventas/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
        
        try {
            // Validar cliente
            if (empty($_POST['venta_cliente_id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un cliente'
                ]);
                exit;
            }
            
            // Validar que tenga productos 
            $productos = [];
            if (isset($_POST['productos'])) {
                if (is_string($_POST['productos'])) {
                    $productos = json_decode($_POST['productos'], true);
                } else if (is_array($_POST['productos'])) {
                    $productos = $_POST['productos'];
                }
            }
            
            if (empty($productos)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe agregar al menos un producto'
                ]);
                exit;
            }
            
            $ventasGuardadas = 0;
            $erroresEncontrados = [];
            
            // Guardar cada producto como una venta individual
            foreach ($productos as $indiceProducto => $datosProducto) {
                try {
                    // Verificar stock
                    $consultaStock = "SELECT invent_cantidad_disponible FROM invent_cel WHERE invent_id = {$datosProducto['inventario_id']}";
                    $stockDisponible = InventarioCelular::fetchFirst($consultaStock);
                    
                    if (!$stockDisponible || $stockDisponible['invent_cantidad_disponible'] < $datosProducto['cantidad']) {
                        $erroresEncontrados[] = "Producto " . ($indiceProducto + 1) . ": Stock insuficiente";
                        continue;
                    }
                    
                    // Crear la venta individual
                    $nuevaVenta = new DetalleVenta([
                        'detalle_venta_id' => $_POST['venta_cliente_id'],
                        'detalle_inventario_id' => $datosProducto['inventario_id'],
                        'detalle_cantidad' => $datosProducto['cantidad'],
                        'detalle_precio_unitario' => $datosProducto['precio'],
                        'detalle_subtotal' => $datosProducto['subtotal']
                    ]);
                    
                    $resultadoGuardar = $nuevaVenta->crear();
                    
                    if ($resultadoGuardar['resultado'] == 1) {
                        // Actualizar stock
                        $consultaActualizarStock = "UPDATE invent_cel SET 
                                invent_cantidad_disponible = invent_cantidad_disponible - {$datosProducto['cantidad']}
                                WHERE invent_id = {$datosProducto['inventario_id']}";
                        InventarioCelular::getDB()->exec($consultaActualizarStock);
                        
                        $ventasGuardadas++;
                    } else {
                        $erroresEncontrados[] = "Error al guardar producto " . ($indiceProducto + 1);
                    }
                    
                } catch (Exception $errorProducto) {
                    $erroresEncontrados[] = "Error en producto " . ($indiceProducto + 1) . ": " . $errorProducto->getMessage();
                }
            }
            
            // Respuesta
            if ($ventasGuardadas > 0) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => "Se guardaron $ventasGuardadas ventas correctamente",
                    'ventas_guardadas' => $ventasGuardadas,
                    'errores' => $erroresEncontrados
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo guardar ninguna venta',
                    'errores' => $erroresEncontrados
                ]);
            }
            
        } catch (Exception $errorGeneral) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al procesar las ventas: ' . $errorGeneral->getMessage(),
                'detalle_error' => 'Problema en el servidor al guardar las ventas'
            ]);
        }
        exit;
    }

    public static function detalleVentaAPI()
    {
        getHeadersApi();
        
        $ventaId = $_GET['id'] ?? null;
        
        if (!$ventaId) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de la venta es requerido'
            ]);
            exit;
        }
        
        try {
            // Obtener detalle de la venta
            $consultaDetalle = "SELECT dv.detalle_cantidad, dv.detalle_precio_unitario, dv.detalle_subtotal,
                           i.invent_modelo, m.marca_nombre
                    FROM detalle_ventas dv
                    INNER JOIN invent_cel i ON dv.detalle_inventario_id = i.invent_id
                    INNER JOIN marc_cel m ON i.invent_marca_id = m.marca_id
                    WHERE dv.detalle_id = $ventaId";
            
            $detalles = DetalleVenta::fetchArray($consultaDetalle);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Detalle encontrado',
                'data' => $detalles
            ]);
        } catch (Exception $errorDetalle) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener detalle: ' . $errorDetalle->getMessage()
            ]);
        }
        exit;
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            // Consulta simple para obtener las ventas con datos básicos
            $consultaVentas = "SELECT dv.detalle_id, dv.detalle_venta_id, dv.detalle_inventario_id,
                           dv.detalle_cantidad, dv.detalle_precio_unitario, dv.detalle_subtotal
                    FROM detalle_ventas dv 
                    ORDER BY dv.detalle_id DESC";
            
            $ventasEncontradas = DetalleVenta::fetchArray($consultaVentas);
            
            // Obtener información adicional para cada venta
            if (!empty($ventasEncontradas)) {
                foreach ($ventasEncontradas as &$venta) {
                    // Obtener información del cliente
                    try {
                        $consultaCliente = "SELECT cli_nombres, cli_apellidos FROM clientes WHERE cli_id = {$venta['detalle_venta_id']}";
                        $datosCliente = Cliente::fetchFirst($consultaCliente);
                        if ($datosCliente) {
                            $venta['cli_nombres'] = $datosCliente['cli_nombres'];
                            $venta['cli_apellidos'] = $datosCliente['cli_apellidos'];
                        } else {
                            $venta['cli_nombres'] = 'Cliente';
                            $venta['cli_apellidos'] = 'Desconocido';
                        }
                    } catch (Exception $e) {
                        $venta['cli_nombres'] = 'Cliente';
                        $venta['cli_apellidos'] = 'Desconocido';
                    }
                    
                    // Obtener información del producto
                    try {
                        $consultaProducto = "SELECT i.invent_modelo, m.marca_nombre 
                                FROM invent_cel i 
                                INNER JOIN marc_cel m ON i.invent_marca_id = m.marca_id 
                                WHERE i.invent_id = {$venta['detalle_inventario_id']}";
                        $datosProducto = InventarioCelular::fetchFirst($consultaProducto);
                        if ($datosProducto) {
                            $venta['producto_nombre'] = $datosProducto['marca_nombre'] . ' ' . $datosProducto['invent_modelo'];
                        } else {
                            $venta['producto_nombre'] = 'Producto Desconocido';
                        }
                    } catch (Exception $e) {
                        $venta['producto_nombre'] = 'Producto Desconocido';
                    }
                    
                    $venta['venta_total'] = $venta['detalle_subtotal']; 
                    $venta['venta_fecha'] = date('d/m/Y H:i');
                    $venta['venta_estado'] = 'COMPLETADA';
                    $venta['venta_tipo'] = 'VENTA';
                    $venta['venta_id'] = $venta['detalle_id']; 
                }
            }
            
            if (!empty($ventasEncontradas)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Ventas encontradas: ' . count($ventasEncontradas),
                    'data' => $ventasEncontradas
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron ventas',
                    'data' => []
                ]);
            }
            
        } catch (Exception $errorBusqueda) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar ventas: ' . $errorBusqueda->getMessage(),
                'detalle_error' => 'No se pudieron cargar las ventas de la base de datos'
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

    public static function obtenerProductosAPI()
    {
        getHeadersApi();
        
        try {
            // Consulta para productos con stock disponible
            $sql = "SELECT i.invent_id, i.invent_modelo, i.invent_precio_venta, 
                           i.invent_cantidad_disponible, i.invent_descripcion,
                           m.marca_nombre 
                    FROM invent_cel i 
                    INNER JOIN marc_cel m ON i.invent_marca_id = m.marca_id 
                    WHERE i.invent_estado = 1 AND i.invent_cantidad_disponible > 0
                    ORDER BY i.invent_modelo";
            
            $productos = InventarioCelular::fetchArray($sql);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos encontrados',
                'data' => $productos
            ]);
        } catch (Exception $errorProductos) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener productos: ' . $errorProductos->getMessage()
            ]);
        }
        exit;
    }
}