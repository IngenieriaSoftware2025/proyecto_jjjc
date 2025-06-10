<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\MarcaCelular;

class MarcaCelularController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('marcas/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
        
        // Sanitizar nombre de la marca
        $_POST['marca_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['marca_nombre']))));
        
        $cantidad_nombre = strlen($_POST['marca_nombre']);
        
        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la marca debe tener más de 1 caracteres'
            ]);
            exit;
        }
        
        // Sanitizar descripción
        $_POST['marca_descripcion'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['marca_descripcion']))));
        
        if (strlen($_POST['marca_descripcion']) < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La descripción debe tener más de 4 caracteres'
            ]);
            exit;
        }
        
        // Verificar si la marca ya existe
        $marcaExistente = MarcaCelular::where('marca_nombre', $_POST['marca_nombre']);
        if (!empty($marcaExistente)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe una marca con ese nombre'
            ]);
            exit;
        }
        
        $_POST['marca_fecha_creacion'] = '';
        
        $marca = new MarcaCelular($_POST);
        $resultado = $marca->crear();

        if($resultado['resultado'] == 1){
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marca registrada correctamente',
            ]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar la marca',
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            $marcas = MarcaCelular::where('marca_estado', 1);
            
            if (!empty($marcas)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Marcas encontradas: ' . count($marcas),
                    'data' => $marcas
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron marcas',
                    'data' => []
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar marcas: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        
        if (empty($_POST['marca_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de la marca es requerido'
            ]);
            exit;
        }
        
        // Sanitizar datos igual que en guardar
        $_POST['marca_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['marca_nombre']))));
        $_POST['marca_descripcion'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['marca_descripcion']))));
        
        // Verificar si el nombre ya existe
        $marcaExistente = MarcaCelular::fetchFirst("SELECT * FROM marc_cel WHERE marca_nombre = '{$_POST['marca_nombre']}' AND marca_id != {$_POST['marca_id']}");
        if ($marcaExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otra marca con ese nombre'
            ]);
            exit;
        }
        
        try {
            // Usar consulta SQL directa para actualizar
            $sql = "UPDATE marc_cel SET 
                    marca_nombre = '{$_POST['marca_nombre']}',
                    marca_descripcion = '{$_POST['marca_descripcion']}'
                    WHERE marca_id = {$_POST['marca_id']}";
            
            $resultado = MarcaCelular::getDB()->exec($sql);

            if($resultado >= 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Marca modificada correctamente',
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar la marca',
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
                'mensaje' => 'ID de la marca es requerido'
            ]);
            exit;
        }
        
        try {
            // Cambiar estado a 0 -- NO eliminar físicamente
            $sql = "UPDATE marc_cel SET marca_estado = 0 WHERE marca_id = $id AND marca_estado = 1";
            $resultado = MarcaCelular::getDB()->exec($sql);
            
            if($resultado > 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Marca eliminada correctamente (estado cambiado a inactivo)',
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo eliminar la marca (puede que ya esté eliminada)',
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