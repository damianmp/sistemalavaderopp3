<?php
class LogDTO {
    private $m_id;
    private $m_usuario;
    private $m_idmovimiento;
    private $m_accion;
    private $fecha;
    
    public function __construct($m_usuario, $m_idmovimiento, $m_accion) {
        $this->m_usuario = $m_usuario;
        $this->m_idmovimiento = $m_idmovimiento;
        $this->m_accion = $m_accion;
    }
    
    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha): void {
        $this->fecha = $fecha;
    }

    public function getM_id() {
        return $this->m_id;
    }

    public function getM_usuario() {
        return $this->m_usuario;
    }

    public function getM_idmovimiento() {
        return $this->m_idmovimiento;
    }

    public function getM_accion() {
        return $this->m_accion;
    }

    public function setM_id($m_id): void {
        $this->m_id = $m_id;
    }

    public function setM_usuario($m_usuario): void {
        $this->m_usuario = $m_usuario;
    }

    public function setM_idmovimiento($m_idmovimiento): void {
        $this->m_idmovimiento = $m_idmovimiento;
    }

    public function setM_accion($m_accion): void {
        $this->m_accion = $m_accion;
    }
}
