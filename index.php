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
                <a href="https://www.sistemalavaderopp3.ml">¿Olvidaste la contraseña?</a>
                </div> 
            </form>
        </div>
    </div>
</div>
<!--
<form action="Formulario.php" method="post">
<img src="imagenes/logo.jpeg" id="imagen_inicio">
<table id="inicio">
    <tr>
        <td>Usuario</td><td><input type="text" name="ficha"></td>
    </tr>
    <tr>
         <td>Contraseña</td><td><input type="password" name="contrasenia"><input type="submit" value="Ingresar"></td>
    </tr>
    <tr>
        <td><a href="https://www.sistemalavaderopp3.ml">¿Olvidaste la contraseña?</a></td>
    </tr>
</table>
</form> -->
<?php
    }
    else{
        $usuDTOLogin = $_SESSION['usuario'];
        if($usuDTOLogin->getM_rol()->count() > 0){
            foreach ($usuDTOLogin->getM_rol() as $rol){
                if($rol->getM_id() == 2){
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">
  <?php
  echo "Bienvenido! ".$usuDTOLogin->getM_nombre()." Al sistema";
  ?>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="CargarRopaSucia.php">Cargar ropa sucia</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="AdministrarDeposito.php">Administrar deposito</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="CrearPrenda.php">Crear nuevo tipo prenda</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="cerrar.php">Cerrar sesion</a>
      </li>
    </ul>
  </div>
</nav>
<?php
                    echo "<br>Todos los paquetes hechos:";
                    $array = MovimientoDAO::getMovimientos("*");
                    foreach ($array as $movimientos){
                        echo "<br><a href='EditarPaquete.php?id=".$movimientos->getId()."'>".$movimientos."</a>";
                    }
                }
            }
        }
?>
<?php
    }
    include_once("html/Footer.php");
?>