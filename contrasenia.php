<?php
include_once("html/Header.php");

session_start();
if (!isset($_SESSION['usuario'])) {
?>
<div class="container w-75 bg-Info mt-5 rounded shadow">
<div class="row align-items-stretch"> 
<div class="col logoContrasenia d-none d-lg-block col-md-5 col-lg-5 col-xl-6 rounded">
    </div>

    <div class="col bg-white p-5 rounded-end">
    <div class="text-end">
    <img src="imagenes/sistema (3).png"  width="48" alt="">
    </div>
    <h2 class="fw-bold text-center py-5">Bienvenido</h2>

    <form action="contrasenia.php" method="post">
    <div class="form-floating mb-3">
                    <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Ingresar Email</label>
    </div>
    <div class="d-grid">
        <button class="btn btn-primary btn-sm" type="submit" id="btnEnviarEmail"> Enviar </button>
    </div>
    <div class="my-3">
        <span><a href="index.php">Atras</a></span>
    </div>
    <div id="contenido">
    <?php
    if (isset($_POST['email'])) {
        $aux = UsuarioDAO::isEmailValid($_POST['email']);
        if ($aux != null) {

            $passwordran = rand(1000, 99999999);

            $to = $aux->getM_correo();
            $subject = "Recuperación de contraseña https://www.sistemalavaderopp3.ml";
            $message = "\n\rHola usuario del sistema de lavandereria(r)"
                . "\n\rEstás recibiendo esta notificación porque vos solicitaste una nueva clave para tu cuenta."
                . "\n\rUsuario: " . $aux->getM_Ficha_municial() . ""
                . "\n\rClave: " . $passwordran . ""
                . "\n\rSaludos cordiales, la administración.";
            $header = "From: mail@localstrike.ml\r\n";

            UsuarioDAO::changePassword($aux->getM_correo(), $passwordran, true);

            $retval = mail($to, $subject, $message, $header);
            if ($retval == true) {
                echo "Mensaje enviado correctamente!...";
            }
            else {
                //rolback
                UsuarioDAO::changePassword($aux->getM_correo(), $aux->getM_contrasenia(), false);
                echo "Error al mandar mensaje...";
            }
        }
    }
}
include_once("html/Footer.php");
?>
</div>
                </form>
  </div>
</div>
</div>




<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery-1.11.3.js"></script>
<script src="js/codigo.js"></script>