<?php
class CasacaMedico extends Prenda{
static private $icon_prenda = 'imagenes/CasacaMedico.jpg';
public function __construct() {
$this->setM_icono(self::$icon_prenda);
}
public function __toString() {
return parent::__toString();
}
}