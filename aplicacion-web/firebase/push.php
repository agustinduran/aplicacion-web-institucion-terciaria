<?php
namespace App\Controller;

class Push {

    private $is_background;

    private $asunto;
    private $descripcion;
    private $fecha;

    //TODO si la notificación está activa para ver
    // private $flagActivo;

    /* *** GETTERS *** */

    function __construct() {

    }

    public function setAsunto($asunto) {
        $this->asunto = $asunto;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }


    public function setIsBackground($is_background) {
        $this->is_background = $is_background;
    }

    /*
    public function setFlagActivo($flagActivo){
        $this->flagActivo = $flagActivo;
    }
    */

    public function getPush() {
        $res = array();
        $res['data']['asunto'] = $this->asunto;
        $res['data']['is_background'] = $this->is_background;
        $res['data']['descripcion'] = $this->descripcion;
        $res['data']['fecha'] = $this->fecha;

        //$res['data']['flagActivo'] = $this->flagActivo;

        return $res;
    }

}
