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
    
    public function removeSalaPrenda($sala, $prenda, $id) {
        $con  = new Conexion();
        $sqlQuery = "DELETE FROM prenda_salas WHERE id_salas =".$sala." and id_prenda = ".$prenda." and id_movimiento like '".$id."' and id_estado = 2";
        $con->getConnection()->query($sqlQuery);
    }
    
    public function removeSalaPrendaLimpia($sala, $prenda, $cantidad) {
        $con  = new Conexion();
        $sqlQuery = "DELETE FROM prenda_salas WHERE id_salas =".$sala." and id_prenda = ".$prenda." and id_estado = 1 and cantidad = ".$cantidad;
        //echo $sqlQuery;
        $con->getConnection()->query($sqlQuery);
    }
    
    public function addSala($sala, $prenda, $id) {
        $con  = new Conexion();
        //buscar si esa ese tipo de prenda
        $sqlQuery = "SELECT * FROM prenda_salas WHERE id_prenda = ".$prenda->getM_codigo()." and id_salas = ".$sala->getM_id()." and id_movimiento like '".$id."' and id_estado = 2";
        $resultado = $con->getConnection()->query($sqlQuery);
        if($resultado->num_rows == 1){
            //update
            $row = $resultado->fetch_assoc();
            $cantidad = $prenda->getM_cantidad() + $row['cantidad'];
            
            $sqlQuery = "UPDATE prenda_salas SET cantidad = ".$cantidad." WHERE id_salas = ".$sala->getM_id()." and id_prenda = ".$prenda->getM_codigo()." and id_movimiento like '".$id."' and id_estado = 2";
            $con->getConnection()->query($sqlQuery);
        }
        else{
            $sqlQuery = "INSERT INTO prenda_salas(id_salas, id_prenda, id_estado, cantidad, id_movimiento) VALUES (?,?,?,?,?)";

            $statement = $con->getConnection()->prepare($sqlQuery);
            $statement->bind_param("iiiis",$id_salas, $id_prenda, $id_estado, $cantidad, $m_id);

            $id_salas = $sala->getM_id();
            $id_prenda = $prenda->getM_codigo();
            $id_estado = 2;
            $cantidad = $prenda->getM_cantidad();
            $m_id = $id;

            if(!$statement->execute()){
                echo "error (" . $statement->errno.") ".$statement->error;
            }
        }
    }

    public function getSala($id, $mov) {
        $con  = new Conexion();
        $sqlQuery = "SELECT * FROM `salas` WHERE id_sala = ".$id;
        
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
        
        $sqlQuery = "SELECT p.codigo, p.descripcion, ps.cantidad FROM prenda_salas as ps JOIN prenda as p on p.codigo = ps.id_prenda where ps.id_salas = ".$id." and id_movimiento like '".$mov."' and id_estado = 2";
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
    
    public function getSalaLimpia($id) {
        $con  = new Conexion();
        $sqlQuery = "SELECT * FROM `salas` WHERE id_sala = ".$id;
        
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

        $sqlQuery = "SELECT p.codigo, p.descripcion, ps.cantidad FROM prenda_salas as ps JOIN prenda as p on p.codigo = ps.id_prenda where ps.id_salas = ".$id." and id_estado = 1";
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
    
    public function addUsuarioPrendaSalas(UsuarioDTO $usuario, $id_movimento) {
        $con = new Conexion();
        $sqlQuery = "INSERT INTO `usuarios_movimiento`(`usuario`, `prenda_salas`) VALUES (?,?)";
        
        $statement = $con->getConnection()->prepare($sqlQuery);
        $statement->bind_param("is", $id_usuario, $id_predasalas);
        $id_usuario = $usuario->getM_idusuario();
        $id_predasalas = $id_movimento;
        
        if(!$statement->execute()){
            echo "error (" . $statement->errno.") ".$statement->error;
        }
    }
    
    public function getUsuarioPrendaSalas(UsuarioDTO $usuario) {
        $con = new Conexion();
        $sqlQuery = "SELECT * FROM `usuarios_movimiento` WHERE `usuario` = ".$usuario->getM_idusuario();
        $resultado = $con->getConnection()->query($sqlQuery);
        if($resultado->num_rows == 1){
            $row = $resultado->fetch_assoc();
            return $row['prenda_salas'];
        }
        return null;
    }
    
    public function removeUsuarioPrendaSalas(UsuarioDTO $usuario) {
        $con = new Conexion();
        $sqlQuery = "DELETE FROM `usuarios_movimiento` WHERE `usuario` = ".$usuario->getM_idusuario();
        $con->getConnection()->query($sqlQuery);
    }
}
