<?php
class Gato extends Prenda{
static private $icon_prenda = 'imagenes/Gato.jpg';
public function __construct() {
$this->setM_icono(self::$icon_prenda);
}
public function __toString() {
return parent::__toString();
}
}