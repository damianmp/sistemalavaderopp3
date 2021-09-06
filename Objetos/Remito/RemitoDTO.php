<?php

class RemitoDTO{
    private $id;
    private $id_movimiento;
    
    public function getId() {
        return $this->id;
    }

    public function getId_movimiento() {
        return $this->id_movimiento;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setId_movimiento($id_movimiento): void {
        $this->id_movimiento = $id_movimiento;
    }
}
