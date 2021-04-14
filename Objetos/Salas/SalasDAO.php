<?php

class SalasDAO{
    public function getAllSalas(){
        $con  = new Conexion();
        $sqlQuery = "SELECT * FROM `salas`";
        
        $array = new ArrayObject();
        
        $resultado = $con->getConnection()->query($sqlQuery);
        if ($resultado->num_rows >= 1){
            while($row = $resultado->fetch_assoc()) {
                $sala = new SalasDTO();
                $sala->setM_descripcion($row['descripcion']);
                $sala->setM_id($row['id_sala']);
                
                $array->append($sala);
            }
        }
        
        return $array;
    }
    
    public function removeSalaPrenda($sala, $prenda) {
        $con  = new Conexion();
        $sqlQuery = "DELETE FROM prenda_salas WHERE id_salas =".$sala." and id_prenda = ".$prenda."";
        $con->getConnection()->query($sqlQuery);
    }
    
    public function addSala($sala, $prenda) {
        $con  = new Conexion();
        //buscar si esa ese tipo de prenda
        $sqlQuery = "SELECT * FROM prenda_salas WHERE id_prenda = ".$prenda->getM_codigo()." and id_salas = ".$sala->getM_id()."";
        $resultado = $con->getConnection()->query($sqlQuery);
        if($resultado->num_rows == 1){
            //update
            $sqlQuery = "UPDATE prenda_salas SET cantidad = ".$prenda->getM_cantidad()." WHERE id_salas = ".$sala->getM_id()." and id_prenda = ".$prenda->getM_codigo()."";
            $con->getConnection()->query($sqlQuery);
        }
        else{
            $sqlQuery = "INSERT INTO prenda_salas(id_salas, id_prenda, id_estado, cantidad) VALUES (?,?,?,?)";

            $statement = $con->getConnection()->prepare($sqlQuery);
            $statement->bind_param("iiii",$id_salas, $id_prenda, $id_estado, $cantidad);

            $id_salas = $sala->getM_id();
            $id_prenda = $prenda->getM_codigo();
            $id_estado = 2;
            $cantidad = $prenda->getM_cantidad();

            if(!$statement->execute()){
                echo "error (" . $statement->errno.") ".$statement->error;
            }
        }
    }

    public function getSala($id) {
        $con  = new Conexion();
        $sqlQuery = "SELECT * FROM `salas` WHERE id_sala = ".$id."";
        
        $sala = new SalasDTO();
        
        $resultado = $con->getConnection()->query($sqlQuery);
        if ($resultado->num_rows == 1){
            while($row = $resultado->fetch_assoc()) {
                $sala->setM_descripcion($row['descripcion']);
                $sala->setM_id($row['id_sala']);
            }
        }
        
        /*
         * AGARRAR TODAS LAS PRENDAS ASOCIADAS
         */
        
        $sqlQuery = "SELECT p.codigo, p.descripcion, ps.cantidad FROM prenda_salas as ps JOIN prenda as p on p.codigo = ps.id_prenda where ps.id_salas = ".$id."";
        $sala->setM_prendas(new ArrayObject());
        
        $resultado = $con->getConnection()->query($sqlQuery);
        if($resultado->num_rows >= 1){
            while($row = $resultado->fetch_assoc()){
                $prenda = PrendaDAO::getPrendaFromString($row['descripcion']);
                $prenda->setM_cantidad($row['cantidad']);
                $prenda->setM_codigo($row['codigo']);
                $prenda->setM_descripcion($row['descripcion']);
                
                $sala->getM_prendas()->append($prenda);
            }
        }
        return $sala;
    }
}
