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
    foreach (glob("Objetos/Prendas/DTO/*.php") as $filename)
    {
        include $filename;
    }
    include("ConexionDb.php");
?>
<html>
<head>
<link rel="stylesheet" href="css/Inicio.css" type="text/css">
</head>
<body>
