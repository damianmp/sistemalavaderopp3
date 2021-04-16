<?php

class MovimientoDAO{
    public function addMovimento($usuario, $sub_total, $mov) {
        $con = new Conexion();
        $sqlQuery = "INSERT INTO movimiento(id_usuarios, id, total, fecha) VALUES (?,?,?, CURRENT_TIMESTAMP)";
        $statement = $con->getConnection()->prepare($sqlQuery);
        $statement->bind_param("isi", $id_usuario, $id, $total);
        $id_usuario = $usuario->getM_idusuario();
        $id = $mov;
        $total = $sub_total;
        if(!$statement->execute()){
            echo "error (" . $statement->errno.") ".$statement->error;
        }
    }

    public function getMovimientos($busqueda) {
        $array = new ArrayObject();
        $sqlQuery = "SELECT * FROM `movimiento`";
        
        $con = new Conexion();
        $resulset = $con->getConnection()->query($sqlQuery);
        if($resulset->num_rows >= 1){
            while($row = $resulset->fetch_assoc()){
                $aux = new MovimientoDTO();
                $aux->setId_user($row['id_usuarios']);
                $aux->setTotal($row['total']);
                $aux->setId($row['id']);
                $aux->setFecha($row['fecha']);
                
                $array->append($aux);
            }
        }
        
        return $array;
    }
}