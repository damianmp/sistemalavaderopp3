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
        if(!strcmp($busqueda, '*')){
            $sqlQuery = "SELECT * FROM `movimiento`";
        }
        else{
            $sqlQuery = "SELECT * FROM movimiento WHERE id like '".$busqueda."'";
        }
        
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
    
    public function getSalaPrendaFromMovimiento($mov) {
        $con = new Conexion();
        $sqlQuery = "SELECT s.id_sala, s.descripcion as sala, p.codigo, p.descripcion, ps.cantidad FROM prenda_salas as ps join prenda as p on p.codigo = ps.id_prenda join salas as s on s.id_sala = ps.id_salas where id_movimiento like '".$mov."'";
        
        $array = new ArrayObject();
        
        $resultado = $con->getConnection()->query($sqlQuery);
        if($resultado->num_rows >=1 ){
            while($row = $resultado->fetch_assoc()){
                $sala = new SalasDTO();
                $sala->setM_id($row['id_sala']);
                $sala->setM_descripcion($row['sala']);
                
                $aux = self::buscarSala($array, $sala);
                
                if($aux != null)
                    $sala = $aux;
                
                if($sala->getM_prendas() == null)
                    $sala->setM_prendas (new ArrayObject());
                
                $prenda = new $row['descripcion'];
                $prenda->setM_descripcion($row['descripcion']);
                $prenda->setM_codigo($row['codigo']);
                $prenda->setM_cantidad($row['cantidad']);
                $sala->getM_prendas()->append($prenda);
                
                if($aux == null)
                    $array->append($sala);
            }
        }
        return $array;
    }
    
    private function buscarSala($array, $sala) {
        foreach ($array as $aux){
            if($sala->getM_id() == $aux->getM_id()){
                return $aux;
            }
        }
        return null;
    }
}