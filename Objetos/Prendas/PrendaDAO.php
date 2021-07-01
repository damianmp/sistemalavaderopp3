<?php

class PrendaDAO{
    public function isPrendaInDepositoFromMovimiento($prenda, $id){
        $con = new Conexion();
        $sqlQuery = "SELECT count(*) as 'cantidad_de_registro' FROM `prenda_salas` WHERE `id_prenda` = ".$prenda->getM_codigo()." and `id_movimiento` = '".$id."' limit 1 ";
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
            $sqlQuery = "UPDATE `deposito` SET `cantidad`=".$prenda->getM_cantidad()." WHERE `id_prenda` = ".$prenda->getM_codigo();
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
        $sqlQury = "SELECT SUM(cantidad) as sumatoria FROM `prenda_salas` WHERE `id_prenda` = ".$id." and id_movimiento like '".$mov."'";
        
        $resultado = $con->getConnection()->query($sqlQury);
        if($resultado != null && $resultado->num_rows == 1){
            $row = $resultado->fetch_assoc();
            return $row['sumatoria'];
        }
    }
    
    public function getCountPrendaSucia($prenda){
        $con = new Conexion();
        $sqlQuery = "SELECT sum(cantidad) as total from prenda_salas as ps join movimiento as m on m.id like ps.id_movimiento where id_prenda = ".$prenda->getM_codigo();
        
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
            $total = (PrendaDAO::getCountPrenda($prenda->getM_codigo(), $movimiento)+$prenda->getM_cantidad())*2 + $cantidadSucia;
            //echo "<br>=============<br>cantidad todavia sin ingresar: ".$total."<br>cantidad a agregar: ". $prenda->getM_cantidad()."(".($prenda->getM_cantidad()*2).")<br>cantidad de prendas sucias(".$prenda->getM_descripcion()."): ".$cantidadSucia."<br>deposito(".$aux->getM_descripcion()."): ".$aux->getM_cantidad();
            if($total > $aux->getM_cantidad()){
                return true;
            }
            return false;
        }
        else{
            return true;
        }
    }
    
    public function getHTMLAllPrendas() {
        
        $array = new ArrayObject();
        
        foreach (glob("Objetos/Prendas/DTO/*.php") as $prendas){
            $aux = preg_split('/(Objetos\/Prendas\/DTO\/)/', $prendas);
            $aux = preg_split('/(.php)/', $aux[1]);
            
            //echo $aux[0]."->ESTADO:".PrendaDAO::getPrenda($aux[0])->getM_estado()."<br>";
            
            if(PrendaDAO::getPrenda($aux[0])->getM_estado() == 1){
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
    
    public function bajaPrenda($prenda) {
        $con = new Conexion();
        $sqlQuery = "UPDATE prenda SET estado = 0 WHERE descripcion like '".$prenda."'";
        $con->getConnection()->query($sqlQuery);
        return 1;
    }
}
