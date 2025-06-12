<?php

namespace Model;

class DetalleVenta extends ActiveRecord {
    
    public static $tabla = 'detalle_ventas';
    public static $columnasDB = [
        'detalle_venta_id',
        'detalle_inventario_id',
        'detalle_cantidad',
        'detalle_precio_unitario',
        'detalle_subtotal'
    ];

    public static $idTabla = 'detalle_id';
    
    public $detalle_id;
    public $detalle_venta_id;
    public $detalle_inventario_id;
    public $detalle_cantidad;
    public $detalle_precio_unitario;
    public $detalle_subtotal;

    public function __construct($args = []){
        $this->detalle_id = $args['detalle_id'] ?? null;
        $this->detalle_venta_id = $args['detalle_venta_id'] ?? '';
        $this->detalle_inventario_id = $args['detalle_inventario_id'] ?? '';
        $this->detalle_cantidad = $args['detalle_cantidad'] ?? 1;
        $this->detalle_precio_unitario = $args['detalle_precio_unitario'] ?? 0.00;
        $this->detalle_subtotal = $args['detalle_subtotal'] ?? 0.00;
    }
}