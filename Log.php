<?php
include_once("html/Header.php");

session_start();
if (isset($_SESSION['usuario'])) {
    $usuDTOLogin = $_SESSION['usuario'];
    foreach ($usuDTOLogin->getM_rol() as $rol) {
        if ($rol->getM_id() == 1) {
            include_once("Barra.php");
            echo "<table class='table'>";
            $array = LogDAO::getLogs();
            $usuarios = UsuarioDAO::listUsuarios();
            $x = 1;
            $aux = null;
            foreach ($array as $log){
                foreach($usuarios as $usu){
                    if($usu->getM_idusuario() == $log->getM_usuario()){
                        $aux = $usu;
                    }
                }
                echo "<tr><td>".$log->getFecha()."</td><td>".$x++."</td><td>".$aux->getM_nombre()."</td><td>".$log->getM_idmovimiento()."</td><td>".$log->getM_accion()."</td></tr>";
            }
            echo "</table>";
        }
    }
}
include_once("html/Footer.php");
?>