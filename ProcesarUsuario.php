<?php
    include_once("html/Header.php");
    session_start();
    if(isset($_SESSION['usuario'])){
        $usuDTOLogin = $_SESSION['usuario'];
        foreach ($usuDTOLogin->getM_rol() as $rol){
               if($rol->getM_id() == 1){
                   if(isset($_SESSION['accion'])){
                       switch ($_SESSION['accion']){
                           case 1:{
                               if(strcmp($_POST['contrasenia'], $_POST['recontrasenia'])){
                                   echo "Contraseñas no cohinciden!";
                               }
                               else if(isset($_SESSION['temp_usu'])){
                                   $aux_usu = new UsuarioDTO(0,0);
                                   $usuario = $_SESSION['temp_usu'];

                                   $aux_usu->setM_Ficha_municial($_POST['ficha_municipal']);
                                   $aux_usu->setM_apellido($_POST['apellido']);
                                   $aux_usu->setM_contrasenia((!strcmp($_POST['contrasenia'], $usuario->getM_contrasenia())?$_POST['contrasenia']:sha1($_POST['contrasenia'])));
                                   $aux_usu->setM_correo($_POST['correo']);
                                   $aux_usu->setM_nombre($_POST['nombre']);

                                   if(UsuarioDAO::editUsuario($usuario, $aux_usu)){
                                       echo "Por favor espere...";
                                       echo "<meta http-equiv=\"refresh\" content=\"2;url=https://www.sistemalavaderopp3.ml/AdministrarUsuarios.php\"/>";
                                       break;
                                   }
                                   else{
                                       echo "Error!";
                                       echo "<meta http-equiv=\"refresh\" content=\"2;url=https://www.sistemalavaderopp3.ml/AdministrarUsuarios.php\"/>";
                                       break;
                                   }
                               }
                               return;
                           }
                           case 2:{
                                UsuarioDAO::editFlagUsuario($_POST['baja']);
                                echo "Por favor espere...";
                                echo "<meta http-equiv=\"refresh\" content=\"2;url=https://www.sistemalavaderopp3.ml/AdministrarUsuarios.php\"/>";
                                break;
                           }
                           case 3:{
                               RolesDAO::setUserRol($_POST['usurol'],$_POST['rol']);
                               echo "Por favor espere...";
                               echo "<meta http-equiv=\"refresh\" content=\"2;url=https://www.sistemalavaderopp3.ml/AdministrarUsuarios.php\"/>";
                               break;
                           }
                       }
                   }
               }
           }
       }
    include_once("html/Footer.php");
?>