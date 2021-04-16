<?php

class MovimientoDTO{
    private $id_user;
    private $id;
    private $total;
    private $fecha;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function getId_user() {
        return $this->id_user;
    }

    public function setId_user($id_user): void {
        $this->id_user = $id_user;
    }
        
    public function getTotal() {
        return $this->total;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setTotal($total): void {
        $this->total = $total;
    }

    public function setFecha($fecha): void {
        $this->fecha = $fecha;
    }
    
    public function __toString() {
        return $this->id. " - ".$this->id_user." - ".$this->total." - ".$this->fecha;
    }
}
