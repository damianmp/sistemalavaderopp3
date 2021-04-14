<?php
    class RolesDAO{
        public function getUserRoles(UsuarioDTO $user) {
            $con = new Conexion();
            $sqlquery = "SELECT r.* FROM rol as r, usuarios as u JOIN usuario_rol as ur on ur.id_usuarios = u.id_usuarios WHERE u.id_usuarios = ".$user->getM_idusuario()." and r.id_rol = ur.id_rol";
            
            $array = new ArrayObject();
            
            $resultados = $con->getConnection()->query($sqlquery);
            if($resultados->num_rows > 0){
                while($row = $resultados->fetch_assoc()){
                    $rol = new RolesDTO($row['id_rol'], $row['descripcion']);
                    $array->append($rol);
                }
            }

            return $array;
        }
        
        public function getAllRoles(){
            $con = new Conexion();
            $sqlquery = "SELECT * FROM rol";
            
            $array = new ArrayObject();
            $resultados = $con->getConnection()->query($sqlquery);
            if($resultados->num_rows > 0){
                while($row = $resultados->fetch_assoc()){
                    $rol = new RolesDTO($row['id_rol'], $row['descripcion']);
                    $array->append($rol);
                }
            }

            return $array;
        }
    }

?>