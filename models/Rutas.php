<?php

namespace Model;

class Rutas extends ActiveRecord {
    
    public static $tabla = 'rutas';
    public static $columnasDB = [
        'ruta_app_id',
        'ruta_nombre',
        'ruta_descripcion',
        'ruta_situacion'
    ];

    public static $idTabla = 'ruta_id';
    
    public $ruta_id;
    public $ruta_app_id;
    public $ruta_nombre;
    public $ruta_descripcion;
    public $ruta_situacion;

    public function __construct($args = []){
        $this->ruta_id = $args['ruta_id'] ?? null;
        $this->ruta_app_id = $args['ruta_app_id'] ?? '';
        $this->ruta_nombre = $args['ruta_nombre'] ?? '';
        $this->ruta_descripcion = $args['ruta_descripcion'] ?? '';
        $this->ruta_situacion = $args['ruta_situacion'] ?? 1;
    }
}