<?php
    include_once("html/Header.php");
    session_start();
    if(isset($_SESSION['usuario'])){
         $usuDTOLogin = $_SESSION['usuario'];
         foreach ($usuDTOLogin->getM_rol() as $rol){
                //if($rol->getM_id() == 2){
                if(isset($_SESSION['mapa'])){
                    $mapa = $_SESSION['mapa'];
                    DepositoDAO::RefreshDeposito($mapa);
                    MovimientoDAO::deleteMovimiento($_POST['id']);
                    $formato = "El usuario ".$usuDTOLogin->getM_nombre()."(".$usuDTOLogin->getM_Ficha_municial().") recibio el paquete con la id ".$_POST['id'];
                    LogDAO::addLog(new LogDTO($usuDTOLogin->getM_idusuario(), $_POST['id'], $formato));
                    
                    unset($_SESSION['mapa']);
                    echo "Espere por favor...";
                    echo "<meta http-equiv=\"refresh\" content=\"2;url=https://www.sistemalavaderopp3.ml/\"/>";
                //}
                }
         }
    }
    include_once("html/Footer.php");
?>