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
}
?>

