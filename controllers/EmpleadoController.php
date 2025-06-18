<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Empleado;

class EmpleadoController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('empleados/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
        
        // Sanitizar nombres
        $_POST['empleado_nombres'] = ucwords(strtolower(trim(htmlspecialchars($_POST['empleado_nombres']))));
        
        $cantidad_nombres = strlen($_POST['empleado_nombres']);
        
        if ($cantidad_nombres < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Los nombres deben tener más de 1 caracteres'
            ]);
            exit;
        }
        
        // Sanitizar apellidos
        $_POST['empleado_apellidos'] = ucwords(strtolower(trim(htmlspecialchars($_POST['empleado_apellidos']))));
        
        $cantidad_apellidos = strlen($_POST['empleado_apellidos']);
        
        if ($cantidad_apellidos < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Los apellidos deben tener más de 1 caracteres'
            ]);
            exit;
        }
        
        // Sanitizar y validar teléfono
        $_POST['empleado_telefono'] = filter_var($_POST['empleado_telefono'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['empleado_telefono']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono debe tener exactamente 8 dígitos'
            ]);
            exit;
        }
        
        // Sanitizar especialidad
        $_POST['empleado_especialidad'] = ucwords(strtolower(trim(htmlspecialchars($_POST['empleado_especialidad']))));
        
        if (strlen($_POST['empleado_especialidad']) < 3) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La especialidad debe tener más de 2 caracteres'
            ]);
            exit;
        }
        
        // Verificar si el teléfono ya existe
        $empleadoExistente = Empleado::where('empleado_telefono', $_POST['empleado_telefono']);
        if (!empty($empleadoExistente)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un empleado con ese número de teléfono'
            ]);
            exit;
        }
        
        $_POST['empleado_fecha_ingreso'] = '';
        
        $empleado = new Empleado($_POST);
        $resultado = $empleado->crear();

        if($resultado['resultado'] == 1){
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Empleado registrado correctamente',
            ]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar el empleado',
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
             // Usar consulta SQL directa para la fecha
            $sql = "SELECT empleado_id, empleado_nombres, empleado_apellidos, empleado_telefono, 
                           empleado_especialidad, empleado_estado, empleado_fecha_ingreso 
                    FROM empleados 
                    WHERE empleado_estado = 1";
            
            $empleados = Empleado::fetchArray($sql);
            
            // Formatear fechas
            if (!empty($empleados)) {
                foreach ($empleados as &$e) {
                    if (!empty($e['empleado_fecha_ingreso'])) {
                        $e['empleado_fecha_ingreso'] = date('d/m/Y', strtotime($e['empleado_fecha_ingreso']));
                    }
                }
            }
            
            if (!empty($empleados)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Empleados encontrados: ' . count($empleados),
                    'data' => $empleados
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron empleados',
                    'data' => []
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar empleados: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        
        if (empty($_POST['empleado_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID del empleado es requerido'
            ]);
            exit;
        }
        
        // Sanitizar datos igual que en guardar
        $_POST['empleado_nombres'] = ucwords(strtolower(trim(htmlspecialchars($_POST['empleado_nombres']))));
        $_POST['empleado_apellidos'] = ucwords(strtolower(trim(htmlspecialchars($_POST['empleado_apellidos']))));
        $_POST['empleado_telefono'] = filter_var($_POST['empleado_telefono'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['empleado_especialidad'] = ucwords(strtolower(trim(htmlspecialchars($_POST['empleado_especialidad']))));
        
        // Verificar si el teléfono ya existe (excluyendo el actual)
        $empleadoExistente = Empleado::fetchFirst("SELECT * FROM empleados WHERE empleado_telefono = '{$_POST['empleado_telefono']}' AND empleado_id != {$_POST['empleado_id']}");
        if ($empleadoExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro empleado con ese número de teléfono'
            ]);
            exit;
        }
        
        try {
            // Usar consulta SQL directa para actualizar
            $sql = "UPDATE empleados SET 
                    empleado_nombres = '{$_POST['empleado_nombres']}',
                    empleado_apellidos = '{$_POST['empleado_apellidos']}',
                    empleado_telefono = '{$_POST['empleado_telefono']}',
                    empleado_especialidad = '{$_POST['empleado_especialidad']}'
                    WHERE empleado_id = {$_POST['empleado_id']}";
            
            $resultado = Empleado::getDB()->exec($sql);

            if($resultado >= 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Empleado modificado correctamente',
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar el empleado',
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
                'mensaje' => 'ID del empleado es requerido'
            ]);
            exit;
        }
        
        try {
            // Cambiar estado a 0  - NO eliminar físicamente
            $sql = "UPDATE empleados SET empleado_estado = 0 WHERE empleado_id = $id AND empleado_estado = 1";
            $resultado = Empleado::getDB()->exec($sql);
            
            if($resultado > 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Empleado eliminado correctamente (estado cambiado a inactivo)',
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo eliminar el empleado (puede que ya esté eliminado)',
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