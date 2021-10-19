<?php
include_once("html/Header.php");

session_start();
if (isset($_SESSION['usuario'])) {
    $usuDTOLogin = $_SESSION['usuario'];
    foreach ($usuDTOLogin->getM_rol() as $rol) {
        if ($rol->getM_id() == 1) {
            include_once("Barra.php");
            echo"<div class='modal-body'>
            <div class='col-sm-12 main-sections'>
                <div class='modal-contents'> <label class='text-center'> <h5><p>Tabla de movimientos</p></h4></lavel>";

            echo "<table class='table table-bordered table-striped table-sm table-secondary' border='1 px'>";
            $array = LogDAO::getLogs();
            $usuarios = UsuarioDAO::listUsuarios();
            $x = 1;
            $aux = null;
            
            $paginado = new ArrayObject();
            
            foreach ($array as $log){
                
                $logs = new ArrayObject();
                
                foreach($usuarios as $usu){
                    if($usu->getM_idusuario() == $log->getM_usuario()){
                        $aux = $usu;
                    }
                }
                $logs->append($x++);
                $logs->append($log->getFecha());
                $logs->append($aux->getM_nombre());
                $logs->append($log->getM_idmovimiento());
                $logs->append($log->getM_accion());
                
                $paginado->append($logs);
                
                //
            }
            
            define("REGISTROS_POR_HOJA", 5);
            
            $pag = isset($_GET['pagina'])?$_GET['pagina']:1;
            
            $inicio = $pag * REGISTROS_POR_HOJA;
            
            for($x = 1; $x < $paginado->count(); $x++){
                if($x >= $inicio  && $x <= $inicio+REGISTROS_POR_HOJA){
                    $obj = $paginado->offsetGet($paginado->count() - $x);
                    echo "<tr><th><h5>Fila</h5></th><th><h5>Fecha</h5> </th><th><h5>usuario</h5></th><th><h5>#Paquete</h5></th><th><h5>movimiento</h5></th><tr><td>".$obj[0]."</td><td>".$obj[1]."</td><td>".$obj[2]."</td><td>".$obj[3]."</td><td>".$obj[4]."</td></tr>";
                }
                //var_dump($obj);
            }
            
            $hojas = $paginado->count()/REGISTROS_POR_HOJA;
            
            echo "</table>";
            echo "</div></div></div>";
            echo "<form action='Log.php' method='get'>";
            
            $can = 0;
            $fix = new ArrayObject();
            
            for($x = $pag; $x >= 1 ; $x--){
                //echo "<button type='submit' name='pagina' value='".$x."'>".$x."</buttom>";
                $fix->append($x);
                $can++;
                if($can > REGISTROS_POR_HOJA){
                    break;
                }
            }
            
            for($x = $fix->count()-1; $x >= 1; $x--){
                echo "<button type='submit' name='pagina' value='".$fix->offsetGet($x)."'>".$fix->offsetGet($x)."</buttom>";
            }
            
            $can = 0;
            for($x = 1; $x <= $hojas ; $x++){
                if($x > $pag){
                    echo "<button type='submit' name='pagina' value='".$x."'>".$x."</buttom>";
                    $can++;
                    if($can > REGISTROS_POR_HOJA){
                        break;
                    }
                }
            }
            echo "</form>";
        }
    }
}
include_once("html/Footer.php");
?>