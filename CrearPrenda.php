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
    <h4>Crear nuevo tipo de prenda:</h4>
    <table class="table table-sm table-secondary"> 
<thead>
<tr>
<form action="CrearPrenda.php" method="POST" enctype="multipart/form-data">
        <td>
            <div class="col-sm-8" >
                <label for="text" class="form-label">Nombre de la prenda:</label>
            </div>
        </td>
        <td>
            <div class="col-sm-4">
                <label for="text" class="form-label">Imagen:</label>
            </div>
        </td>
        <td>
            <div class="col-sm-4">
                <label for="text" class="form-label">Codigo:</label>
            </div>
        </td>
</tr>
</thead>
<tbody>
    <tr>
        <td>
        <div class="col-sm-4">
            <input  class="form-select-sm" name="nombre"></input>
        </div>
        </td>
        <td>
        <div class="col-sm-4">
            <input type="file"  class="form-select-sm" name="imagen">
        </div>
        </td>
        <td>
        <div class="col-sm-4">
            <input type="number" class="form-select-sm" name="codigo" min="1">
        </div>
        </td>
    </tr>
        <tr>
        <td>
                <div class="d-grid">
                    <a href="index.php" class="btn btn-secondary btn-sm">Atrás</a>
                </div>
            </td>
            <td></td>
           
            <td>
                <div div class="d-grid">
                    <input type="submit" class="btn btn-primary btn-sm" name="submit" value="Agregar">
                    </div>
            </td>
        </tr>
</tbody>
</form>
</table>
        </div>
    </div>
</div>
<div class="modal-body">
    <div class="col-sm-3 main-sections ">
        <div class="modal-contents">
<h4>Eliminar prendas:</h4>
<form  id='eliminarPrendas' action="CrearPrenda.php" method="GET">
<table>
<?php
            $tipoprendas = PrendaDAO::getHTMLAllPrendas(1);
            foreach ($tipoprendas as $prenda) {
                $preda_aux = PrendaDAO::getPrenda($prenda[0]);
                echo "<tr><td><img src='" . $preda_aux->getM_icono() . "' style='width: 25px'>" . $preda_aux->getM_descripcion() . "</td><td><button id='borrar' class='btn btn-warning btn-sm btn-block' name='bajaprenda' type='submit' value='" . $preda_aux->getM_descripcion() . "'>Eliminar</button></td></tr>";
            }
?>
</table>
</form>
</div>
    </div>
</div>
<div class="modal-body">
    <div class="col-sm-3 main-sections ">
        <div class="modal-contents">
<h4>Alta Prendas:</h4>
<form  id='altaPrendas' action="CrearPrenda.php" method="GET">
<table>
<?php
            $tipoprendas = PrendaDAO::getHTMLAllPrendas(0);
            foreach ($tipoprendas as $prenda) {
                $preda_aux = PrendaDAO::getPrenda($prenda[0]);
                echo "<tr><td><img src='" . $preda_aux->getM_icono() . "' style='width: 25px'>" . $preda_aux->getM_descripcion() . "</td><td><button id='borrar' class='btn btn-warning btn-sm btn-block' name='altaprenda' type='submit' value='" . $preda_aux->getM_descripcion() . "'>Alta</button></td></tr>";
            }
?>
</table>
</form>
<?php
            if (isset($_GET['bajaprenda'])) {
                PrendaDAO::ABPrenda($_GET['bajaprenda'], 0);
                echo "<meta http-equiv=\"refresh\" content=\"0;url=https://www.sistemalavaderopp3.ml/CrearPrenda.php\"/>";
            }
            if (isset($_GET['altaprenda'])) {
                PrendaDAO::ABPrenda($_GET['altaprenda'], 1);
                echo "<meta http-equiv=\"refresh\" content=\"0;url=https://www.sistemalavaderopp3.ml/CrearPrenda.php\"/>";
            }
            if (isset($_POST['submit']) && isset($_POST['codigo'])) {
                if (isset($_POST['nombre']) && strlen($_POST['nombre']) > 2) {
                    //var_dump($_FILES['imagen']);

                    $_POST['nombre'] = PrendaDAO::fixNombrePrenda($_POST['nombre']);

                    if (PrendaDAO::getPrenda($_POST['nombre']) == null) {
                        $carpeta = "imagenes/" . basename($_FILES['imagen']['tmp_name']);
                        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta)) {
                            rename($carpeta, "imagenes/" . $_FILES['imagen']['name']);
                            $carpeta = "imagenes/" . $_FILES['imagen']['name'];

                            $archivo = pathinfo($carpeta);
                            $extension = $archivo['extension'];

                            rename($carpeta, "imagenes/" . $_POST['nombre'] . "." . $extension);
                            if (!strcmp($extension, "jpg")) {
                                $archivo = fopen("Objetos/Prendas/DTO/" . $_POST['nombre'] . ".php", "wr");

                                $txt = "<?php\nclass " . $_POST['nombre'] . " extends Prenda{\nstatic private \$icon_prenda = 'imagenes/" . $_POST['nombre'] . "." . $extension . "';\npublic function __construct() {\n\$this->setM_icono(self::\$icon_prenda);\n}\npublic function __toString() {\nreturn parent::__toString();\n}\n}";

                                fwrite($archivo, $txt);
                                fclose($archivo);

                                $prenda = new Prenda();
                                $prenda->setM_codigo($_POST['codigo']);
                                $prenda->setM_descripcion($_POST['nombre']);

                                if (!PrendaDAO::addPrenda($prenda)) {
                                    echo "<a style='color: red;'>Error! Codigo ya existente.</a>";
                                    unlink("imagenes/" . $_POST['nombre'] . "." . $extension);
                                    unlink("Objetos/Prendas/DTO/" . $_POST['nombre'] . ".php");
                                }
                                else {
                                    echo "Exito! la prenda ha sido creada";
                                    echo "<meta http-equiv=\"refresh\" content=\"0;url=https://www.sistemalavaderopp3.ml/CrearPrenda.php\"/>";
                                }
                            }
                            else {
                                echo "<a style='color: red;'>Error! imagen no compatible.</a>";
                                unlink("imagenes/" . PrendaDAO::fixNombrePrenda($_POST['nombre']) . "." . $extension);
                            }
                        }
                    }
                    else {
                        echo "<a style='color: red;'>Error! nombre de la prenda ya ha sido creada.</a>";
                    }
                }
                else {
                    echo "<a style='color: red;'>Error! nombre vacio.</a>";
                }
            }
        }
    }
}
?>
</div>
<?php
include_once("html/Footer.php");
?>
       </div>
    </div>
</div>
