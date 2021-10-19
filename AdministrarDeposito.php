<?php
include_once("html/Header.php");

session_start();
if (isset($_SESSION['usuario'])) {
    $usuDTOLogin = $_SESSION['usuario'];
    foreach ($usuDTOLogin->getM_rol() as $rol) {
        if ($rol->getM_id() <= 2) {
            include_once("Barra.php");
?>
<div class="modal-body">
    <div class="col-sm-10 main-sections">
        <div class="modal-contents">
        <h4>Administrar deposito:</h4>
<table class="table table-sm table-secondary"> 
    <thead>
    <tr>
        <form class="form-horizontal" action="AdministrarDeposito.php" method="GET">
        <td>
            <label for="Salas" class=" col-sm-10">Prenda:</label>
        </td>
        <td>
            <label for="Salas" class=" col-sm-10">Cantidad:</label>
        </td>
       
    </tr>
    </thead>
    <tbody>
        <tr>
            <th>
                <div class="col-sm-10">
                <select id="tipoprenda" class="form-select form-select-sm" name="tipoprenda">

		<?php
            $tipoprendas = PrendaDAO::getHTMLAllPrendas(1);
            foreach ($tipoprendas as $prenda) {
                echo "<option value='$prenda[0]'>" . $prenda[0] . "</option>";
            }
?>
</select>
        </div>
    </th>
            <th>
            <div class="col-sm-12">
                <input id="cantidad" class="form-select-sm"type="number" name="cantidad" value="0">
            </div>
            </th>
            
        </tr>
        <tr>
            <th>
            <div class="d-grid">
                    <a href="index.php" class="btn btn-secondary btn-sm">Atrás</a>
                </div>
            </th>
            <th>
                <div class="d-grid">
                    <input id="agregar" class="btn btn-primary btn-sm" type="submit" value="Agregar">
                </div>
            </th>
        </tr>
    </tbody>
</form>

</table>
</div>
    </div>
</div>
<div class="modal-body">
    <div class="col-sm-10 main-sections">
        <div class="modal-contents">
<?php
            if (isset($_GET['tipoprenda']) && isset($_GET['cantidad'])) {
                $prenda = PrendaDAO::getPrenda($_GET['tipoprenda']);
                $prenda->setM_cantidad($_GET['cantidad']);

                PrendaDAO::addPrendaDeposito($prenda);
            }
?>
<table class="table table-sm table-primary ">
<thead>
        <tr>Deposito</tr>
    <tr>
        <td>Prendas</td><td>Total</td><td>Disponible</td><td>En lavadero</td><td>Deposito ropa sucia</td><td>Area de servicio</td>
    </tr>
    </thead>
    <tbody>
<?php
            $auxMapa = PrendaDAO::getPrendaDeposito();
            foreach ($auxMapa->keys() as $auxp) {
                echo "<tr><td><img src='" . $auxp->getM_icono() . "' style='width: 25px'>" . $auxp->getM_descripcion() . "</td>"
                    . "<td>" . $auxp->getM_cantidad() . "</td>"
                    . "<td>" . ($auxp->getM_cantidad() - DepositoDAO::getPrendaSucias($auxp)) . "</td>"
                    . "<td>" . DepositoDAO::getPrendaSucias($auxp) . "</td>"
                    . "<td>" . (DepositoDAO::getPrendasSinEnviar($auxp) == 0 ? 0 : DepositoDAO::getPrendasSinEnviar($auxp)) . "</td>";
                
                $salaslimpias = DepositoDAO::getPrendaLimpias($auxp);
                foreach ($salaslimpias as $auxSala){
                    echo "<tr><td>".$auxSala->getM_descripcion()."</td><td>-</td><td>-</td><td>-</td><td>-</td>";
                    $total = 0;
                    $sumprenda = 0;
                    
                    foreach($auxSala->getM_prendas() as $auxprendas){

                        if($auxprendas->getM_codigo() == $auxp->getM_codigo()){
                            if($auxprendas->getM_cantidad() >= 0){
                                $total = $auxprendas->getM_cantidad();
                            }
                            else{   
                                $sumprenda += $auxprendas->getM_cantidad();
                            }
                        }
                    }
                    echo "<td>".$total."(<a style='color: red;'>".$sumprenda."</a>)</td>";
                    echo "</tr>";
                }
                echo "</tr>";
            }
?>
    </tbody>
</table>
<?php
        }
    }
}
include_once("html/Footer.php");
?>
      </div>
    </div>
</div>