<?php
    include_once("html/Header.php");
    
    session_start();
    if(!isset($_SESSION['usuario'])){
?>
<form action="Formulario.php" method="post">
<img src="imagenes/pato.jpg" id="imagen_inicio">
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
</form>
<?php
    }
    else{
        $usuDTOLogin = $_SESSION['usuario'];
        echo "Bienvenido! ".$usuDTOLogin->getM_nombre()." Al sistema.";
?>
<table id="inicio"><tr>
<?php
        if($usuDTOLogin->getM_rol()->count() > 0){
            foreach ($usuDTOLogin->getM_rol() as $rol){
                if($rol->getM_id() == 2){
?>
<td><a href="CargarRopaSucia.php">Cargar ropa sucia</a></td><td><a href="cerrar.php">Cerrar sesion</a></td>
<?php
                    echo "<br>Todos los paquetes hechos:";
                    $array = MovimientoDAO::getMovimientos(0);
                    
                    foreach ($array as $movimientos){
                        echo "<br><a href='todo.php'>".$movimientos."</a>";
                    }
                }
            }
        }
?>
</tr>
</table>
<?php
    }
    include_once("html/Footer.php");
?>