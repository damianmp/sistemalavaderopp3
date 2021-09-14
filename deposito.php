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
            <h4>Movimiento ropa hospitalaria:</h4>
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
    <td>Prendas</td><td>Cantidad Ingresada</td><td>Saldo</td><td>Lavadero(Tex-care)</td><td>AreaSucia</td>
    </tr>
    </thead>
    <tbody>
<?php
            $auxMapa = PrendaDAO::getPrendaDeposito();
            foreach ($auxMapa->keys() as $auxp) {
                echo "<tr><td><img src='" . $auxp->getM_icono() . "' style='width: 25px'>" . $auxp->getM_descripcion() . "</td><td>" . $auxp->getM_cantidad() . "</td>"
                    . "<td>" . ($auxp->getM_cantidad() - DepositoDAO::getPrendaSucias($auxp)) . "</td>"
                    . "<td>" . DepositoDAO::getPrendaSucias($auxp) . "</td>"
                    . "<td>" . (DepositoDAO::getPrendasSinEnviar($auxp) == 0 ? 0 : DepositoDAO::getPrendasSinEnviar($auxp)) . "</td>"
                    . "</tr>";
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