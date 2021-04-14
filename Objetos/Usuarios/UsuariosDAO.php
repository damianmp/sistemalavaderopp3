<?php
    class UsuarioDAO{
        public function InicioSesion(UsuarioDTO $user) {
            $con = new Conexion();
            
            /*
             * El usuario es la ficha municipal, con el email se recupera la cuenta
             */
            
            $sqlquery = "SELECT * from usuarios AS u WHERE u.ficha_municipal LIKE '".$user->getM_Ficha_municial()."' AND u.contrasenia LIKE '".$user->getM_contrasenia()."'";
            
            $resultado = $con->getConnection()->query($sqlquery);
            
            if ($resultado->num_rows == 1) {
                while($row = $resultado->fetch_assoc()) {
                    
                    $aux = new UsuarioDTO(0,0);
                    $aux->__constructInitComplete($row['id_usuarios'], $row['contrasenia'], $row['nombre'], $row['apellido'], $row['correo'], $row['fecha_alta'], $row['fecha_baja'], $row['ficha_municipal'], 0);
                    
                    $raux = new RolesDAO();
                    $rol = $raux->getUserRoles($aux);
                    
                    $aux->setM_rol($rol);
                    
                    return $aux;
                }
            }
            else{
                return null;
            }
        } 
    }
?>