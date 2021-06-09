<?php
    include_once("html/Header.php");
    
    session_start();
    if(isset($_SESSION['usuario'])){
        $usuDTOLogin = $_SESSION['usuario'];
        foreach ($usuDTOLogin->getM_rol() as $rol){
            if($rol->getM_id() == 2){
?>
<h3>Sistema de Administracion del deposito:</h3>
<form action="AdministrarDeposito.php" method="GET">
<br>Tipo de prenda <select id="tipoprenda" name="tipoprenda">
<?php
                $tipoprendas = PrendaDAO::getHTMLAllPrendas();
                foreach ($tipoprendas as $prenda){
                    echo "<option value='$prenda[0]'>".$prenda[0]."</option>";
                }
?>
</select>
<br>Cantidad <input id="cantidad" type="number" min="0" name="cantidad" value="0">
<input id="agregar" type="submit" value="Agregar">
</form>
<a href="index.php">Atrás</a>
<?php
                if(isset($_GET['tipoprenda']) && isset($_GET['cantidad'])){
                    $prenda = PrendaDAO::getPrenda($_GET['tipoprenda']);
                    $prenda->setM_cantidad($_GET['cantidad']);
                    
                    PrendaDAO::addPrendaDeposito($prenda);
                }
?>
<table border='1px'>
<?php
                $auxMapa = PrendaDAO::getPrendaDeposito();
                foreach($auxMapa->keys() as $auxp){
                    echo "<tr><td><img src='".$auxp->getM_icono()."' style='width: 25px'>".$auxp->getM_descripcion()."</td><td>".$auxp->getM_cantidad()."</td></tr>";
                }
?>
</table>
<?php
            }
        }
    }
    include_once("html/Footer.php");
?>