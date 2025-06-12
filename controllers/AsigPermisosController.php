<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\AsigPermiso;
use Model\Usuarios;
use Model\Aplicacion;
use Model\Permisos;

class AsigPermisosController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('asigPermisos/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
        
        // Validar usuario
        if (empty($_POST['asignacion_usuario_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario'
            ]);
            exit;
        }
        
        // Validar aplicación
        if (empty($_POST['asignacion_app_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una aplicación'
            ]);
            exit;
        }
        
        // Validar permiso
        if (empty($_POST['asignacion_permiso_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un permiso'
            ]);
            exit;
        }
        
        // Validar usuario que coloca el permiso
        if (empty($_POST['asignacion_usuario_asigno'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe indicar el usuario que asigna'
            ]);
            exit;
        }
        
        // Sanitizar motivo
        $_POST['asignacion_motivo'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['asignacion_motivo']))));
        
        if (strlen($_POST['asignacion_motivo']) < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El motivo debe tener más de 4 caracteres'
            ]);
            exit;
        }
        
        // Verificar si ya existe la asignación
        $asignacionExistente = AsigPermiso::fetchFirst("SELECT * FROM asig_permisos WHERE asignacion_usuario_id = {$_POST['asignacion_usuario_id']} AND asignacion_app_id = {$_POST['asignacion_app_id']} AND asignacion_permiso_id = {$_POST['asignacion_permiso_id']} AND asignacion_situacion = 1");
        if ($asignacionExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Este usuario ya tiene asignado este permiso para esta aplicación'
            ]);
            exit;
        }
        
        $_POST['asignacion_fecha'] = '';
        
        $asignacion = new AsigPermiso($_POST);
        $resultado = $asignacion->crear();

        if($resultado['resultado'] == 1){
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignación de permiso registrada correctamente',
            ]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar la asignación de permiso',
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            // Consulta con JOIN para traer todos los datos relacionados
            $sql = "SELECT ap.*, 
                           u.usuario_nom1 || ' ' || u.usuario_nom2 || ' ' || u.usuario_ape1 || ' ' || u.usuario_ape2 as usuario_completo,
                           a.app_nombre_corto,
                           p.permiso_nombre,
                           p.permiso_clave,
                           ua.usuario_nom1 || ' ' || ua.usuario_nom2 || ' ' || ua.usuario_ape1 || ' ' || ua.usuario_ape2 as usuario_asigno_completo
                    FROM asig_permisos ap 
                    INNER JOIN usuario u ON ap.asignacion_usuario_id = u.usuario_id 
                    INNER JOIN aplicacion a ON ap.asignacion_app_id = a.app_id 
                    INNER JOIN permiso p ON ap.asignacion_permiso_id = p.permiso_id 
                    INNER JOIN usuario ua ON ap.asignacion_usuario_asigno = ua.usuario_id 
                    WHERE ap.asignacion_situacion = 1 
                    ORDER BY ap.asignacion_id DESC";
            
            $asignaciones = AsigPermiso::fetchArray($sql);
            
            if (!empty($asignaciones)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Asignaciones encontradas: ' . count($asignaciones),
                    'data' => $asignaciones
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron asignaciones',
                    'data' => []
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar asignaciones: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        
        if (empty($_POST['asignacion_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de la asignación es requerido'
            ]);
            exit;
        }
        
        // Validar campos obligatorios
        if (empty($_POST['asignacion_usuario_id']) || empty($_POST['asignacion_app_id']) || empty($_POST['asignacion_permiso_id']) || empty($_POST['asignacion_usuario_asigno'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Todos los campos son obligatorios'
            ]);
            exit;
        }
        
        // Sanitizar motivo
        $_POST['asignacion_motivo'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['asignacion_motivo']))));
        
        // Verificar si ya existe otra asignación igual 
        $asignacionExistente = AsigPermiso::fetchFirst("SELECT * FROM asig_permisos WHERE asignacion_usuario_id = {$_POST['asignacion_usuario_id']} AND asignacion_app_id = {$_POST['asignacion_app_id']} AND asignacion_permiso_id = {$_POST['asignacion_permiso_id']} AND asignacion_id != {$_POST['asignacion_id']} AND asignacion_situacion = 1");
        if ($asignacionExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otra asignación igual para este usuario'
            ]);
            exit;
        }
        
        try {
            // Usar consulta SQL directa para actualizar
            $sql = "UPDATE asig_permisos SET 
                    asignacion_usuario_id = {$_POST['asignacion_usuario_id']},
                    asignacion_app_id = {$_POST['asignacion_app_id']},
                    asignacion_permiso_id = {$_POST['asignacion_permiso_id']},
                    asignacion_usuario_asigno = {$_POST['asignacion_usuario_asigno']},
                    asignacion_motivo = '{$_POST['asignacion_motivo']}'
                    WHERE asignacion_id = {$_POST['asignacion_id']}";
            
            $resultado = AsigPermiso::getDB()->exec($sql);

            if($resultado >= 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Asignación modificada correctamente',
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar la asignación',
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
                'mensaje' => 'ID de la asignación es requerido'
            ]);
            exit;
        }
        
        try {
            // Cambiar situación a 0 -- NO eliminar físicamente
            $sql = "UPDATE asig_permisos SET asignacion_situacion = 0 WHERE asignacion_id = $id AND asignacion_situacion = 1";
            $resultado = AsigPermiso::getDB()->exec($sql);
            
            if($resultado > 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Asignación eliminada correctamente (situación cambiada a inactiva)',
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo eliminar la asignación (puede que ya esté eliminada)',
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

    public static function obtenerUsuariosAPI()
    {
        getHeadersApi();
        
        try {
            $usuarios = Usuarios::where('usuario_situacion', 1);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios encontrados',
                'data' => $usuarios
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener usuarios: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function obtenerAplicacionesAPI()
    {
        getHeadersApi();
        
        try {
            $aplicaciones = Aplicacion::where('app_situacion', 1);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Aplicaciones encontradas',
                'data' => $aplicaciones
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener aplicaciones: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function obtenerPermisosPorAppAPI()
    {
        getHeadersApi();
        
        $app_id = $_GET['app_id'] ?? null;
        
        if (!$app_id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de aplicación es requerido'
            ]);
            exit;
        }
        
        try {
            // Usar consulta SQL directa para evitar problemas con el filtro
            $sql = "SELECT * FROM permiso WHERE permiso_app_id = $app_id AND permiso_situacion = 1";
            $permisos = Permisos::fetchArray($sql);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos encontrados',
                'data' => $permisos
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener permisos: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}