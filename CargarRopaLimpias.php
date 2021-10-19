<?php
include_once("html/Header.php");

session_start();
if (isset($_SESSION['usuario'])) {
    $usuDTOLogin = $_SESSION['usuario'];
    foreach ($usuDTOLogin->getM_rol() as $rol) {
        if ($rol->getM_id() <= 2) {
            include_once("Barra.php");
            ?>
<form action="CargarRopaLimpias.php" method="get">
    Sala: <select id="sala" name="sala" >
<?php
            foreach(SalasDAO::getAllSalas() as $salas){
                echo "<option value='" . $salas->getM_id() . "'>".$salas->getM_descripcion()."</option>";
            }
?>
    </select>
    Prenda: <select id="prenda" name="prenda" >
<?php
            foreach(PrendaDAO::getHTMLAllPrendas(1) as $prenda){
                echo "<option value='$prenda[0]'>".$prenda[0]."</option>";
            }
?>
    </select>
    cantidad: <input id="cantidad" type="number" min="0" value="0" name="cantidad">
    <input id="agregar" type="submit" value="Agregar">
</form>
<?php
            $salasAll = SalasDAO::getAllSalas();
            foreach ($salasAll as $salas){
                $aux = SalasDAO::getSalaLimpia($salas->getM_id());

                echo "<table class='table table-sm table-striped table-bordered table-hover table-primary' border='1px'><thead class='thead-light'><th colspan='4'>" . $aux->getM_descripcion() . "</th></tr><tr><td>Imagen</td><td>Prenda</td><td>Cantidad</td><td></td></tr>";
                if ($aux->getM_prendas() != null) {
                    foreach ($aux->getM_prendas() as $prenda) {
                        
                        if($prenda->getM_cantidad() > 0){
                            echo "<form action='CargarRopaLimpias.php' method='get'>";
                            echo "<tr><td><img src='" . $prenda->getM_icono() . "' style='width: 80px'></td><td ><h3>" . $prenda->getM_descripcion() . "</h3></td><td><h3>" . $prenda->getM_cantidad() . "</h3></td><td><button id='borrar' class='btn btn-warning btn-sm btn-block' name='prenda' type='submit' value='" . $aux->getM_id() . "p" . $prenda->getM_codigo() . "c".$prenda->getM_cantidad()."'>Borrar</button></tr>";
                            echo "</form>";
                        }
                        else{
                            echo "<form action='saldar.php' method='get'>";
                            echo "<tr><td><img src='" . $prenda->getM_icono() . "' style='width: 80px'></td><td ><h3>" . $prenda->getM_descripcion() . "</h3></td><td><h3>" . $prenda->getM_cantidad() . "</h3></td><td><button id='saldar' type='submit' name='saldar' value='". $aux->getM_id() . "p" . $prenda->getM_codigo() . "c" .$prenda->getM_cantidad()."' class='btn btn-warning btn-sm btn-block'>Saldar</button></tr>";
                            echo "</form>";
                            
                        }
                    }
                }
                echo "</table>";
            }
            
            if(isset($_GET['sala']) && isset($_GET['prenda'])){
                
                $prenda = PrendaDAO::getPrendaFromString($_GET['prenda']);
                $prenda->setM_cantidad($_GET['cantidad']);
                
                //AGREGAR VERIFICACION DEPOSITO
                
                PrendaDAO::addPrendaSalaLimpia($prenda, $_GET['sala']);
                
                echo "<meta http-equiv=\"refresh\" content=\"0;url=https://www.sistemalavaderopp3.ml/CargarRopaLimpias.php\"/>";
            }
            else if (isset($_GET['prenda'])) {
                $prenda = preg_split('/[\d]{1,}p/', $_GET['prenda']);
                $sala = preg_split('/p[\d]{1,}/', $_GET['prenda']);

                $sala = $sala[0];
                $prenda = $prenda[1];
                
                $cantidad = preg_split('/[\d]{1,}c/', $prenda);
                $prenda = preg_split('/c[\d]{1,}/', $prenda);
                SalasDAO::removeSalaPrendaLimpia($sala, $prenda[0], $cantidad[1]);
                echo "<meta http-equiv=\"refresh\" content=\"0;url=https://www.sistemalavaderopp3.ml/CargarRopaLimpias.php\"/>";
            }
        }
    }
}
include_once("html/Footer.php");
?>
