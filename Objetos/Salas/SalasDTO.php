<?php

class SalasDTO{
    private $m_id;
    private $m_descripcion;
    private $m_prendas;
    
    public function getM_id() {
        return $this->m_id;
    }

    public function getM_descripcion() {
        return $this->m_descripcion;
    }

    public function getM_prendas() {
        return $this->m_prendas;
    }

    public function setM_id($m_id): void {
        $this->m_id = $m_id;
    }

    public function setM_descripcion($m_descripcion): void {
        $this->m_descripcion = $m_descripcion;
    }

    public function setM_prendas($m_prendas): void {
        $this->m_prendas = $m_prendas;
    }
    
    public function __toString() {
        //$txt = $this->m_id;
        //$txt = $txt. " - ". $this->m_descripcion;
        
        /*foreach ($this->m_prendas as $aux){
            $txt = $txt." - ".$aux;
        }
        */
        return $this->m_id. " - ". $this->m_descripcion;
    }
}

