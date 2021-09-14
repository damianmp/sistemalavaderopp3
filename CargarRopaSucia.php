<?php
include_once("html/Header.php");

session_start();
if (isset($_SESSION['usuario'])) {
    $usuDTOLogin = $_SESSION['usuario'];
    foreach ($usuDTOLogin->getM_rol() as $rol) {
        if ($rol->getM_id() <= 2) {
            include_once("Barra.php");
            //if(!isset($_SESSION['id_mov'])){
            if (SalasDAO::getUsuarioPrendaSalas($usuDTOLogin) == null) {
                $_SESSION['id_mov'] = hash("md5", (rand(-9999999999999999, 9999999999999999)));
                SalasDAO::addUsuarioPrendaSalas($usuDTOLogin, $_SESSION['id_mov']);
            }
            else {
                $_SESSION['id_mov'] = SalasDAO::getUsuarioPrendaSalas($usuDTOLogin);
            }
?>
<br>
<div class="modal-body">
    <div class="col-sm-10 main-sections">
        <div class="modal-contents">
        <h4> Cargar ropa sucia:</h4>
        <table class="table table-sm table-secondary" >
            
                <tr>
            
                    <form class="form-horizontal" action="CargarRopaSucia.php" method="get">
                    <td scope="row">
                        <label for="Salas" class=" col-sm-12">Salas:</label>
                     </td>
                        <td scope="row">
                
                            <label for="Tipo de prenda" class=" col-sm-12">Prendas:</label>
        
                        </td>
                    <td scope="row">
                        <label for="Cantidad" class=" col-sm-8">Cantidad:</label>
                     </td>
                    
        </tr>
            
          
        <tr>
            <td>
                <div class="col-sm-12">
                    <select id="sala" class="form-select form-select-sm" name="sala" >
                    <?php
            $salaI = SalasDAO::getAllSalas();
            foreach ($salaI as $aux) {
                echo "<option value='" . $aux->getM_id() . "' " . (isset($_GET['sala']) && $_GET['sala'] == $aux->getM_id() ? "selected" : "") . ">" . $aux->getM_Descripcion() . "</option>";
            }
?>
               </select>
                </div>
            </td>
       
        <td>
                    <div class="col-sm-12">
                        <select id="tipoprenda" class="form-select form-select-sm" name="tipoprenda">
                    <?php
            $tipoprendas = PrendaDAO::getHTMLAllPrendas(1);
            foreach ($tipoprendas as $prenda) {
                echo "<option value='$prenda[0]'>" . $prenda[0] . "</option>";
            }
?>
  </select>
                    </div>
        </td>
        <td>
                    <div class="col-sm-9">
                        <input id="cantidad" class=" form-select-sm" type="number" min="0" value="0" name="cantidad">
                    </div>
        </td>
        </tr>
        <tr>
            <td></td>
            <td>
                    <div class="d-grid">
                        <a href="index.php" class="btn btn-secondary btn-sm">Atrás</a>
                    </div>
                </td>
   
                <td >
                    
                    <div class="d-grid">
                    <input id="agregar" class="btn btn-primary btn-sm" type="submit" value="Agregar">
                    </div>
                </td>
            </tr>
                
            </form>
    
</table>
</div>
</div>
</div>

<br>
<div class="modal-body">
    <div class="col-sm-10 main-sections">
        <div class="modal-contents">
            <?php
            if (isset($_GET['prenda'])) {
                $prenda = preg_split('/[\d]{1,}p/', $_GET['prenda']);
                $sala = preg_split('/p[\d]{1,}/', $_GET['prenda']);

                $sala = $sala[0];
                $prenda = $prenda[1];

                SalasDAO::removeSalaPrenda($sala, $prenda, $_SESSION['id_mov']);
            }
            if (isset($_GET['tipoprenda']) && isset($_GET['cantidad']) && isset($_GET['sala'])) {
                $prenda = PrendaDAO::getPrendaFromString($_GET['tipoprenda']);
                $prenda->setM_cantidad($_GET['cantidad']);

                $sala = SalasDAO::getSala($_GET['sala'], $_SESSION['id_mov']);

                //verifico que lo que lo que se quiera agregar no sobrepase lo que hay en el deposito
                if (PrendaDAO::isOutboundPrendaDeposito($prenda, $_SESSION['id_mov'])) {
                    echo "<a style='color: red;'>Error! cantidad de prendas a agregar invalida</a>";
                }
                else {
                    SalasDAO::addSala($sala, $prenda, $_SESSION['id_mov']);
                }
            }

            foreach ($salaI as $aux) {
?>
        <form id="cargarRopaSucia" action="CargarRopaSucia.php" method="get">
            
            
            
            
            
            <?php
                echo "<table class='table table-sm table-striped table-bordered table-hover table-primary' border='1px'><thead class='thead-light'><th colspan='4'>" . $aux->getM_descripcion() . "</th></tr><tr><td>Imagen</td><td>Prenda</td><td>Cantidad</td><td></td></tr>";
                $aux = SalasDAO::getSala($aux->getM_id(), $_SESSION['id_mov']);
                if ($aux->getM_prendas() != null) {
                    foreach ($aux->getM_prendas() as $prenda) {
                        echo "<tr><td><img src='" . $prenda->getM_icono() . "' style='width: 80px'></td><td ><h3>" . $prenda->getM_descripcion() . "</h3></td><td><h3>" . $prenda->getM_cantidad() . "</h3></td><td><button id='borrar' class='btn btn-warning btn-sm btn-block' name='prenda' type='submit' value='" . $aux->getM_id() . "p" . $prenda->getM_codigo() . "'>Borrar</button></tr>";
                    }
                }
                echo "</table>";
?>
        </form>
    <?php
            }
?>    

<br>
        <?php
            echo "<table id='tablaTotal' class='table table-sm table-striped table-bordered table-hover table-primary' border='1px'><thead class='thead-light'><tr class='info'><th colspan='3'>Total</th></tr><tr><td>Imagen</td><td>Prenda</td><td>Cantidad</td></tr><tr>";
            $total = 0;
            $allprendasstr = PrendaDAO::getHTMLAllPrendas(1);
            foreach ($allprendasstr as $prendastr) {
                $objprenda = PrendaDAO::getPrendaFromString($prendastr[0]);
                $objprenda->setM_cantidad(PrendaDAO::getCountPrenda($objprenda->getM_codigo(), $_SESSION['id_mov']));
                echo "<tr><td><img src='" . $objprenda->getM_icono() . "' style='width: 80px'></td><td><h3>" . $objprenda->getM_descripcion() . "</h3></td><td><h3>" . $objprenda->getM_cantidad() . "</h3></td></tr>";

                $total += $objprenda->getM_cantidad();
            }
            $_SESSION['total'] = $total;
            echo "</table>";
?>
        <form action="ProcesarRopaSucia.php" method="POST">
        <div class="d-grid">
            <input id="procesar" class="btn btn-success btn-lg" type="submit" value="Procesar">
        </div>
        </form>
</div>
    <?php
        }
    }
}
include_once("html/Footer.php");
?>
        </div>
    </div>
</div>
