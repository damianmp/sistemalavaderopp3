<?php
    session_start();
    unset($_SESSION['usuario']);
    unset($_SESSION['id_mov']);
    header("Location:index.php");
?>
