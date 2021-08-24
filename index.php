<?php
    include_once("html/Header.php");

    session_start();
    if(!isset($_SESSION['usuario'])){
?>
<div class="modal-dialog text-center">
    <div class="col-sm-8 main-section">
        <div class="modal-content">
            <div class="col-12 user-img">
                <img src="imagenes/user.png">
            </div>
            <form class="col-12" action="Formulario.php" method="post">
                <div class="form-group" id="user-group">
                     <input type="text" name="ficha" placeholder="Ingrese ficha"></td>
                </div>
                <div class="form-group" id="contrasena-group" >
                    <input type="password" name="contrasenia" placeholder="contrasenia">
                </div> 
                <button type="submit" class="btn btn-primary" value="Ingresar"><i class="fas fa-sign-in-alt"></i> Ingresar</button>
                <div class="col-12 forgot">
                <a href="contrasenia.php">¿Olvidaste la contraseña?</a>
                </div> 
            </form>
        </div>
    </div>
</div>
<?php
    }
    else{
        $usuDTOLogin = $_SESSION['usuario'];
        if($usuDTOLogin->getM_rol()->count() > 0){
            foreach ($usuDTOLogin->getM_rol() as $rol){
                if($rol->getM_id() == 2){
                    include_once ("Barra.php");
                    ?>
                    <div class="container-fluid modal-content">
                    <div class="modal-dialog text-center">
                    <div class="row">
                    <div class="col-md-3">
                    <img src="imagenes/S.G.S.R.H.gif" class="img-responsive">
                    </div>
                    </div>
                    </div>
                    <?php
                    echo "<br>Todos los paquetes hechos:";
                    $array = MovimientoDAO::getMovimientos("*");
                    foreach ($array as $movimientos){
                        echo "<br><a href='EditarPaquete.php?id=".$movimientos->getId()."'>".$movimientos."</a>";
                    }
                    ?>
                        <div style="margin-top: 100px;text-align: center;font-style: italic;font-size: 10px;">
                            Trabajo practico hecho por: Diego Pardo, Damian Elias Molina Ponce, Gerardo Aponte y Ramiro Claros
                        </div>
                    </div>
                    <?php
                }
            }
        }
?>
<?php
    }
    include_once("html/Footer.php");
?>