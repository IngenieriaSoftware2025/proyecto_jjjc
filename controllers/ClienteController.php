<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Cliente;

class ClienteController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('clientes/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
        
        // Sanitizar nombres
        $_POST['cli_nombres'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cli_nombres']))));
        
        $cantidad_nombres = strlen($_POST['cli_nombres']);
        
        if ($cantidad_nombres < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Los nombres deben tener más de 1 caracteres'
            ]);
            exit;
        }
        
        // Sanitizar apellidos
        $_POST['cli_apellidos'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cli_apellidos']))));
        
        $cantidad_apellidos = strlen($_POST['cli_apellidos']);
        
        if ($cantidad_apellidos < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Los apellidos deben tener más de 1 caracteres'
            ]);
            exit;
        }
        
        // Sanitizar y validar teléfono
        $_POST['cli_telefono'] = filter_var($_POST['cli_telefono'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['cli_telefono']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono debe tener exactamente 8 dígitos'
            ]);
            exit;
        }
        
        // Sanitizar NIT (opcional)
        if (!empty($_POST['cli_nit'])) {
            $_POST['cli_nit'] = trim(htmlspecialchars($_POST['cli_nit']));
        }
        
        // Sanitizar y validar correo (opcional)
        if (!empty($_POST['cli_correo'])) {
            $_POST['cli_correo'] = filter_var($_POST['cli_correo'], FILTER_SANITIZE_EMAIL);
            if (!filter_var($_POST['cli_correo'], FILTER_VALIDATE_EMAIL)){
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico no es válido'
                ]);
                exit;
            }
        }
        
        // Sanitizar dirección
        $_POST['cli_direccion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cli_direccion']))));
        
        if (strlen($_POST['cli_direccion']) < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La dirección debe tener más de 4 caracteres'
            ]);
            exit;
        }
        
        // Verificar si el teléfono ya existe
        $clienteExistente = Cliente::where('cli_telefono', $_POST['cli_telefono']);
        if (!empty($clienteExistente)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un cliente con ese número de teléfono'
            ]);
            exit;
        }
        
        $_POST['cli_fecha_registro'] = '';
        
        $cliente = new Cliente($_POST);
        $resultado = $cliente->crear();

        if($resultado['resultado'] == 1){
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Cliente registrado correctamente',
            ]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar el cliente',
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            // Usar consulta SQL directa para la fecha
            $sql = "SELECT cli_id, cli_nombres, cli_apellidos, cli_nit, cli_telefono, 
                           cli_correo, cli_direccion, cli_estado, cli_fecha_registro 
                    FROM clientes 
                    WHERE cli_estado = 1";
            
            $clientes = Cliente::fetchArray($sql);
            
            // Formatear fechas
            if (!empty($clientes)) {
                foreach ($clientes as &$cliente) {
                    if (!empty($cliente['cli_fecha_registro'])) {
                        $cliente['cli_fecha_registro'] = date('d/m/Y', strtotime($cliente['cli_fecha_registro']));
                    }
                }
            }
            
            if (!empty($clientes)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Clientes encontrados: ' . count($clientes),
                    'data' => $clientes
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron clientes',
                    'data' => []
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar clientes: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        
        if (empty($_POST['cli_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID del cliente es requerido'
            ]);
            exit;
        }
        
        // Sanitizar datos igual que en guardar
        $_POST['cli_nombres'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cli_nombres']))));
        $_POST['cli_apellidos'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cli_apellidos']))));
        $_POST['cli_telefono'] = filter_var($_POST['cli_telefono'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['cli_direccion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cli_direccion']))));
        
        if (!empty($_POST['cli_nit'])) {
            $_POST['cli_nit'] = trim(htmlspecialchars($_POST['cli_nit']));
        }
        
        if (!empty($_POST['cli_correo'])) {
            $_POST['cli_correo'] = filter_var($_POST['cli_correo'], FILTER_SANITIZE_EMAIL);
        }
        
        // Verificar si el teléfono ya existe (excluyendo el actual)
        $clienteExistente = Cliente::fetchFirst("SELECT * FROM clientes WHERE cli_telefono = '{$_POST['cli_telefono']}' AND cli_id != {$_POST['cli_id']}");
        if ($clienteExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro cliente con ese número de teléfono'
            ]);
            exit;
        }
        
        try {
            // Usar consulta SQL directa para actualizar
            $sql = "UPDATE clientes SET 
                    cli_nombres = '{$_POST['cli_nombres']}',
                    cli_apellidos = '{$_POST['cli_apellidos']}',
                    cli_nit = '{$_POST['cli_nit']}',
                    cli_telefono = '{$_POST['cli_telefono']}',
                    cli_correo = '{$_POST['cli_correo']}',
                    cli_direccion = '{$_POST['cli_direccion']}'
                    WHERE cli_id = {$_POST['cli_id']}";
            
            $resultado = Cliente::getDB()->exec($sql);

            if($resultado >= 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Cliente modificado correctamente',
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar el cliente',
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
                'mensaje' => 'ID del cliente es requerido'
            ]);
            exit;
        }
        
        try {
            // Cambiar estado a 0 (eliminación lógica) - NO eliminar físicamente
            $sql = "UPDATE clientes SET cli_estado = 0 WHERE cli_id = $id AND cli_estado = 1";
            $resultado = Cliente::getDB()->exec($sql);
            
            if($resultado > 0){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Cliente eliminado correctamente (estado cambiado a inactivo)',
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo eliminar el cliente (puede que ya esté eliminado)',
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