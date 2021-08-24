<?php
    include_once("html/Header.php");
    
    session_start();
    if(isset($_SESSION['usuario'])){
        $usuDTOLogin = $_SESSION['usuario'];
        foreach ($usuDTOLogin->getM_rol() as $rol){
            if($rol->getM_id() == 2){
                include_once("Barra.php");
                ?>
<div class="container-fluid modal-content">
<?php
                $aux = MovimientoDAO::getMovimientos($_GET['id']);
                $movimiento = $aux[0];
                
                echo "<b>ID del Movimiento: </b>".$_GET['id']."<br><b> Fecha del paquete hecho: </b>".$movimiento->getFecha();
                
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
                ?>
<br>
<b>Prendas en devueltas:</b>
<form action="ConfirmarPaquete.php" method="POST">
    <?php
                $array_prendas = PrendaDAO::getHTMLAllPrendas();
                echo "<table>";
                foreach($array_prendas as $prendas){
                    $aux = PrendaDAO::getPrendaFromString($prendas[0]);
                    //var_dump($aux);
                    if(PrendaDAO::isPrendaInDepositoFromMovimiento($aux, $movimiento->getId())){
                        echo "<tr><td><img src='".$aux->getM_icono()."' style='width: 25px'>".$aux->getM_descripcion().""
                                . "<input name='".$aux->getM_descripcion()."' required='required'/>"
                                . "</td></tr>";
                    }
                }
                echo "<input name='id' value='".$_GET['id']."' hidden></table>";
    ?>
    <input type="submit" value="Levantar paquete"/>
</form>

                <?php
            }
        }   
    }
?>
<a href="index.php">Atrás</a>
</div>
<?php
    include_once("html/Footer.php");
?>
    
