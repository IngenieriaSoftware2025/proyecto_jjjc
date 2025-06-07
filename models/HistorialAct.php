<?php

namespace Model;

// Modelo para la tabla historial_act
class HistorialAct extends ActiveRecord {
    
    public static $tabla = 'historial_act';
    public static $columnasDB = [
        'historial_usuario_id',
        'historial_fecha',
        'historial_ruta',
        'historial_ejecucion',
        'historial_situacion'
    ];

    public static $idTabla = 'historial_id';
    
    public $historial_id;
    public $historial_usuario_id;
    public $historial_fecha;
    public $historial_ruta;
    public $historial_ejecucion;
    public $historial_situacion;

    public function __construct($args = []){
        $this->historial_id = $args['historial_id'] ?? null;
        $this->historial_usuario_id = $args['historial_usuario_id'] ?? '';
        $this->historial_fecha = $args['historial_fecha'] ?? date('Y-m-d H:i');
        $this->historial_ruta = $args['historial_ruta'] ?? '';
        $this->historial_ejecucion = $args['historial_ejecucion'] ?? '';
        $this->historial_situacion = $args['historial_situacion'] ?? 1;
    }
}