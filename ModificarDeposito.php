<?php
    include_once("html/Header.php");
    session_start();
    if(isset($_SESSION['usuario'])){
         $usuDTOLogin = $_SESSION['usuario'];
         foreach ($usuDTOLogin->getM_rol() as $rol){
                if($rol->getM_id() == 2){
                    $mapa = $_SESSION['mapa'];
                    DepositoDAO::RefreshDeposito($mapa);
                    MovimientoDAO::deleteMovimiento($_POST['id']);
                    unset($_SESSION['mapa']);
                    echo "Espere por favor...";
                    echo "<meta http-equiv=\"refresh\" content=\"2;url=https://www.sistemalavaderopp3.ml/\"/>";
                }
         }
    }
    include_once("html/Footer.php");
?>