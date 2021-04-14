<?php

class PrendaDAO{
    public function getCountPrenda($id, $mov) {
        $con = new Conexion();
        $sqlQury = "SELECT SUM(cantidad) as sumatoria FROM `prenda_salas` WHERE `id_prenda` = ".$id." and id_movimiento like '".$mov."'";
        
        $resultado = $con->getConnection()->query($sqlQury);
        if($resultado->num_rows == 1){
            $row = $resultado->fetch_assoc();
            
            return $row['sumatoria'];
        }
    }
    
    public function getHTMLAllPrendas() {
        
        $array = new ArrayObject();
        
        foreach (glob("Objetos/Prendas/DTO/*.php") as $prendas){
            $aux = preg_split('/(Objetos\/Prendas\/DTO\/)/', $prendas);
            $aux = preg_split('/(.php)/', $aux[1]);
            $array->append($aux);
            
        }
        return $array;
    }
    
    public function addPrenda($prenda){
         
    }
    
    public function getPrenda($prenda) : Prenda{
        $con = new Conexion();
        $sqlQuery = "SELECT p.codigo, p.descripcion as prenda FROM prenda as p WHERE p.descripcion like '".$prenda."'";
        $resultado = $con->getConnection()->query($sqlQuery);
        
        $aux = new $prenda;

        if($resultado->num_rows == 1){
            while($row = $resultado->fetch_assoc()){
                $aux->setM_codigo($row['codigo']);
                $aux->setM_descripcion($row['prenda']);
            }
        }
        return $aux;
    }
    
    public function getPrendaFromString($strPrenda) : Prenda{
        $auxP = PrendaDAO::getPrenda($strPrenda);
        
        return $auxP;
    }
}
