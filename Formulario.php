<?php
    include_once("html/Header.php");
?>
<?php
    $_POST['contrasenia'] = sha1($_POST['contrasenia']);
    $arrayPost = array($_POST['ficha'], $_POST['contrasenia']);
    
    $usuDao = new UsuarioDAO();
    $usuDTOLogin = $usuDao->InicioSesion(new UsuarioDTO($arrayPost[0],$arrayPost[1])); 
    if($usuDTOLogin != null){
        session_start();
        $_SESSION['usuario'] = $usuDTOLogin;
        
        echo "Espere por favor...";
        echo "<meta http-equiv=\"refresh\" content=\"2;url=https://www.sistemalavaderopp3.ml/\"/>";
    }
    else{   
        echo "Error usuario/contraseña erroneos";
        echo "<meta http-equiv=\"refresh\" content=\"2;url=https://www.sistemalavaderopp3.ml/\"/>";
    }
?>
<?php
    include_once("html/Footer.php");
?>