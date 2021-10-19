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
<?php
    foreach ($usuDTOLogin->getM_rol() as $usurol){
        if($usurol->getM_id() <= 2){
    ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
          Administrar
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="CrearPrenda.php">Ropa hopitalaria</a></li>
          <?php
          foreach ($usuDTOLogin->getM_rol() as $usurol){
              if($usurol->getM_id() == 1){
          ?>
          <li><a class="dropdown-item" href="AdministrarUsuarios.php">Administrar usuarios</a></li>
          <li><a class="dropdown-item" href="Log.php">Ver Movimientos</a></li>
          <?php
              }
          }
           ?>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
          Gestionar
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="CargarRopaSucia.php">Egresos de ropa hospitalaria</a></li>
          <li><a class="dropdown-item" href="AdministrarDeposito.php">Ingresos de ropa hospitalaria</a></li>
          <li><a class="dropdown-item" href="CargarRopaLimpias.php">Ingresos de ropa a salas</a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
          Consultar
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="deposito.php">EL estado de la ropa hospitalaria</a></li>
          <li><a class="dropdown-item" href="#">Stock Por tipo de ropa hospitalaria</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="cerrar.php">Cerrar sesion</a>
      </li>
    </ul>
  </div>
<?php
    }
    else{
        ?>
    <li class="nav-item">
        <a class="nav-link" href="cerrar.php">Cerrar sesion</a>
      </li>
    </ul>
  </div>
    <?php
    }
}
?>
</nav>


