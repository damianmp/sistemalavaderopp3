<?php

class PrendaDAO{
    public function isPrendaInDepositoFromMovimiento($prenda, $id){
        $con = new Conexion();
        $sqlQuery = "SELECT count(*) as 'cantidad_de_registro' FROM `prenda_salas` WHERE `id_prenda` = ".$prenda->getM_codigo()." and `id_movimiento` = '".$id."' and id_estado = 2 limit 1 ";
        $resultado = $con->getConnection()->query($sqlQuery);
        $row = $resultado->fetch_assoc();
        if($row['cantidad_de_registro'] > 0){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function addPrendaDeposito($prenda){
        $con = new Conexion();
        
        $sqlQuery = "SELECT * FROM `deposito` WHERE `id_prenda` = ".$prenda->getM_codigo();
        $resultado = $con->getConnection()->query($sqlQuery);
        if($resultado->num_rows == 1){
            //update
            //que las prendas se acumulen
            $row = $resultado->fetch_assoc();
            
            $sqlQuery = "UPDATE `deposito` SET `cantidad`=".($prenda->getM_cantidad()+$row['cantidad'])." WHERE `id_prenda` = ".$prenda->getM_codigo();
            $con->getConnection()->query($sqlQuery);
            return true;
        }
        else{
            $sqlQuery = "INSERT INTO `deposito`(`id_prenda`, `cantidad`, `id_estado`) VALUES (?,?,1)";
            $statement = $con->getConnection()->prepare($sqlQuery);
            $statement->bind_param("ii", $id_prenda, $cantidad);
            $id_prenda = $prenda->getM_codigo();
            $cantidad = $prenda->getM_cantidad();
            if(!$statement->execute()){
                return false;
            }
            return true;
        }
    }
    
    public function getPrendaDeposito(){
        $con = new Conexion();
        //$sqlQury = "SELECT * FROM `deposito`";
        $sqlQury = "SELECT d.*, p.descripcion FROM deposito as d join prenda as p on d.id_prenda = p.codigo ";
        $resultado = $con->getConnection()->query($sqlQury);
        /*
         * OBJETO PRENDA | ESTADO
         * 
         * Ds/Map requiere de la extension php_ds.dll
         * 
         */
        
        $mapa = new Ds\Map();
        
        if($resultado->num_rows >= 1){
            while($row = $resultado->fetch_assoc()){
                
                $class = $row['descripcion'];
                
                $aux = new $class;
                $aux->setM_codigo($row['id_prenda']);
                $aux->setM_cantidad($row['cantidad']);
                $aux->setM_descripcion($row['descripcion']);
                $mapa->put($aux, $row['id_estado']);
            }
        }
        return $mapa;
    }
    
    public function getCountPrenda($id, $mov) {
        $con = new Conexion();
        $sqlQury = "SELECT SUM(cantidad) as sumatoria FROM `prenda_salas` WHERE `id_prenda` = ".$id." and id_movimiento like '".$mov."' and id_estado = 2";
        
        $resultado = $con->getConnection()->query($sqlQury);
        if($resultado != null && $resultado->num_rows == 1){
            $row = $resultado->fetch_assoc();
            return $row['sumatoria'];
        }
    }
    
    public function getCountPrendaSucia($prenda){
        $con = new Conexion();
        $sqlQuery = "SELECT sum(cantidad) as total from prenda_salas as ps join movimiento as m on m.id like ps.id_movimiento where id_estado = 2 and id_prenda = ".$prenda->getM_codigo();
        
        $resultado = $con->getConnection()->query($sqlQuery);
        if($resultado != null && $resultado->num_rows == 1){
            $row = $resultado->fetch_assoc();
            return $row['total'];
        }
    }

    public function isOutboundPrendaDeposito($prenda, $movimiento){
        /*
         * Diego dijo, que al agregarse una prenda esa prenda tiene que tener un respaldo incluso
         * por ejemplo:
         *  si agrego 2 prendas, entonces tengo que tener 2 prendas mas por lo menos para poder
         *  tener un respaldo. si agrego 2 prendas sucias, entonces tengo que tener por lo menos
         *  2 prendas mas, dando un total de 4 prendas.-
         * 
         * 21/09/2021 14:50 - Se modifico la formula, ahora se cuenta si hay respaldo en el deposito
         */
        $cantidadSucia = PrendaDAO::getCountPrendaSucia($prenda);
        if($cantidadSucia == null){
            $cantidadSucia = 0;
        }
        
        $mapa = PrendaDAO::getPrendaDeposito();
        $aux = null;
        foreach($mapa->keys() as $auxp){
            if($auxp->getM_codigo() == $prenda->getM_codigo()){
                $aux = $auxp;
            }
        }
        if($aux != null){
            //saco la formula actual
            $total = (PrendaDAO::getCountPrenda($prenda->getM_codigo(), $movimiento)+$prenda->getM_cantidad()) + $cantidadSucia;
            
            //echo "<br>=============<br>cantidad todavia sin ingresar: ".$total."<br>cantidad a agregar: ". $prenda->getM_cantidad()."(".($prenda->getM_cantidad()*2).")<br>cantidad de prendas sucias(".$prenda->getM_descripcion()."): ".$cantidadSucia."<br>deposito(".$aux->getM_descripcion()."): ".$aux->getM_cantidad();
            if($total > $aux->getM_cantidad()){
                return true;
            }
            return false;
        }
        else{
            return true;
        }
        /*if($aux == null){
            return true;
        }*/
        //return false;
    }
    
    public function getHTMLAllPrendas($b_valido) {
        
        $array = new ArrayObject();
        
        foreach (glob("Objetos/Prendas/DTO/*.php") as $prendas){
            $aux = preg_split('/(Objetos\/Prendas\/DTO\/)/', $prendas);
            $aux = preg_split('/(.php)/', $aux[1]);
            
            //echo $aux[0]."->ESTADO:".PrendaDAO::getPrenda($aux[0])->getM_estado()."<br>";
            
            if(PrendaDAO::getPrenda($aux[0])->getM_estado() == $b_valido){
                $array->append($aux);
            }
        }
        return $array;
    }
    
    public function addPrenda($prenda){
        $con = new Conexion();
        $sqlQuery = "INSERT INTO `prenda` (`codigo`, `descripcion`) VALUES (?,?)";
        
        $statement = $con->getConnection()->prepare($sqlQuery);
        $statement->bind_param("is", $codigo, $descripcion);
        $codigo = $prenda->getM_codigo();
        $descripcion = $prenda->getM_descripcion();
        
        if(!$statement->execute()){
            //echo "error (" . $statement->errno.") ".$statement->error;
            return false;
        }
        return true;
    }
    
    public function getPrenda($prenda) {
        $con = new Conexion();
        $sqlQuery = "SELECT p.codigo, p.descripcion as prenda, p.estado FROM prenda as p WHERE p.descripcion like '".$prenda."'";
        $resultado = $con->getConnection()->query($sqlQuery);
        
        if(class_exists($prenda)){
            $aux = new $prenda;
        }
        else{
            return null;
        }
        
        if($resultado->num_rows == 1){
            while($row = $resultado->fetch_assoc()){
                $aux->setM_codigo($row['codigo']);
                $aux->setM_descripcion($row['prenda']);
                $aux->setM_estado($row['estado']);
            }
        }
        return $aux;
    }
    
    public function getPrendaById($id){
        $con = new Conexion();
        $sqlQuery = "SELECT `id_prenda`, `codigo`, `descripcion` FROM `prenda` WHERE `codigo` = ".$id;
        $resultado = $con->getConnection()->query($sqlQuery);

        if($resultado->num_rows == 1){
            $row = $resultado->fetch_assoc();
            
            $class = $row['descripcion'];
            $auxprenda = new $class;
            $auxprenda->setM_descripcion($row['descripcion']);
            $auxprenda->setM_codigo($row['codigo']);
            
            return $auxprenda;
        }
        return null;
    }
    
    public function getPrendaFromString($strPrenda) : Prenda{
        $auxP = PrendaDAO::getPrenda($strPrenda);
        
        return $auxP;
    }
    
    public function ABPrenda($prenda, $estado) {
        $con = new Conexion();
        $sqlQuery = "UPDATE prenda SET estado = ".$estado." WHERE descripcion like '".$prenda."'";
        $con->getConnection()->query($sqlQuery);
        return 1;
    }
    
    public function fixNombrePrenda($prenda){
        if(preg_match('/[a-zA-Z\s]{1,}/', $prenda, $prenda)){
            $prenda = preg_replace('/\s+/', '', $prenda);
            return $prenda[0];
        }
        return $prenda;
    }
    
    //get la tanda de ropa limpia
    public function getPrendaSalaMovLimpia($prenda, $sala) {
        $con = new Conexion();
        $sqlQuery = "SELECT * FROM `prenda_salas` where id_salas = ".$sala." and id_prenda = ".$prenda. " and id_estado = 1";
        $statement = $con->getConnection()->query($sqlQuery);
        if($statement->num_rows > 0){
            $row = $statement->fetch_assoc();
            return $row['id_movimiento'];
        }
    }
    
    public function removePrendaMovSalaLimpia($prenda, $sala, $cantidad) {
        /*
         * La idea:
         * agarrar una cantidad e ir sacando la resta de las prendas que podria sacar
         */
        $con = new Conexion();
        $sqlQuery = "Select * from prenda_salas where id_prenda =".$prenda." and id_salas=".$sala." and cantidad < 0 and id_estado = 1";
        $statement = $con->getConnection()->query($sqlQuery);
        
        //echo $sqlQuery."<br>";
        
        if($statement->num_rows > 0){
            $row = $statement->fetch_assoc();
            
            $sqlQuery = ($cantidad+$row['cantidad'] < 0 )? 
                    "UPDATE `prenda_salas` SET `cantidad`= ? WHERE `id_salas` = ? and `id_prenda`=? and `id_estado`=1 and `id_movimiento`= ? and cantidad <0 "
                    :
                    "DELETE FROM `prenda_salas` WHERE `id_salas` = ? and `id_prenda`=? and `id_estado`=1 and `id_movimiento`= ? and cantidad <0";
            
            //echo ($cantidad+$row['cantidad'])." --<br> ".$sqlQuery;
            
            $prepare = $con->getConnection()->prepare($sqlQuery);

            if($cantidad+$row['cantidad'] < 0){
                $prepare->bind_param("iiis", $auxcantidad, $id_salas, $id_prenda, $movimiento);
                $auxcantidad = $cantidad+$row['cantidad'];
                $id_salas = $sala;
                $id_prenda = $prenda;
                $movimiento = $row['id_movimiento'];
            }
            else{   
                $prepare->bind_param("iis", $id_salas, $id_prenda, $movimiento);
                $id_salas = $sala;
                $id_prenda = $prenda;
                $movimiento = $row['id_movimiento'];
            }

            $prepare->execute();
        }
    }

    public function addPrendaMovSalaLimpia($prenda, $sala) {
        $con = new Conexion();
        $movimiento = self::getPrendaSalaMovLimpia($prenda->getM_codigo(),$sala->getM_id());
        //$sqlQurty = "INSERT INTO `prenda_salas`(`id_salas`, `id_prenda`, `id_estado`, `id_movimiento`, `cantidad`) VALUES (?,?,1,?,?)";
        $sqlQuery = "SELECT * FROM prenda_salas where id_salas = ".$sala->getM_id()." and id_prenda = ".$prenda->getM_codigo()." and id_estado = 1 and id_movimiento like '".$movimiento."'";
        $statement = $con->getConnection()->query($sqlQuery);
        
        //echo $sqlQuery;
        
        if($statement->num_rows > 0){
            
            $row = $statement->fetch_assoc();
            
            //var_dump($row);
            
            $sqlQuery = "SELECT * FROM prenda_salas where id_salas = ".$sala->getM_id()." and id_prenda = ".$prenda->getM_codigo()." and id_estado = 1 and id_movimiento like '".$movimiento."' and cantidad <= 0";
            $statement = $con->getConnection()->query($sqlQuery);
            
            //echo $sqlQuery;
            
            if($statement->num_rows > 0){
                $row = $statement->fetch_assoc();
                
                $sqlQurty = "UPDATE `prenda_salas` SET `cantidad`= ? WHERE `id_salas` = ? and `id_prenda`=? and `id_estado`=1 and `id_movimiento`=? and cantidad <0";
                $preparar = $con->getConnection()->prepare($sqlQurty);
                $preparar->bind_param("iiis",$cantidad , $id_salas, $id_prenda, $id_movimiento);
                $id_salas = $sala->getM_id();
                $id_prenda = $prenda->getM_codigo();
                $cantidad = $row['cantidad'] + $prenda->getM_cantidad();
                $id_movimiento = $movimiento;
                
                $preparar->execute();
            }
            else{
                //echo "no";
                $sqlQurty = "INSERT INTO `prenda_salas`(`id_salas`, `id_prenda`, `id_estado`, `id_movimiento`, `cantidad`) VALUE (?,?,1,?,?)";
                $preparar = $con->getConnection()->prepare($sqlQurty);
                $preparar->bind_param("iisi", $id_salas, $id_prenda, $id_movimiento, $cantidad);
                $id_salas = $sala->getM_id();
                $id_prenda = $prenda->getM_codigo();
                $cantidad = $prenda->getM_cantidad();
                $id_movimiento = $movimiento;
                
                $preparar->execute();
            }
        }
    }

    public function addPrendaSalaLimpia(Prenda $prenda, $sala){
        //averiguo si hay un movimiento existente
        /*
         * El id_movimiento para prendas limpias (1) en este caso seria para un uso distinto: se usaria para distinguir tandas de prendas.
         */
        $con = new Conexion();
        $sqlQurty = "SELECT * from prenda_salas where id_salas = ".$sala." and id_prenda = ".$prenda->getM_codigo()." and id_estado = 1";
        
        //echo $sqlQurty;
        
        $statement = $con->getConnection()->query($sqlQurty);
        if($statement->num_rows > 0){
            
            //echo "if($statement->num_rows > 0){";
            
            $row = $statement->fetch_assoc();
            
            //var_dump($row);
            
            $sqlQurty = "UPDATE `prenda_salas` SET cantidad = ? where id_salas = ? and id_prenda = ? and id_estado = 1";
            $prepare = $con->getConnection()->prepare($sqlQurty);
            $prepare->bind_param("iii",$cantidad , $id_salas, $id_prenda);
            $id_salas = $sala;
            $id_prenda = $prenda->getM_codigo();
            $cantidad = $prenda->getM_cantidad();
            
            $prepare->execute();
            
            //echo $row;
            
            return $row['id_movimiento'];
            
        }
        else{
            $sqlQurty = "INSERT INTO `prenda_salas`(`id_salas`, `id_prenda`, `id_estado`, `id_movimiento`, `cantidad`) VALUES (?,?,1,?,?)";
            $prepare = $con->getConnection()->prepare($sqlQurty);
            $prepare->bind_param("iisi", $id_salas, $id_prenda, $id_movimiento, $cantidad);
            $id_salas = $sala;
            $id_prenda = $prenda->getM_codigo();
            $cantidad = $prenda->getM_cantidad();
            $id_movimiento = hash("md5", (rand(-9999999999999999, 9999999999999999)));

            $prepare->execute();

            return $id_movimiento;
        }
    }
    
    public function getPrendasSala($sala, $prenda, $estado, $mov) {
        $con = new Conexion();
        $sqlQuery = ($estado == 1)?
            "SELECT sum(cantidad) as cantidad FROM `prenda_salas` where id_salas = ".$sala." and id_prenda = ".$prenda." and id_estado = ".$estado
                :
            "SELECT * FROM `prenda_salas` where id_salas = ".$sala." and id_prenda = ".$prenda." and id_estado = ".$estado." and id_movimiento like '".$mov."'";
        $statement = $con->getConnection()->query($sqlQuery);
        
        //echo "<br>".$sqlQuery."<br>";
        
        if($statement->num_rows > 0){
            $row = $statement->fetch_assoc();
            
            return $row['cantidad'];
        }
    }
    
    public function removePrendasDebe($sala, $prenda, $cantidad) {
        $con = new Conexion();
        $sqlQuery = "DELETE FROM `prenda_salas` WHERE id_salas = ? and id_prenda = ? and id_estado = 1 and cantidad = ?";
        $prepare = $con->getConnection()->prepare($sqlQuery);
        $prepare->bind_param("iii",$id_sala, $id_prenda, $can);
        $id_sala = $sala;
        $id_prenda = $prenda;
        $can = $cantidad;
        
        $prepare->execute();
        
        
    }
}
