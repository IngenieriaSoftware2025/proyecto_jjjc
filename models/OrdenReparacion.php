<?php

namespace Model;

class OrdenReparacion extends ActiveRecord {
    
    public static $tabla = 'ordenes_reparacion';
    public static $columnasDB = [
        'orden_cli_id',
        'orden_empleado_id',
        'orden_serv_id',
        'orden_modelo_celular',
        'orden_marca_celular',
        'orden_motivo_ingreso',
        'orden_diagnostico',
        'orden_precio_servicio',
        //'orden_fecha_ingreso',
        //'orden_fecha_asignacion',
        //'orden_fecha_finalizacion',
        'orden_estado',
        'orden_observaciones',
        'orden_situacion'
    ];

    public static $idTabla = 'orden_id';
    
    public $orden_id;
    public $orden_cli_id;
    public $orden_empleado_id;
    public $orden_serv_id;
    public $orden_modelo_celular;
    public $orden_marca_celular;
    public $orden_motivo_ingreso;
    public $orden_diagnostico;
    public $orden_precio_servicio;
    public $orden_fecha_ingreso;
    public $orden_fecha_asignacion;
    public $orden_fecha_finalizacion;
    public $orden_estado;
    public $orden_observaciones;
    public $orden_situacion;

    public function __construct($args = []){
        $this->orden_id = $args['orden_id'] ?? null;
        $this->orden_cli_id = $args['orden_cli_id'] ?? '';
        $this->orden_empleado_id = $args['orden_empleado_id'] ?? '';
        $this->orden_serv_id = $args['orden_serv_id'] ?? '';
        $this->orden_modelo_celular = $args['orden_modelo_celular'] ?? '';
        $this->orden_marca_celular = $args['orden_marca_celular'] ?? '';
        $this->orden_motivo_ingreso = $args['orden_motivo_ingreso'] ?? '';
        $this->orden_diagnostico = $args['orden_diagnostico'] ?? '';
        $this->orden_precio_servicio = $args['orden_precio_servicio'] ?? 0.00;
        $this->orden_fecha_ingreso = $args['orden_fecha_ingreso'] ?? '';
        $this->orden_fecha_asignacion = $args['orden_fecha_asignacion'] ?? '';
        $this->orden_fecha_finalizacion = $args['orden_fecha_finalizacion'] ?? '';
        $this->orden_estado = $args['orden_estado'] ?? 'RECIBIDO';
        $this->orden_observaciones = $args['orden_observaciones'] ?? '';
        $this->orden_situacion = $args['orden_situacion'] ?? 1;
    }
}