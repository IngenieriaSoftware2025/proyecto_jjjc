<?php

namespace Model;

class Venta extends ActiveRecord {
    
    public static $tabla = 'rep_ventas';
    public static $columnasDB = [
        'venta_cliente_id',
        'venta_total',
        'venta_estado',
        'venta_tipo',
        //'venta_fecha'
    ];

    public static $idTabla = 'venta_id';
    
    public $venta_id;
    public $venta_cliente_id;
    public $venta_total;
    public $venta_fecha;
    public $venta_estado;
    public $venta_tipo;

    public function __construct($args = []){
        $this->venta_id = $args['venta_id'] ?? null;
        $this->venta_cliente_id = $args['venta_cliente_id'] ?? '';
        $this->venta_total = $args['venta_total'] ?? 0.00;
        $this->venta_fecha = $args['venta_fecha'] ?? '';
        $this->venta_estado = $args['venta_estado'] ?? 'COMPLETADA';
        $this->venta_tipo = $args['venta_tipo'] ?? 'VENTA';
    }
}