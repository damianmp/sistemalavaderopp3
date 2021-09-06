<style type="text/css">
        @font-face { font-family: Lato; src: url(fonts/Lato-Bol.ttf); font-weight: bold; }
        @font-face { font-family: Lato; src: url(fonts/Lato-Reg.ttf); }
        @font-face { font-family: "Lato Light"; src: url(fonts/Lato-Lig.ttf); }
        html {
            font-family: "Lato", "Helvetica", "sans-serif";
            color: #333333;
        }
        p {
            margin: 0 0 6px 0;
            font-size: 11pt;
            font-family: "Lato", "Helvetica", "sans-serif";
        }
        table {
            line-height: 140%;
            margin-bottom: 20px;
        }
            table.bordered {
                border-top: 0.1pt solid #999999;
                border-bottom: 0.1pt solid #999999;
            }
            tr, td, th { border: none; }
            th {
                font-size: 10pt;
                border-bottom: 0.1pt solid #999999;
                padding: 3px 0;
                line-height: 1;
                font-weight: bold;
            }
            td {
                font-size: 10pt;
                font-family: "Lato", "Helvetica", "sans-serif";
                vertical-align: top;
                padding: 3px 0;
            }
            td:last-child { padding-bottom: 0; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .padding-40 { padding: 40px 0 0 0;}
        .bottom-10 { margin-bottom: 10px; }
        .bottom-20 { margin-bottom: 20px; }
        .header { color: #7f7f7f; }
        .header p { font-size: 13px; line-height: 1.5em; }
        .header-link { font-weight: bold; text-decoration: none; color: #4F81BD; font-size: 14px; }
        .logo { margin-bottom: 20px; margin-left: -5px; }
        .logo img { zoom: 60%; }
        .recipient { margin-left: 7cm; }
        .main h1 {
            margin: 18px 0 18px 0;
            font-size: 250%;
            font-weight: normal;
            color: #4F81BD;
        }
        .main h2 {
            margin: 18px 0 6px 0;
            font-size: 180%;
            font-weight: normal;
        }
        .info { border: 1px solid #4F81BD; padding: 10px; margin-top: 50px; margin-bottom: 50px; }
        .info a { color: #4F81BD; text-decoration: none; }
        .client { color: #DEAE3A; font-weight: 800}
</style>
<?php
        include("Objetos/Remito/RemitoDAO.php");
        include("Objetos/Remito/RemitoDTO.php");
        
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
        include("Objetos/Deposito/DepositoDAO.php");
        foreach (glob("Objetos/Prendas/DTO/*.php") as $filename)
        {
            include $filename;
        }
        include("ConexionDb.php");
        session_start();
        
        if(isset($_SESSION['usuario'])){
            $usuDTOLogin = $_SESSION['usuario'];
            foreach ($usuDTOLogin->getM_rol() as $rol){
                if($rol->getM_id() == 2){
                    if(isset($_POST['id'])){
                        $_SESSION['id_remito'] = $_POST['id'];
                    }
?>
<div style="background-color: cornsilk;width: 500px;">
<table>
    <tr>
        <th>Sistema de Gestion de Ropa Hospitalaria (devolucion de prendas)</th>
    </tr>
</table>
<table>
    <tr>
        <th>Nº:</th>
        <td><?php echo RemitoDAO::AddRemito($_SESSION['id_remito']); ?></td>
    </tr>
    <tr>
        <th>Solicitante:</th>
        <td>
        <?php echo $usuDTOLogin->getM_nombre()." ".$usuDTOLogin->getM_apellido();?></td>
    </tr>
    <tr>
        <th>Fecha:</th>
        <td> <?php echo date("Y-m-d H:i"); ?></td>
    </tr>
    <tr>
        <th>Firma:</th>
        <td>................................................................</td>
    </tr>
</table>
<table>
    <tr>
        <th style="width: 100%;">Prenda</th>
        <th>Codigo</th>
        <th>Diferencia</th>
    </tr>
        <?php
        
        $_SESSION['mapa'];
        $mapa = $_SESSION['mapa'];

        foreach($mapa->keys() as $prenda){
            $mapa->get($prenda);
            echo "<tr><td>".$prenda."</td><td>".PrendaDAO::getPrenda($prenda)->getM_codigo()."</td><td>".$mapa->get($prenda)."</td></tr>";
        }
        ?>
</table>
<?php
                }
            }
        }
?>
</div>
</html>