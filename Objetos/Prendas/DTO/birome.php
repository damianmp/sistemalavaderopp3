<?php
class birome extends Prenda{
static private $icon_prenda = 'imagenes/birome.jpg';
public function __construct() {
$this->setM_icono(self::$icon_prenda);
}
public function __toString() {
return parent::__toString();
}
}