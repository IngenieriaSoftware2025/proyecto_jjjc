<?php

namespace Model;

class TipoServicio extends ActiveRecord {
    
    public static $tabla = 'tipo_servicio';
    public static $columnasDB = [
        'serv_nombre',
        'serv_precio',
        'serv_descripcion',
        'serv_estado'
    ];

    public static $idTabla = 'serv_id';
    
    public $serv_id;
    public $serv_nombre;
    public $serv_precio;
    public $serv_descripcion;
    public $serv_estado;

    public function __construct($args = []){
        $this->serv_id = $args['serv_id'] ?? null;
        $this->serv_nombre = $args['serv_nombre'] ?? '';
        $this->serv_precio = $args['serv_precio'] ?? 0.00;
        $this->serv_descripcion = $args['serv_descripcion'] ?? '';
        $this->serv_estado = $args['serv_estado'] ?? 1;
    }
}