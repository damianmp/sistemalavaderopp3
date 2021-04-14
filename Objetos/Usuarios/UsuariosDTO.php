<?php
    class UsuarioDTO{
        private $m_idusuario;
        private $m_contrasenia;
        private $m_nombre;
        private $m_apellido;
        private $m_correo;
        private $m_fecha_alta;
        private $m_fecha_baja;
        private $m_rol;
        private $m_ficha_municial;
        
        public function __constructInitComplete($idusuario, $contrasenia, $nombre, $apellido, $correo, $fecha_alta, $fecha_baja, $ficha_municial, $rol) {
            $this->m_idusuario = $idusuario;
            $this->m_contrasenia = $contrasenia;
            $this->m_nombre = $nombre;
            $this->m_apellido = $apellido;
            $this->m_correo = $correo;
            $this->m_fecha_alta = $fecha_alta;
            $this->m_fecha_baja = $fecha_baja;
            $this->m_ficha_municial = $ficha_municial;
            $this->m_rol = $rol;
        }

        public function __construct($ficha_municial, $contrasenia) {
            $this->m_ficha_municial = $ficha_municial;
            $this->m_contrasenia = $contrasenia;
        }
        
        public function getM_Ficha_municial() {
            return $this->m_ficha_municial;
        }

        public function setM_Ficha_municial($ficha_municial): void {
            $this->m_ficha_municial = $ficha_municial;
        }
        
        public function getM_idusuario() {
            return $this->m_idusuario;
        }

        public function getM_contrasenia() {
            return $this->m_contrasenia;
        }

        public function getM_nombre() {
            return $this->m_nombre;
        }

        public function getM_apellido() {
            return $this->m_apellido;
        }

        public function getM_correo() {
            return $this->m_correo;
        }

        public function getM_fecha_alta() {
            return $this->m_fecha_alta;
        }

        public function getM_fecha_baja() {
            return $this->m_fecha_baja;
        }

        public function getM_rol() {
            return $this->m_rol;
        }

        public function setM_idusuario($m_idusuario): void {
            $this->m_idusuario = $m_idusuario;
        }

        public function setM_contrasenia($m_contrasenia): void {
            $this->m_contrasenia = $m_contrasenia;
        }

        public function setM_nombre($m_nombre): void {
            $this->m_nombre = $m_nombre;
        }

        public function setM_apellido($m_apellido): void {
            $this->m_apellido = $m_apellido;
        }

        public function setM_correo($m_correo): void {
            $this->m_correo = $m_correo;
        }

        public function setM_fecha_alta($m_fecha_alta): void {
            $this->m_fecha_alta = $m_fecha_alta;
        }

        public function setM_fecha_baja($m_fecha_baja): void {
            $this->m_fecha_baja = $m_fecha_baja;
        }

        public function setM_rol($m_rol): void {
            $this->m_rol = $m_rol;
        }
    }
?>