<?php

namespace Model;

class InventarioCelular extends ActiveRecord {
    
    public static $tabla = 'invent_cel';
    public static $columnasDB = [
        'invent_modelo',
        'invent_marca_id',
        'invent_precio_compra',
        'invent_precio_venta',
        'invent_cantidad_disponible',
        'invent_descripcion',
        'invent_estado',
        //'invent_fecha_ingreso'
    ];

    public static $idTabla = 'invent_id';
    
    public $invent_id;
    public $invent_modelo;
    public $invent_marca_id;
    public $invent_precio_compra;
    public $invent_precio_venta;
    public $invent_cantidad_disponible;
    public $invent_descripcion;
    public $invent_estado;
    public $invent_fecha_ingreso;

    public function __construct($args = []){
        $this->invent_id = $args['invent_id'] ?? null;
        $this->invent_modelo = $args['invent_modelo'] ?? '';
        $this->invent_marca_id = $args['invent_marca_id'] ?? '';
        $this->invent_precio_compra = $args['invent_precio_compra'] ?? 0.00;
        $this->invent_precio_venta = $args['invent_precio_venta'] ?? 0.00;
        $this->invent_cantidad_disponible = $args['invent_cantidad_disponible'] ?? 0;
        $this->invent_descripcion = $args['invent_descripcion'] ?? '';
        $this->invent_estado = $args['invent_estado'] ?? 1;
        $this->invent_fecha_ingreso = $args['invent_fecha_ingreso'] ?? date('Y-m-d H:i:s');
    }
}