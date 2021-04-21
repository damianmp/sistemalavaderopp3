<?php
    include_once("html/Header.php");
    
    session_start();
    if(isset($_SESSION['usuario'])){
        $usuDTOLogin = $_SESSION['usuario'];
        foreach ($usuDTOLogin->getM_rol() as $rol){
            if($rol->getM_id() == 2){
                $aux = MovimientoDAO::getMovimientos($_GET['id']);
                $movimiento = $aux[0];
                
                $salas_array = MovimientoDAO::getSalaPrendaFromMovimiento($movimiento->getId());
                
                foreach ($salas_array as $salas){
?>
<table border='1px'>
<?php
                    echo "<tr><th colspan='2'>".$salas->getM_descripcion()."</th></tr>";
                    foreach ($salas->getM_prendas() as $prendas){
                        echo "<tr><td><img src='".$prendas->getM_icono()."' style='width: 25px'>".$prendas->getM_descripcion()."</td><td>".$prendas->getM_cantidad()."</td></tr>";
                    }
?>
</table>
<?php
                }
            }
        }   
    }
?>
<a href="index.php">Atrás</a>
<?php
    include_once("html/Footer.php");
?>
    
