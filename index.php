<?php
include_once("html/Header.php");

session_start();
if (!isset($_SESSION['usuario'])) {
?>
<div class="container w-75 bg-Info mt-5 rounded shadow">
<div class="row align-items-stretch">
<div class="col bg d-none d-lg-block col-md-5 col-lg-5 col-xl-6 rounded">
      </div>

      <div class="col bg-white p-5 rounded-end">
      <div class="text-end">
      <img src="imagenes/sistemap.png"  width="48" alt="" >
      </div>
      <h2 class="fw-bold text-center py-5">Bienvenido</h2>

      <form action="Formulario.php" method="post">
      <div class="mb-4">
      <label for="text" class="form-label" >Ingrese Ficha Municipal</label>
      <input type="text" class="form-control" name="ficha">
        </div>
        <div class="mb-4">
        <label for="password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" name="contrasenia">
        </div>
        <div class="d-grid">
        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </div>
        <div class="my-3">
        <span><a href="contrasenia.php">Recuperar Contraseña</a></span>
        </div>
    </form>
    </div>
 </div>
</div>
<?php
}
else {
  $usuDTOLogin = $_SESSION['usuario'];
  if ($usuDTOLogin->getM_rol()->count() > 0) {
    foreach ($usuDTOLogin->getM_rol() as $rol) {
      //if ($rol->getM_id() == 2) {
        include_once("Barra.php");
?>
<br>
<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="imagenes/ib2-1.jpg" alt="First slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="imagenes/hospitalAlvarezFachada.jpg" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="imagenes/alvarez_2_11.jpg" alt="Third slide">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
                    <?php
        echo "<br>Todos los paquetes hechos:";
        $array = MovimientoDAO::getMovimientos("*");
        foreach ($array as $movimientos) {
          echo "<br><a href='EditarPaquete.php?id=" . $movimientos->getId() . "'>" . $movimientos . "</a>";
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
//}
include_once("html/Footer.php");
?>