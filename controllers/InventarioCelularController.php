<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\InventarioCelular;
use Model\MarcaCelular;

class InventarioCelularController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('inventario/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
        
        // Validar marca
        if (empty($_POST['invent_marca_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca'
            ]);
            exit;
        }
        
        // Sanitizar modelo
        $_POST['invent_modelo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['invent_modelo']))));
        
        $cantidad_modelo = strlen($_POST['invent_modelo']);
        
        if ($cantidad_modelo < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El modelo debe tener más de 1 caracteres'
            ]);
            exit;
        }
        
        // Validar precios
        $_POST['invent_precio_compra'] = filter_var($_POST['invent_precio_compra'], FILTER_VALIDATE_FLOAT);
        if ($_POST['invent_precio_compra'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de compra debe ser mayor a 0'
            ]);
            exit;
        }
        
        $_POST['invent_precio_venta'] = filter_var($_POST['invent_precio_venta'], FILTER_VALIDATE_FLOAT);
        if ($_POST['invent_precio_venta'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de venta debe ser mayor a 0'
            ]);
            exit;
        }
        
        // Validar que precio de venta sea mayor que precio de compra
        if ($_POST['invent_precio_venta'] <= $_POST['invent_precio_compra']) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio de venta debe ser mayor al precio de compra'
            ]);
            exit;
        }
        
        // Validar cantidad
        $_POST['invent_cantidad_disponible'] = filter_var($_POST['invent_cantidad_disponible'], FILTER_VALIDATE_INT);
        if ($_POST['invent_cantidad_disponible'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad no puede ser negativa'
            ]);
            exit;
        }
        
        // Sanitizar descripción
        $_POST['invent_descripcion'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['invent_descripcion']))));
        
        if (strlen($_POST['invent_descripcion']) < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La descripción debe tener más de 4 caracteres'
            ]);
            exit;
        }
        
        // Verificar si el modelo ya existe para esa marca
        $productoExistente = InventarioCelular::fetchFirst("SELECT * FROM invent_cel WHERE invent_modelo = '{$_POST['invent_modelo']}' AND invent_marca_id = {$_POST['invent_marca_id']}");
        if ($productoExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un producto con ese modelo para esta marca'
            ]);
            exit;
        }
        
        $_POST['invent_fecha_ingreso'] = '';
        
        $inventario = new InventarioCelular($_POST);
        $resultado = $inventario->crear();

        if($resultado['resultado'] == 1){
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Producto agregado al inventario correctamente',
            ]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al agregar el producto al inventario',
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            // Consulta con JOIN para traer el nombre de la marca Y formatear fecha
            $sql = "SELECT i.invent_id, i.invent_modelo, i.invent_marca_id, i.invent_precio_compra,
                           i.invent_precio_venta, i.invent_cantidad_disponible, i.invent_descripcion,
                           i.invent_estado, i.invent_fecha_ingreso, m.marca_nombre 
                    FROM invent_cel i 
                    INNER JOIN marc_cel m ON i.invent_marca_id = m.marca_id 
                    WHERE i.invent_estado = 1 
                    ORDER BY i.invent_id DESC";
            
            $inventario = InventarioCelular::fetchArray($sql);
            
            // Formatear fechas
            if (!empty($inventario)) {
                foreach ($inventario as &$producto) {
                    if (!empty($producto['invent_fecha_ingreso'])) {
                        $producto['invent_fecha_ingreso'] = date('d/m/Y', strtotime($producto['invent_fecha_ingreso']));
                    }
                }
            }
            
            if (!empty($inventario)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Productos encontrados: ' . count($inventario),
                    'data' => $inventario
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron productos en el inventario',
                    'data' => []
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar productos: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        
        if (empty($_POST['invent_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID del producto es requerido'
            ]);
            exit;
        }
        
        // Validaciones similares al guardar
        if (empty($_POST['invent_marca_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca'
            ]);
            exit;
        }
        
        // Sanitizar datos
        $_POST['invent_modelo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['invent_modelo']))));
        $_POST['invent_precio_compra'] = filter_var($_POST['invent_precio_compra'], FILTER_VALIDATE_FLOAT);
        $_POST['invent_precio_venta'] = filter_var($_POST['invent_precio_venta'], FILTER_VALIDATE_FLOAT);
        $_POST['invent_cantidad_disponible'] = filter_var($_POST['invent_cantidad_disponible'], FILTER_VALIDATE_INT);
        $_POST['invent_descripcion'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['invent_descripcion']))));
        
        // Verificar si el modelo ya existe para esa marca 
        $productoExistente = InventarioCelular::fetchFirst("SELECT * FROM invent_cel WHERE invent_modelo = '{$_POST['invent_modelo']}' AND invent_marca_id = {$_POST['invent_marca_id']} AND invent_id != {$_POST['invent_id']}");
        if ($productoExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro producto con ese modelo para esta marca'
            ]);
            exit;
        }
        
        try {
            // Usar consulta SQL directa para actualizar
            $sql = "UPDATE invent_cel SET 
                    invent_modelo = '{$_POST['invent_modelo']}',
                    invent_marca_id = {$_POST['invent_marca_id']},
                    invent_precio_compra = {$_POST['invent_precio_compra']},
                    invent_precio_venta = {$_POST['invent_precio_venta']},
                    invent_cantidad_disponible = {$_POST['invent_cantidad_disponible']},
                    invent_descripcion = '{$_POST['invent_descripcion']}'
                    WHERE invent_id = {$_POST['invent_id']}";
            
            $resultado = InventarioCelular::getDB()->exec($sql);

            if($resultado >= 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Producto modificado correctamente',
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar el producto',
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

    public static function eliminarAPI()
    {
        getHeadersApi();
        
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID del producto es requerido'
            ]);
            exit;
        }
        
        try {
            // Cambiar estado a 0  NO eliminar físicamente
            $sql = "UPDATE invent_cel SET invent_estado = 0 WHERE invent_id = $id AND invent_estado = 1";
            $resultado = InventarioCelular::getDB()->exec($sql);
            
            if($resultado > 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Producto eliminado del inventario correctamente',
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo eliminar el producto (puede que ya esté eliminado)',
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

    public static function obtenerMarcasAPI()
    {
        getHeadersApi();
        
        try {
            $marcas = MarcaCelular::where('marca_estado', 1);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marcas encontradas',
                'data' => $marcas
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener marcas: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}