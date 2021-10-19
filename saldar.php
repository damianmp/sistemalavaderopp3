<?php
include_once("html/Header.php");

session_start();
if (isset($_SESSION['usuario'])) {
    $usuDTOLogin = $_SESSION['usuario'];
    foreach ($usuDTOLogin->getM_rol() as $rol) {
        if ($rol->getM_id() <= 2) {
            if(isset($_GET['saldar'])){
                $contenido = $_GET['saldar'];
                //echo $contenido;

                $prenda = preg_split('/[\d]{1,}p/', $contenido);
                $sala = preg_split('/p[\d]{1,}/', $contenido);

                $sala = $sala[0];
                $prenda = $prenda[1];

                $cantidad = preg_split('/[\d]{1,}c/', $prenda);
                $prenda = preg_split('/c/', $prenda);
            }
            //echo "sala: ".$sala." prenda: ".$prenda[0]." cantidad: ".$cantidad[1];
            if(!isset($_POST['si']) || !isset($_POST['no'])){
            ?>
                <form action="saldar.php" method="post">
                    ¿Estas seguro que decea saldar la prendas deudoras?
                    <button type="submit" name="si">Si</button>
                    <button type="submit" name="no">No</button>
                    <input type="text" name="sala" value="<?php echo $sala; ?>" hidden>
                    <input type="text" name="prenda" value="<?php echo $prenda[0]; ?>" hidden>
                    <input type="text" name="cantidad" value="<?php echo $cantidad[1]; ?>" hidden>
                </form>
            <?php
            }
            if(isset($_POST['si'])){    
                PrendaDAO::removePrendasDebe($_POST['sala'], $_POST['prenda'], $_POST['cantidad']);
                echo "<meta http-equiv=\"refresh\" content=\"0;url=https://www.sistemalavaderopp3.ml/CargarRopaLimpias.php\"/>";
            }
            else if(isset($_POST['no'])){
                echo "<meta http-equiv=\"refresh\" content=\"0;url=https://www.sistemalavaderopp3.ml/CargarRopaLimpias.php\"/>";
            }
        }
    }
}
include_once("html/Footer.php");
?>