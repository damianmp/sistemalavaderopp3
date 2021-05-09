<?php
    include_once("html/Header.php");
    
    session_start();
    if(isset($_SESSION['usuario'])){
        $usuDTOLogin = $_SESSION['usuario'];
        foreach ($usuDTOLogin->getM_rol() as $rol){
            if($rol->getM_id() == 2){
?>
<h3>Crear nuevo tipo de prenda:</h3>
<form action="CrearPrenda.php" method="POST" enctype="multipart/form-data">
    Nombre de la prenda: <input name="nombre"></input><br>
    Imagen: <input type="file" name="imagen"><br>
    Codigo: <input type="number" name="codigo" min="1">
    <input type="submit" name="submit" value="Agregar">
</form>
<a href="index.php">Atrás</a>
<?php
                if(isset($_POST['submit']) && isset($_POST['codigo']))
                {
                    if(isset($_POST['nombre']) && strlen($_POST['nombre']) > 2){
                        //var_dump($_FILES['imagen']);
                        if(PrendaDAO::getPrenda($_POST['nombre']) == null){
                            $carpeta = "imagenes/". basename($_FILES['imagen']['tmp_name']);
                            if(move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta)){
                                rename($carpeta, "imagenes/".$_FILES['imagen']['name']);
                                $carpeta = "imagenes/".$_FILES['imagen']['name'];

                                $archivo = pathinfo($carpeta);
                                $extension = $archivo['extension'];

                                rename($carpeta, "imagenes/".$_POST['nombre'].".".$extension);
                                if(!strcmp($extension, "jpg")){
                                    $archivo = fopen("Objetos/Prendas/DTO/".$_POST['nombre'].".php", "wr");

                                    $txt = "<?php\nclass ".$_POST['nombre']." extends Prenda{\nstatic private \$icon_prenda = 'imagenes/".$_POST['nombre'].".".$extension."';\npublic function __construct() {\n\$this->setM_icono(self::\$icon_prenda);\n}\npublic function __toString() {\nreturn parent::__toString();\n}\n}";

                                    fwrite($archivo, $txt);
                                    fclose($archivo);

                                    $prenda = new Prenda();
                                    $prenda->setM_codigo($_POST['codigo']);
                                    $prenda->setM_descripcion($_POST['nombre']);

                                    if(!PrendaDAO::addPrenda($prenda)){
                                        echo "<a style='color: red;'>Error! Codigo ya existente.</a>";
                                        unlink("imagenes/".$_POST['nombre'].".".$extension);
                                        unlink("Objetos/Prendas/DTO/".$_POST['nombre'].".php");
                                    }
                                    else{
                                        echo "Exito! la prenda ha sido creada";
                                    }
                                }
                                else{
                                    echo "<a style='color: red;'>Error! imagen no compatible.</a>";
                                    unlink("imagenes/".$_POST['nombre'].".".$extension);
                                }
                            }
                        }
                        else{
                            echo "<a style='color: red;'>Error! nombre de la prenda ya ha sido creada.</a>"; 
                        }
                    }
                    else{
                        echo "<a style='color: red;'>Error! nombre vacio.</a>";  
                    }
                }
            }
        }
    }
    include_once("html/Footer.php");
?>
