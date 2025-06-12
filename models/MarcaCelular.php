<?php

namespace Model;

class MarcaCelular extends ActiveRecord {
    
    public static $tabla = 'marc_cel';
    public static $columnasDB = [
        'marca_nombre',
        'marca_descripcion',
        'marca_estado',
        //'marca_fecha_creacion'
    ];

    public static $idTabla = 'marca_id';
    
    public $marca_id;
    public $marca_nombre;
    public $marca_descripcion;
    public $marca_estado;
    public $marca_fecha_creacion;

    public function __construct($args = []){
        $this->marca_id = $args['marca_id'] ?? null;
        $this->marca_nombre = $args['marca_nombre'] ?? '';
        $this->marca_descripcion = $args['marca_descripcion'] ?? '';
        $this->marca_estado = $args['marca_estado'] ?? 1;
        $this->marca_fecha_creacion = $args['marca_fecha_creacion'] ?? date('Y-m-d H:i:s');
    }
}