<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\TipoServicio;

class TipoServicioController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('servicios/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
        
        // Sanitizar nombre del servicio
        $_POST['serv_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['serv_nombre']))));
        
        $cantidad_nombre = strlen($_POST['serv_nombre']);
        
        if ($cantidad_nombre < 3) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del servicio debe tener más de 2 caracteres'
            ]);
            exit;
        }
        
        // Validar precio
        $_POST['serv_precio'] = filter_var($_POST['serv_precio'], FILTER_VALIDATE_FLOAT);
        if ($_POST['serv_precio'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio debe ser mayor a 0'
            ]);
            exit;
        }
        
        // Sanitizar descripción
        $_POST['serv_descripcion'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['serv_descripcion']))));
        
        if (strlen($_POST['serv_descripcion']) < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La descripción debe tener más de 4 caracteres'
            ]);
            exit;
        }
        
        // Verificar si el servicio ya existe
        $servicioExistente = TipoServicio::where('serv_nombre', $_POST['serv_nombre']);
        if (!empty($servicioExistente)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un servicio con ese nombre'
            ]);
            exit;
        }
        
        $servicio = new TipoServicio($_POST);
        $resultado = $servicio->crear();

        if($resultado['resultado'] == 1){
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Servicio registrado correctamente',
            ]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar el servicio',
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            $servicios = TipoServicio::where('serv_estado', 1);
            
            if (!empty($servicios)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Servicios encontrados: ' . count($servicios),
                    'data' => $servicios
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron servicios',
                    'data' => []
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar servicios: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        
        if (empty($_POST['serv_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID del servicio es requerido'
            ]);
            exit;
        }
        
        // Sanitizar datos igual que en guardar
        $_POST['serv_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['serv_nombre']))));
        $_POST['serv_precio'] = filter_var($_POST['serv_precio'], FILTER_VALIDATE_FLOAT);
        $_POST['serv_descripcion'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['serv_descripcion']))));
        
        // Verificar si el nombre ya existe (excluyendo el actual)
        $servicioExistente = TipoServicio::fetchFirst("SELECT * FROM tipo_servicio WHERE serv_nombre = '{$_POST['serv_nombre']}' AND serv_id != {$_POST['serv_id']}");
        if ($servicioExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro servicio con ese nombre'
            ]);
            exit;
        }
        
        try {
            // Usar consulta SQL directa para actualizar
            $sql = "UPDATE tipo_servicio SET 
                    serv_nombre = '{$_POST['serv_nombre']}',
                    serv_precio = {$_POST['serv_precio']},
                    serv_descripcion = '{$_POST['serv_descripcion']}'
                    WHERE serv_id = {$_POST['serv_id']}";
            
            $resultado = TipoServicio::getDB()->exec($sql);

            if($resultado >= 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Servicio modificado correctamente',
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar el servicio',
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
                'mensaje' => 'ID del servicio es requerido'
            ]);
            exit;
        }
        
        try {
            // Cambiar estado a 0 -- NO eliminar físicamente
            $sql = "UPDATE tipo_servicio SET serv_estado = 0 WHERE serv_id = $id AND serv_estado = 1";
            $resultado = TipoServicio::getDB()->exec($sql);
            
            if($resultado > 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Servicio eliminado correctamente (estado cambiado a inactivo)',
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo eliminar el servicio (puede que ya esté eliminado)',
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
}