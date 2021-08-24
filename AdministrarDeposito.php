<?php
    include_once("html/Header.php");
    
    session_start();
    if(isset($_SESSION['usuario'])){
        $usuDTOLogin = $_SESSION['usuario'];
        foreach ($usuDTOLogin->getM_rol() as $rol){
            if($rol->getM_id() == 2){
                include_once ("Barra.php");
?>
<div class="container-fluid modal-content">
<div class="form-group">
<label for="Salas" class="control-label col-md-7"><h3>Sistema de Administracion del deposito:</h3></label>
</div>
<form class="form-horizontal" action="AdministrarDeposito.php" method="GET">
<div class="form-group ">
<label for="Salas" class="control-label col-md-2">Tipo de prenda</label>
<div class="col-md-5">
<select id="tipoprenda" class="form-control" name="tipoprenda">

		<?php
			$tipoprendas = PrendaDAO::getHTMLAllPrendas();
			foreach ($tipoprendas as $prenda){
				echo "<option value='$prenda[0]'>".$prenda[0]."</option>";
			}
		?>
</select>
</div>
</div>
<div class="form-group ">
<label for="Salas" class="control-label col-md-2">Cantidad</label>
<div class="col-md-2">
<input id="cantidad" class="form-control" type="number" min="0" name="cantidad" value="0">
</div>
</div>
<div class="col-md-2 col.md.offset-2">
<input id="agregar" class="btn btn-primary btn-lg" type="submit" value="Agregar">
</div>
    
</form>
<div class="form-group">
<div class="col-md-2 col.md.offset-2">
<a href="index.php">Atrás</a>
</div>
</div>
<?php
                if(isset($_GET['tipoprenda']) && isset($_GET['cantidad'])){
                    $prenda = PrendaDAO::getPrenda($_GET['tipoprenda']);
                    $prenda->setM_cantidad($_GET['cantidad']);
                    
                    PrendaDAO::addPrendaDeposito($prenda);
                }
?>
<table border='1px'>
    <thead><tr><th>Penda</th><th>Cantidad total</th><th>Limpias</th><th>Sucias</th></tr></thead>
<?php
                $auxMapa = PrendaDAO::getPrendaDeposito();
                foreach($auxMapa->keys() as $auxp){
                    echo "<tr><td><img src='".$auxp->getM_icono()."' style='width: 25px'>".$auxp->getM_descripcion()."</td><td>".$auxp->getM_cantidad()."</td>"
                            . "<td>".($auxp->getM_cantidad() - DepositoDAO::getPrendaSucias($auxp))."</td>"
                            . "<td>".DepositoDAO::getPrendaSucias($auxp)."</td>"
                            . "</tr>";
                }
?>
</table>
</div>
<?php
            }
        }
    }
    include_once("html/Footer.php");
?>