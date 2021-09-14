<?php
include_once("html/Header.php");
    
    session_start();
    if(isset($_SESSION['usuario'])){
        $usuDTOLogin = $_SESSION['usuario'];
        foreach ($usuDTOLogin->getM_rol() as $rol){
            //if($rol->getM_id() == 2){
                include_once("Barra.php");
                ?>
<div class="container-fluid modal-content">
<?php
                //var_dump($_POST);
                $prendas = PrendaDAO::getHTMLAllPrendas(1);

                $mapa = new Ds\Map();

                foreach($prendas as $aux){
                    $totalLimpio = 0;
                    $totalSucio = 0;
                    if(PrendaDAO::isPrendaInDepositoFromMovimiento(PrendaDAO::getPrenda($aux[0]), $_POST['id'])){
                        if(strlen($_POST[$aux[0]]) > 0){
                            $sum = preg_split('/[+]/', $_POST[$aux[0]]);
                            foreach($sum as $aux2){
                                $totalLimpio +=$aux2;
                            }
                            $mov = MovimientoDAO::getSalaPrendaFromMovimiento($_POST['id']);

                            foreach ($mov as $sala){
                                foreach($sala->getM_prendas() as $prenda){
                                    if($prenda->getM_codigo() == PrendaDAO::getPrenda($aux[0])->getM_codigo()){
                                        $totalSucio += $prenda->getM_cantidad();
                                    }
                                }
                            }

                            echo "<br><b>total de ".$aux[0]." SUCIO= ".$totalSucio." LIMPIO= ".$totalLimpio."</b> DIFERENCIA= ".($totalLimpio-$totalSucio);
                        }
                    }
                    $mapa->put($aux[0], $totalLimpio-$totalSucio);
                }
                $_SESSION['mapa'] = $mapa;
?>
<form target="_blank" action="remito.php" method="POST">
    <input type="submit" value="Remito" name="procesar">
    <input name="id" value="<?php
    echo $_POST['id'];
    ?>" hidden>
</form>
<form action="ModificarDeposito.php" method="POST">
    <input type="submit" value="Procesar" name="procesar">
    <input name="id" value="<?php
    echo $_POST['id'];
    ?>" hidden>
</form>
</div>
<?php
            }
        }
    //}
?>
<?php
    include_once("html/Footer.php");
?>