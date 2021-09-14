<?php
include_once("html/Header.php");

session_start();
if (isset($_SESSION['usuario'])) {
    $usuDTOLogin = $_SESSION['usuario'];
    foreach ($usuDTOLogin->getM_rol() as $rol) {
        if ($rol->getM_id() == 1) {
            include_once("Barra.php");
            //encargado, todo. operario funcional. enfermero ven los paquetes
            unset($_SESSION['accion']);
?>
<div class="modal-body">
    <div class="col-sm-10 main-sections">
        <div class="modal-contents">
        <h4> Administrar usuarios:</h4>
        <table class="table table-sm table-secondary" >
        <tr>
<form action="AdministrarUsuarios.php" method="POST">
<td scope="row">
    <?php
            $arr = UsuarioDAO::listUsuarios();
            foreach ($arr as $usuario) {
                echo $usuario->getM_nombre() . ", " . $usuario->getM_apellido() . " (" . $usuario->getM_idusuario() . ")  <button type='submit'class='btn btn-outline-dark' name='usuario' value='" . $usuario->getM_idusuario() . "'>Editar usuario</button><button type='submit'class='btn btn-outline-dark' name='baja' value='" . $usuario->getM_idusuario() . "'>" . (($usuario->getM_fecha_baja() == null) ? "Dar de baja</button>" : "Dar de alta</button>") . "<button type='submit'class='btn btn-outline-dark' name='roles' value='" . $usuario->getM_idusuario() . "'>Editar roles</button><br>";
                $_SESSION['usu' . $usuario->getM_idusuario()] = $usuario;
            }
?>
</form>
<?php
            if (isset($_POST['usuario'])) {
                $_SESSION['accion'] = 1;
                $usuario = $_SESSION['usu' . $_POST['usuario']];
                $_SESSION['temp_usu'] = $usuario;
                echo "<tr><th>Editar usuarios (" . $_POST['usuario'] . ") -> " . $usuario->getM_nombre() . "</th></tr>";
?>
<form action="ProcesarUsuario.php" method="POST">
<tr> 
<td>Datos</td>
</tr>
<td>Nombre <input type="text" class="form-select-sm" name="nombre" value="<?php echo $usuario->getM_nombre(); ?>"></td></tr>
<tr><td>Apellido <input type="text" class="form-select-sm" name="apellido" value="<?php echo $usuario->getM_apellido(); ?>"></td></tr>
<tr><td>Ficha municipal<input type="text" class="form-select-sm" name="ficha_municipal" value="<?php echo $usuario->getM_Ficha_municial(); ?>"></td></tr>
<tr><td>Contraseña<input type="password" class="form-select-sm" name="contrasenia" value="<?php echo $usuario->getM_contrasenia(); ?>"></td></tr>
<tr><td>Reingrese contraseña<input type="password" class="form-select-sm" name="recontrasenia" value="<?php echo $usuario->getM_contrasenia(); ?>"></td></tr>
<tr><td>Correo <input type="text" class="form-select-sm"  name="correo" value="<?php echo $usuario->getM_correo(); ?>"></td></tr>
<tr>    
<br>
<td>
<div class="d-grid">
<input type="submit" class="btn btn-primary btn-sm">
</div>
</td>
</tr>
</table>
</div>
</div>
</form>
</div>
<?php
            }
            else if(isset($_POST['baja'])){
                 $_SESSION['accion'] = 2;
                 $usuario = $_SESSION['usu' . $_POST['baja']];
?>
<form action="ProcesarUsuario.php" method="POST">
    <?php echo "¿Estas seguro que queres dar de ".(($usuario->getM_fecha_baja() == null) ? "baja" : "alta")." a el usuario ".$usuario->getM_nombre()." ".$usuario->getM_apellido()."?";?>
    <button type="submit">Si</button>
    <input name='baja' value='<?php echo $usuario->getM_idusuario();?>' hidden>
</form>
<?php
            }
            else if(isset($_POST['roles'])){
                $_SESSION['accion'] = 3;
                $usuario = $_SESSION['usu' . $_POST['roles']];
                foreach($usuario->getM_rol() as $rol_usu){
                    echo "Rol del usuario ".$usuario->getM_nombre()." ".$usuario->getM_apellido()." es <b>".$rol_usu->getM_descripcion()."</b><br>";
                }
                ?>
<form action="ProcesarUsuario.php" method="POST">
Seleccione un nuevo rol:
<select name="rol">
<?php
                $array_roles = RolesDAO::getAllRoles();
                foreach($array_roles as $rolobj){
                    echo "<option value=".$rolobj->getM_id().">".$rolobj->getM_descripcion()."</option>";
                }
?>
</select>
<input name='usurol' value='<?php echo $usuario->getM_idusuario();?>' hidden>
<button type="submit">Cambiar rol</button>
</form>
<?php
            }
        }
    }
}
include_once("html/Footer.php");
?>
