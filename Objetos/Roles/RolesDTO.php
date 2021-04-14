<?php
    class RolesDTO{
        private $m_id;
        private $m_descripcion;

        public function __construct($id, $descripcion) {
            $this->m_id = $id;
            $this->m_descripcion = $descripcion;
        }
        
        public function getM_id() {
            return $this->m_id;
        }

        public function getM_descripcion() {
            return $this->m_descripcion;
        }

        public function setM_id($m_id): void {
            $this->m_id = $m_id;
        }

        public function setM_descripcion($m_descripcion): void {
            $this->m_descripcion = $m_descripcion;
        }
    }
?>