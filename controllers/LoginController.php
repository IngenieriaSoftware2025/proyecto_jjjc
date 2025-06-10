<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Usuarios;
use MVC\Router;

class LoginController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('login/index', [], 'layout/layoutLogin');
    }




      public static function login() {
        getHeadersApi();
        try{
            $usuario = htmlspecialchars($_POST['usuario_correo'], FILTER_SANITIZE_NUMBER_INT);
            $contrasena = htmlspecialchars($_POST['usuario_contra']);



        }catch(Exception $e){
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Erro al intentar loguear',
                'detalle' => $e->getMessage()
            ]);
        }
    }




    public static function autenticarAPI()
    {
        getHeadersApi();
        
        // Validar que se envíen los datos necesarios
        if (empty($_POST['usuario_correo']) || empty($_POST['usuario_contra'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo y la contraseña son requeridos'
            ]);
            exit;
        }

        // Sanitizar el correo
        $correo = filter_var(trim($_POST['usuario_correo']), FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El formato del correo electrónico no es válido'
            ]);
            exit;
        }

        $contraseña = $_POST['usuario_contra'];

        try {
            // Buscar usuario por correo y que esté activo
            $usuario = Usuarios::fetchFirst("SELECT * FROM usuario WHERE usuario_correo = '$correo' AND usuario_situacion = 1");
            
            if (!$usuario) {
                http_response_code(401);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Correo electrónico no registrado o usuario inactivo'
                ]);
                exit;
            }

            // Verificar la contraseña
            if (!password_verify($contraseña, $usuario['usuario_contra'])) {
                http_response_code(401);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Contraseña incorrecta'
                ]);
                exit;
            }

            // Login exitoso - Crear sesión
            session_start();
            $_SESSION['auth_user'] = true;
            $_SESSION['usuario_id'] = $usuario['usuario_id'];
            $_SESSION['usuario_nombre'] = $usuario['usuario_nom1'] . ' ' . $usuario['usuario_ape1'];
            $_SESSION['usuario_correo'] = $usuario['usuario_correo'];
            $_SESSION['usuario_foto'] = $usuario['usuario_fotografia'];

            // Actualizar fecha de último acceso (opcional)
            self::actualizarUltimoAcceso($usuario['usuario_id']);

            // Cargar permisos del usuario
            self::cargarPermisosUsuario($usuario['usuario_id']);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Inicio de sesión exitoso',
                'usuario' => [
                    'id' => $usuario['usuario_id'],
                    'nombre' => $usuario['usuario_nom1'] . ' ' . $usuario['usuario_ape1'],
                    'correo' => $usuario['usuario_correo']
                ]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage()
            ]);
        }
        exit;
    }

    public static function cerrarSesionAPI()
    {
        getHeadersApi();
        
        try {
            session_start();
            
            // Limpiar todas las variables de sesión
            $_SESSION = array();
            
            // Destruir la cookie de sesión si existe
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            
            // Destruir la sesión
            session_destroy();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Sesión cerrada correctamente'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al cerrar sesión',
                'detalle' => $e->getMessage()
            ]);
        }
        exit;
    }

    public static function verificarSesionAPI()
    {
        getHeadersApi();
        
        try {
            session_start();
            
            if (isset($_SESSION['auth_user']) && $_SESSION['auth_user'] === true) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Sesión activa',
                    'usuario' => [
                        'id' => $_SESSION['usuario_id'],
                        'nombre' => $_SESSION['usuario_nombre'],
                        'correo' => $_SESSION['usuario_correo']
                    ]
                ]);
            } else {
                http_response_code(401);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No hay sesión activa'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al verificar sesión',
                'detalle' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Método privado para actualizar último acceso
    private static function actualizarUltimoAcceso($usuarioId)
    {
        try {
            $sql = "UPDATE usuario SET usuario_fecha_contra = TODAY WHERE usuario_id = $usuarioId";
            self::getDB()->exec($sql);
        } catch (Exception $e) {
            // Log del error pero no interrumpir el login
            error_log("Error al actualizar último acceso: " . $e->getMessage());
        }
    }

    // Método privado para cargar permisos del usuario en la sesión
    private static function cargarPermisosUsuario($usuarioId)
    {
        try {
            // Consulta para obtener todos los permisos asignados al usuario
            $sql = "SELECT 
                        p.permiso_clave,
                        p.permiso_nombre,
                        a.app_nombre_corto,
                        a.app_nombre_medium
                    FROM asig_permisos ap
                    INNER JOIN permiso p ON ap.asignacion_permiso_id = p.permiso_id
                    INNER JOIN aplicacion a ON ap.asignacion_app_id = a.app_id
                    WHERE ap.asignacion_usuario_id = $usuarioId 
                    AND ap.asignacion_situacion = 1 
                    AND p.permiso_situacion = 1 
                    AND a.app_situacion = 1";
            
            $permisos = self::fetchArray($sql);
            
            // Guardar permisos en la sesión
            $_SESSION['permisos_usuario'] = [];
            
            foreach ($permisos as $permiso) {
                // Crear clave única para el permiso (app + permiso)
                $clavePermiso = $permiso['app_nombre_corto'] . '_' . $permiso['permiso_clave'];
                $_SESSION['permisos_usuario'][] = $clavePermiso;
                
                // También guardar individual para compatibilidad con hasPermission()
                $_SESSION[$clavePermiso] = true;
            }

        } catch (Exception $e) {
            // Log del error pero no interrumpir el login
            error_log("Error al cargar permisos del usuario: " . $e->getMessage());
            $_SESSION['permisos_usuario'] = [];
        }
    }

    // Método público para verificar si el usuario tiene un permiso específico
    public static function tienePermiso($clavePermiso)
    {
        session_start();
        
        if (!isset($_SESSION['auth_user']) || $_SESSION['auth_user'] !== true) {
            return false;
        }

        return isset($_SESSION[$clavePermiso]) && $_SESSION[$clavePermiso] === true;
    }

    // Método para obtener todos los permisos del usuario actual
    public static function obtenerPermisosUsuario()
    {
        session_start();
        
        if (!isset($_SESSION['auth_user']) || $_SESSION['auth_user'] !== true) {
            return [];
        }

        return $_SESSION['permisos_usuario'] ?? [];
    }

    // Página de logout (redirige)
    public static function cerrarSesion(Router $router)
    {
        session_start();
        session_destroy();
        header('Location: /proyecto_jjjc/login');
        exit;
    }
}