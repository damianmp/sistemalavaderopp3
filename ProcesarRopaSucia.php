<?php
    include_once("html/Header.php");
    session_start();
    if(isset($_SESSION['usuario'])){
         $usuDTOLogin = $_SESSION['usuario'];
         foreach ($usuDTOLogin->getM_rol() as $rol){
                if($rol->getM_id() <= 2){
                    //agregar movimiento a los pendientes para su devolucion
                    MovimientoDAO::addMovimento($usuDTOLogin, $_SESSION['total'], $_SESSION['id_mov']);
                    SalasDAO::removeUsuarioPrendaSalas($usuDTOLogin);
                    
                    $formato = "Se agrego como entregado al lavadero";
                    LogDAO::addLog(new LogDTO($usuDTOLogin->getM_idusuario(), $_SESSION['id_mov'], $formato));
                    
                    unset($_SESSION['id_mov']);
                    echo "Espere por favor...";
                    echo "<meta http-equiv=\"refresh\" content=\"2;url=https://www.sistemalavaderopp3.ml/\"/>";
                }
         }
    }
    include_once("html/Footer.php");
?>