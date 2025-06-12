<?php

namespace Model;

class Empleado extends ActiveRecord {
    
    public static $tabla = 'empleados';
    public static $columnasDB = [
        'empleado_nombres',
        'empleado_apellidos',
        'empleado_telefono',
        'empleado_especialidad',
        'empleado_estado',
        //'empleado_fecha_ingreso' 
    ];

    public static $idTabla = 'empleado_id';
    
    public $empleado_id;
    public $empleado_nombres;
    public $empleado_apellidos;
    public $empleado_telefono;
    public $empleado_especialidad;
    public $empleado_estado;
    public $empleado_fecha_ingreso;

    public function __construct($args = []){
        $this->empleado_id = $args['empleado_id'] ?? null;
        $this->empleado_nombres = $args['empleado_nombres'] ?? '';
        $this->empleado_apellidos = $args['empleado_apellidos'] ?? '';
        $this->empleado_telefono = $args['empleado_telefono'] ?? '';
        $this->empleado_especialidad = $args['empleado_especialidad'] ?? '';
        $this->empleado_estado = $args['empleado_estado'] ?? 1;
        $this->empleado_fecha_ingreso = $args['empleado_fecha_ingreso'] ?? date('Y-m-d H:i:s');
    }
}