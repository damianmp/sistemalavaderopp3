<?php
class Prenda{
    private $m_codigo;
    private $m_icono;
    private $m_descripcion;
    private $m_cantidad;
    private $m_estado;
    
    public function getM_estado() {
        return $this->m_estado;
    }

    public function setM_estado($m_estado): void {
        $this->m_estado = $m_estado;
    }

    public function getM_cantidad() {
        return $this->m_cantidad;
    }

    public function setM_cantidad($m_cantidad): void {
        $this->m_cantidad = $m_cantidad;
    }

    public function getM_descripcion() {
        return $this->m_descripcion;
    }

    public function setM_descripcion($m_descripcion): void {
        $this->m_descripcion = $m_descripcion;
    }

    public function getM_icono() {
        return $this->m_icono;
    }

    public function setM_icono($m_icono): void {
        $this->m_icono = $m_icono;
    }
    
    public function getM_codigo() {
        return $this->m_codigo;
    }
    
    public function setM_codigo($m_codigo): void {
        $this->m_codigo = $m_codigo;
    }

    public function __toString() {
        return $this->m_codigo." - ". $this->m_cantidad." - <img src='".$this->m_icono."'>";
    }
}
