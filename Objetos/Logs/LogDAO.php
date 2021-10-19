<?php

class LogDAO {
    public function addLog(LogDTO $log) {
        $con = new Conexion();
        $sqlQuery = "INSERT INTO `log`(`id_usuario`, `id_movimiento`, `accion`, `fecha`) VALUES (?,?,?, CURRENT_TIMESTAMP)";
        $prepare = $con->getConnection()->prepare($sqlQuery);
        $prepare->bind_param("iss",$id_usuario,$id_movimiento, $accion);
        $id_usuario = $log->getM_usuario();
        $id_movimiento = $log->getM_idmovimiento();
        $accion = $log->getM_accion();
        
        $prepare->execute();
    }
    
    public function getLogs() {
        $con = new Conexion();
        $sqlQuery = "SELECT * FROM `log`";
        $statement = $con->getConnection()->query($sqlQuery);
        
        $array = new ArrayObject();
        
        if($statement->num_rows > 0){
           while($row = $statement->fetch_assoc()){
               $log = new LogDTO($row['id_usuario'],$row['id_movimiento'],$row['accion']);
               $log->setFecha($row['fecha']);
               
               $array->append($log);
           }
        }
        
        return $array;
    }
}
