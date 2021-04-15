<?php
    include_once("html/Header.php");
    
    session_start();
    if(isset($_SESSION['usuario'])){
         $usuDTOLogin = $_SESSION['usuario'];
         foreach ($usuDTOLogin->getM_rol() as $rol){
                if($rol->getM_id() == 2){
                    if(!isset($_SESSION['id_mov'])){
                        $_SESSION['id_mov'] = hash("md5", rand(-9999999999999999, 9999999999999999));
                    }
?>
Sistema de carga de ropa sucia:
<form action="CargarRopaSucia.php" method="get">
    Tipo de prenda <select name="tipoprenda">
<?php
                    $tipoprendas = PrendaDAO::getHTMLAllPrendas();
                    foreach ($tipoprendas as $prenda){
                        echo "<option value='$prenda[0]'>".$prenda[0]."</option>";
                    }
?>
</select>
<br>Cantidad <input type="number" min="0" name="cantidad">
<br>Salas <select name="sala">
<?php
                    $salaI = SalasDAO::getAllSalas();
                    foreach ($salaI as $aux){
                        echo "<option value='".$aux->getM_id()."'>".$aux->getM_Descripcion()."</option>";
                    }
?>
</select>
<input type="submit" value="Agregar">
</form>
<a href="index.php">Atrás</a> 
<?php
                if(isset($_GET['prenda'])){
                    $prenda = preg_split('/[\d]{1,}p/', $_GET['prenda']);
                    $sala = preg_split('/p[\d]{1,}/', $_GET['prenda']);
                    
                    $sala = $sala[0];
                    $prenda = $prenda[1];
                    
                    SalasDAO::removeSalaPrenda($sala, $prenda, $_SESSION['id_mov']);
                }
                if(isset($_GET['tipoprenda']) && isset($_GET['cantidad']) && isset($_GET['sala'])){
                    $prenda = PrendaDAO::getPrendaFromString($_GET['tipoprenda']);
                    $prenda->setM_cantidad($_GET['cantidad']);
                    
                    $sala = SalasDAO::getSala($_GET['sala'], $_SESSION['id_mov']);
                    
                    SalasDAO::addSala($sala, $prenda, $_SESSION['id_mov']);
                }
                    
                foreach($salaI as $aux){
?>
<form action="CargarRopaSucia.php" method="get">
<?php
                    echo "<table border='1px'><tr><th colspan='3'>".$aux->getM_descripcion()."</th></tr>";
                    $aux = SalasDAO::getSala($aux->getM_id(), $_SESSION['id_mov']);
                    if($aux->getM_prendas() != null){
                        foreach ($aux->getM_prendas() as $prenda){
                            echo "<tr><td><img src='".$prenda->getM_icono()."' style='width: 25px'>".$prenda->getM_descripcion()."</td><td>".$prenda->getM_cantidad()."</td><td><button name='prenda' type='submit' value='".$aux->getM_id()."p".$prenda->getM_codigo()."'>Borrar</button></tr>";
                        }
                    }
                    echo "</table>";
?>
</form>
<?php
                }
?>
<table border='1px'>
<tr><th colspan="2">Total</th></tr>
<?php
                $total = 0;
                $allprendasstr = PrendaDAO::getHTMLAllPrendas();
                foreach($allprendasstr as $prendastr){
                    $objprenda = PrendaDAO::getPrendaFromString($prendastr[0]);
                    $objprenda->setM_cantidad(PrendaDAO::getCountPrenda($objprenda->getM_codigo(), $_SESSION['id_mov']));
                    echo "<tr><td><img src='".$objprenda->getM_icono()."' style='width: 25px'>".$objprenda->getM_descripcion()."</td><td>".$objprenda->getM_cantidad()."</td></tr>";
                    
                    $total += $objprenda->getM_cantidad();
                }
                echo "<tr><td style='background-color: red'>Total de prendas</td><td style='background-color: red'>".$total."</td></tr>";
?>
</table>
<?php
            }
        }
     }
    include_once("html/Footer.php");
?>
