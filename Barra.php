<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="index.php">
  <?php
echo "Bienvenido! " . $usuDTOLogin->getM_nombre() . " Al S.G.S.R.H";
?>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
          Administrar
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="CrearPrenda.php">Ropa hopitalaria</a></li>
          <li><a class="dropdown-item" href="AdministrarUsuarios.php">Administrar usuarios</a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
          Gestionar
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="CargarRopaSucia.php">Egresos de ropa hospitalaria</a></li>
          <li><a class="dropdown-item" href="AdministrarDeposito.php">Ingresos de ropa hospitalaria</a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
          Consultar stock
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Total de ropa hospitalaria</a></li>
          <li><a class="dropdown-item" href="#">Por ropa hospitalaria</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="cerrar.php">Cerrar sesion</a>
      </li>
    </ul>
  </div>
</nav>
<!--<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery-1.11.3.js"></script>
<script src="js/codigo.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="js/bootstrap.bundle.js"></script>
<script src="js/bootstrap.bundle.min.js.map"></script>-->


