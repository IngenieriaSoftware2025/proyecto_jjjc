<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Venta;
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
            if (empty($_POST['productos']) || !is_array($_POST['productos'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe agregar al menos un producto'
                ]);
                exit;
            }
            
            // Calcular total
            $total = 0;
            foreach ($_POST['productos'] as $producto) {
                $total += floatval($producto['subtotal']);
            }
            
            $_POST['venta_total'] = $total;
            $_POST['venta_fecha'] = '';
            
            // Crear la venta
            $venta = new Venta($_POST);
            $resultadoVenta = $venta->crear();
            
            if ($resultadoVenta['resultado'] == 1) {
                $ventaId = $resultadoVenta['id'];
                
                // Crear detalles de venta y actualizar inventario
                foreach ($_POST['productos'] as $producto) {
                    // Crear detalle
                    $detalle = new DetalleVenta([
                        'detalle_venta_id' => $ventaId,
                        'detalle_inventario_id' => $producto['inventario_id'],
                        'detalle_cantidad' => $producto['cantidad'],
                        'detalle_precio_unitario' => $producto['precio'],
                        'detalle_subtotal' => $producto['subtotal']
                    ]);
                    $detalle->crear();
                    
                    // Actualizar stock del inventario
                    $sql = "UPDATE invent_cel SET 
                            invent_cantidad_disponible = invent_cantidad_disponible - {$producto['cantidad']}
                            WHERE invent_id = {$producto['inventario_id']}";
                    InventarioCelular::getDB()->exec($sql);
                }
                
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Venta registrada correctamente',
                    'venta_id' => $ventaId
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar la venta'
                ]);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            // Consulta con JOIN para traer datos del cliente
            $sql = "SELECT v.venta_id, v.venta_cliente_id, v.venta_total, v.venta_fecha, 
                           v.venta_estado, v.venta_tipo,
                           c.cli_nombres, c.cli_apellidos 
                    FROM rep_ventas v 
                    INNER JOIN clientes c ON v.venta_cliente_id = c.cli_id 
                    WHERE v.venta_estado = 'COMPLETADA' 
                    ORDER BY v.venta_id DESC";
            
            $ventas = Venta::fetchArray($sql);
            
            // Formatear fechas
            if (!empty($ventas)) {
                foreach ($ventas as &$venta) {
                    if (!empty($venta['venta_fecha'])) {
                        $venta['venta_fecha'] = date('d/m/Y H:i', strtotime($venta['venta_fecha']));
                    }
                }
            }
            
            if (!empty($ventas)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Ventas encontradas: ' . count($ventas),
                    'data' => $ventas
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron ventas',
                    'data' => []
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar ventas: ' . $e->getMessage()
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
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener clientes: ' . $e->getMessage()
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
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener productos: ' . $e->getMessage()
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
            $sql = "SELECT dv.detalle_cantidad, dv.detalle_precio_unitario, dv.detalle_subtotal,
                           i.invent_modelo, m.marca_nombre
                    FROM detalle_ventas dv
                    INNER JOIN invent_cel i ON dv.detalle_inventario_id = i.invent_id
                    INNER JOIN marc_cel m ON i.invent_marca_id = m.marca_id
                    WHERE dv.detalle_venta_id = $ventaId";
            
            $detalles = DetalleVenta::fetchArray($sql);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Detalle encontrado',
                'data' => $detalles
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}