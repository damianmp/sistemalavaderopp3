<?php

class DepositoDAO{
    public function RefreshDeposito(Ds\Map $mapa){
        $llaves = $mapa->keys();
        $valores = $mapa->values();
        $cantidad = $mapa->capacity()/2;

        $con = new Conexion();

        $mapa_deposito = PrendaDAO::getPrendaDeposito();

        for($i = 0; $i < $cantidad; $i++){
            $aux = null;
            $aux2 = PrendaDAO::getPrenda($llaves[$i]);

            foreach($mapa_deposito->keys() as $deposito_prenda){
                if($deposito_prenda instanceof $aux2){
                    $aux = $deposito_prenda;
                }

            }

            if($aux != null){
                $sqlString = "UPDATE deposito SET cantidad = ? WHERE id_prenda = ?";
                $statement = $con->getConnection()->prepare($sqlString);
                $statement->bind_param("ii", $prenda_cantidad, $id_prenda);
                $prenda_cantidad = $valores[$i] + $aux->getM_cantidad();
                $id_prenda = PrendaDAO::getPrenda($llaves[$i])->getM_codigo();
                $statement->execute();
            }
        }
    }
    
    public function getPrendaSucias(Prenda $prenda) {
        $sqlQuery = "select sum(pa.cantidad) as 'cantidad' from prenda_salas as pa join movimiento as m on pa.id_movimiento like m.id where pa.id_prenda = ".$prenda->getM_codigo()." and pa.id_estado = 2";
        $con = new Conexion();
        $statement = $con->getConnection()->query($sqlQuery);
        $row = $statement->fetch_assoc();
        if($row['cantidad'] == null){
            return 0;
        }
        else{
            return $row['cantidad'];
        }
    }
    
    public function getPrendasSinEnviar(Prenda $prenda) {
        /*
         * Buscar entre los movimientos, las prendas NO procesadas
         */
        $con = new Conexion();
        
        $sqlQuery = "SELECT sum(ps.cantidad) as total FROM `usuarios_movimiento` as um join prenda_salas as ps on um.prenda_salas like ps.id_movimiento WHERE ps.id_prenda = ".$prenda->getM_codigo()." and ps.id_estado = 2";
        
        $resultados = $con->getConnection()->query($sqlQuery);
        if($resultados->num_rows > 0){
            $row = $resultados->fetch_assoc();
            return $row['total'];
        }
    }
    
    public function getPrendaLimpias(Prenda $prenda) {
        //saco las tandas limpias de las salas
        /*
         *      SALA ->array( PRENDA limpia )
         */
        $array = new ArrayObject();
        $allsalas = SalasDAO::getAllSalas();
        foreach($allsalas as $sala){
            $aux = SalasDAO::getSalaLimpia($sala->getM_id());
            if(self::getCountPrendas($aux)> 0 && self::isPrendaInSala($aux, $prenda)){
                $array->append($aux);
            }
        }
        return $array;
    }
    
    private function getCountPrendas(SalasDTO $sala) {
        $x = 0;
        $prendas = $sala->getM_prendas();
        foreach($prendas as $aux){
            $x++;
        }
        return $x;
    }
    
    private function isPrendaInSala(SalasDTO $sala, Prenda $prenda) {
        $prendas = $sala->getM_prendas();
        foreach($prendas as $aux){
            if($aux->getM_codigo() == $prenda->getM_codigo()){
                return true;
            }
        }
        return false;
    }
}
?>

