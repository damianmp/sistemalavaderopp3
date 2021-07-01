<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="index.php">
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
