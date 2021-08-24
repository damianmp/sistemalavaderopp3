<?php
    include_once("html/Header.php");

    session_start();
    if(!isset($_SESSION['usuario'])){
?>
<div class="container-fluid modal-content">
                    <div class="row" method="post">
                        <form action="contrasenia.php" method="post">
                            Ingrese Email: <input name="email" type="text">
                            <button type="submit"> Enviar </button>
                        </form>
                    </div>
</div>
<?php
        if(isset($_POST['email'])){
            $aux = UsuarioDAO::isEmailValid($_POST['email']);
            if($aux != null){
                
                $passwordran = rand(1000, 99999999);
                
                $to = $aux->getM_correo();
                $subject = "Recuperación de contraseña https://www.sistemalavaderopp3.ml";
                $message =   "\n\rHola usuario del sistema de lavandereria(r)"
                            ."\n\rEstás recibiendo esta notificación porque vos solicitaste una nueva clave para tu cuenta."
                            ."\n\rUsuario: ".$aux->getM_Ficha_municial().""
                            ."\n\rClave: ".$passwordran.""
                            ."\n\rSaludos cordiales, la administración.";
                $header = "From: mail@localstrike.ml\r\n";
                
                UsuarioDAO::changePassword($aux->getM_correo(),$passwordran, true);
                
                $retval = mail ($to,$subject,$message,$header);
                if( $retval == true )  
                {
                  echo "Mensaje enviado correctamente!...";
                }
                else
                {
                  //rolback
                  UsuarioDAO::changePassword($aux->getM_correo(),$aux->getM_contrasenia(), false);
                  echo "Error al mandar mensaje...";
                }
            }
        }
    }
    include_once("html/Footer.php");
?>