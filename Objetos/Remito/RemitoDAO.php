<?php
class RemitoDAO{
    public function AddRemito($id_movimiento){
        $con = new Conexion();
        $sqlQuery = "SELECT "
                . "m.id_movimiento as movimiento, "
                . "u.nombre as nombre, "
                . "u.apellido as apellido "
                . "FROM "
                . "movimiento as m "
                . "join "
                . "usuarios as u "
                . "WHERE id like '".$id_movimiento."' and u.id_usuarios = m.id_usuarios";
        $resultados = $con->getConnection()->query($sqlQuery);
        if($resultados->num_rows > 0){
            $row = $resultados->fetch_assoc();
            
            $sqlQuery = "select * from remito where id_movimiento = ".$row['movimiento'];
            $resultados = $con->getConnection()->query($sqlQuery);
            
            if($resultados->num_rows == 0){
                $sqlQuery = "INSERT INTO `remito`(`id_movimiento`, fecha) VALUES (?, CURRENT_TIMESTAMP)";
                $statement = $con->getConnection()->prepare($sqlQuery);
                $statement->bind_param("i", $id_mov);

                $id_mov = $row['movimiento'];
                if(!$statement->execute()){
                    echo "error (" . $statement->errno.") ".$statement->error;
                }
                
                $sqlQuery = "SELECT * from remito where id_movimiento = ".$row['movimiento'];
                $resultados = $con->getConnection()->query($sqlQuery);
                if($resultados->num_rows > 0){
                    $row = $resultados->fetch_assoc();
                    return $row['id']; 
                }
            }
            
            $row = $resultados->fetch_assoc();
            return $row['id'];
        }
    }
}