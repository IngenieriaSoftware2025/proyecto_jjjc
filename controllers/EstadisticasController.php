<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;

class EstadisticasController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('estadisticas/index', []);
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            // 1. PRODUCTOS MÁS VENDIDOS 
            $consultaProductos = "SELECT 
                i.invent_modelo as producto,
                i.invent_id as producto_id,
                m.marca_nombre,
                SUM(dv.detalle_cantidad) as cantidad
                FROM detalle_ventas dv
                INNER JOIN invent_cel i ON dv.detalle_inventario_id = i.invent_id
                INNER JOIN marc_cel m ON i.invent_marca_id = m.marca_id
                INNER JOIN rep_ventas v ON dv.detalle_venta_id = v.venta_id
                WHERE v.venta_estado = 'COMPLETADA'
                GROUP BY i.invent_id, i.invent_modelo, m.marca_nombre
                ORDER BY cantidad DESC
                LIMIT 10";
            
            $productosVendidos = ActiveRecord::fetchArray($consultaProductos);
            
            // Formatear datos de productos
            $productosFormateados = [];
            foreach ($productosVendidos as $producto) {
                $productosFormateados[] = [
                    'producto' => $producto['marca_nombre'] . ' ' . $producto['producto'],
                    'pro_id' => $producto['producto_id'],
                    'cantidad' => (int)$producto['cantidad']
                ];
            }

            // 2. CLIENTES CON MÁS COMPRAS
            $consultaClientes = "SELECT 
                c.cli_nombres,
                c.cli_apellidos,
                COUNT(v.venta_id) as total_compras,
                SUM(v.venta_total) as monto_total,
                SUM(CASE WHEN dv.detalle_cantidad IS NOT NULL THEN dv.detalle_cantidad ELSE 0 END) as cantidad_total_productos
                FROM clientes c
                INNER JOIN rep_ventas v ON c.cli_id = v.venta_cliente_id
                LEFT JOIN detalle_ventas dv ON v.venta_id = dv.detalle_venta_id
                WHERE v.venta_estado = 'COMPLETADA'
                GROUP BY c.cli_id, c.cli_nombres, c.cli_apellidos
                ORDER BY total_compras DESC, monto_total DESC
                LIMIT 10";
            
            $clientesActivos = ActiveRecord::fetchArray($consultaClientes);
            
            // Formatear datos de clientes
            $clientesFormateados = [];
            foreach ($clientesActivos as $cliente) {
                $clientesFormateados[] = [
                    'cliente' => $cliente['cli_nombres'] . ' ' . $cliente['cli_apellidos'],
                    'total_compras' => (int)$cliente['total_compras'],
                    'monto_total' => (float)$cliente['monto_total'],
                    'cantidad_total_prod' => (int)$cliente['cantidad_total_productos']
                ];
            }

            // 3. VENTAS POR MES DEL AÑO ACTUAL
            $anioActual = date('Y');
            $consultaVentasMes = "SELECT 
                SUM(CASE WHEN MONTH(venta_fecha) = 1 THEN venta_total ELSE 0 END) as enero,
                SUM(CASE WHEN MONTH(venta_fecha) = 2 THEN venta_total ELSE 0 END) as febrero,
                SUM(CASE WHEN MONTH(venta_fecha) = 3 THEN venta_total ELSE 0 END) as marzo,
                SUM(CASE WHEN MONTH(venta_fecha) = 4 THEN venta_total ELSE 0 END) as abril,
                SUM(CASE WHEN MONTH(venta_fecha) = 5 THEN venta_total ELSE 0 END) as mayo,
                SUM(CASE WHEN MONTH(venta_fecha) = 6 THEN venta_total ELSE 0 END) as junio,
                SUM(CASE WHEN MONTH(venta_fecha) = 7 THEN venta_total ELSE 0 END) as julio,
                SUM(CASE WHEN MONTH(venta_fecha) = 8 THEN venta_total ELSE 0 END) as agosto,
                SUM(CASE WHEN MONTH(venta_fecha) = 9 THEN venta_total ELSE 0 END) as septiembre,
                SUM(CASE WHEN MONTH(venta_fecha) = 10 THEN venta_total ELSE 0 END) as octubre,
                SUM(CASE WHEN MONTH(venta_fecha) = 11 THEN venta_total ELSE 0 END) as noviembre,
                SUM(CASE WHEN MONTH(venta_fecha) = 12 THEN venta_total ELSE 0 END) as diciembre
                FROM rep_ventas 
                WHERE YEAR(venta_fecha) = $anioActual 
                AND venta_estado = 'COMPLETADA'";
            
            $ventasMensuales = ActiveRecord::fetchArray($consultaVentasMes);
            
            if (empty($ventasMensuales)) {
                $ventasMensuales = [[
                    'enero' => 0, 'febrero' => 0, 'marzo' => 0, 'abril' => 0,
                    'mayo' => 0, 'junio' => 0, 'julio' => 0, 'agosto' => 0,
                    'septiembre' => 0, 'octubre' => 0, 'noviembre' => 0, 'diciembre' => 0
                ]];
            }

            // 4. ESTADÍSTICAS ADICIONALES
            $consultaEstadisticasGenerales = "SELECT 
                (SELECT COUNT(*) FROM invent_cel WHERE invent_estado = 1) as total_productos_stock,
                (SELECT COUNT(*) FROM rep_ventas WHERE venta_estado = 'COMPLETADA' AND YEAR(venta_fecha) = $anioActual) as total_ventas_anio,
                (SELECT COUNT(*) FROM clientes WHERE cli_estado = 1) as total_clientes_activos,
                (SELECT COUNT(*) FROM ordenes_reparacion WHERE orden_situacion = 1) as total_reparaciones_activas,
                (SELECT SUM(venta_total) FROM rep_ventas WHERE venta_estado = 'COMPLETADA' AND YEAR(venta_fecha) = $anioActual) as ingresos_totales_anio,
                (SELECT SUM(invent_cantidad_disponible) FROM invent_cel WHERE invent_estado = 1) as cantidad_total_inventario";
            
            $estadisticasGenerales = ActiveRecord::fetchFirst($consultaEstadisticasGenerales);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas generadas correctamente',
                'productos' => $productosFormateados,
                'clientes' => $clientesFormateados,
                'ventasMes' => $ventasMensuales,
                'estadisticasGenerales' => $estadisticasGenerales,
                'anio' => $anioActual
            ]);
            
        } catch (Exception $errorEstadisticas) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al generar estadísticas: ' . $errorEstadisticas->getMessage()
            ]);
        }
        exit;
    }

    public static function resumenVentasAPI()
    {
        getHeadersApi();
        
        try {
            $anioActual = date('Y');
            $mesActual = date('m');
            
            // Resumen de ventas del mes actual
            $consultaResumenMes = "SELECT 
                COUNT(*) as total_ventas_mes,
                SUM(venta_total) as ingresos_mes,
                AVG(venta_total) as promedio_venta,
                MAX(venta_total) as venta_mayor,
                MIN(venta_total) as venta_menor
                FROM rep_ventas 
                WHERE YEAR(venta_fecha) = $anioActual 
                AND MONTH(venta_fecha) = $mesActual
                AND venta_estado = 'COMPLETADA'";
            
            $resumenMensual = ActiveRecord::fetchFirst($consultaResumenMes);
            
            // Top 5 productos del mes
            $consultaTopProductosMes = "SELECT 
                i.invent_modelo,
                m.marca_nombre,
                SUM(dv.detalle_cantidad) as cantidad_vendida,
                SUM(dv.detalle_subtotal) as ingresos_producto
                FROM detalle_ventas dv
                INNER JOIN invent_cel i ON dv.detalle_inventario_id = i.invent_id
                INNER JOIN marc_cel m ON i.invent_marca_id = m.marca_id
                INNER JOIN rep_ventas v ON dv.detalle_venta_id = v.venta_id
                WHERE YEAR(v.venta_fecha) = $anioActual 
                AND MONTH(v.venta_fecha) = $mesActual
                AND v.venta_estado = 'COMPLETADA'
                GROUP BY i.invent_id, i.invent_modelo, m.marca_nombre
                ORDER BY cantidad_vendida DESC
                LIMIT 5";
            
            $topProductosMes = ActiveRecord::fetchArray($consultaTopProductosMes);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Resumen de ventas generado',
                'resumenMensual' => $resumenMensual,
                'topProductosMes' => $topProductosMes,
                'mes' => (int)$mesActual,
                'anio' => (int)$anioActual
            ]);
            
        } catch (Exception $errorResumen) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al generar resumen: ' . $errorResumen->getMessage()
            ]);
        }
        exit;
    }
}