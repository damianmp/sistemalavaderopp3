<?php
include("Objetos/Usuarios/UsuariosDTO.php");
include("Objetos/Usuarios/UsuariosDAO.php");
include("Objetos/Roles/RolesDAO.php");
include("Objetos/Roles/RolesDTO.php");
include("Objetos/Prendas/Prenda.php");
include("Objetos/Prendas/PrendaDAO.php");
include("Objetos/Salas/SalasDAO.php");
include("Objetos/Salas/SalasDTO.php");
include("Objetos/Movimiento/MovimientoDAO.php");
include("Objetos/Movimiento/MovimientoDTO.php");
include("Objetos/Logs/LogDAO.php");
include("Objetos/Logs/LogDTO.php");
include("Objetos/Deposito/DepositoDAO.php");
foreach (glob("Objetos/Prendas/DTO/*.php") as $filename) {
    include $filename;
}
include("ConexionDb.php");
?>
<html>
<head>
<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">-->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/Inicio.css" type="text/css">
<!-- <script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery-1.11.3.js"></script>-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/solid.css">
<link rel="shortcut icon" href="imagenes/logoLavaderoI_v3.png">
</head>
<body>
<div class="container">