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
        
        public function isEmailValid($email){
            $con = new Conexion();
            $sqlQuery = "SELECT * FROM `usuarios` where correo = '".$email."'";
            $resultado = $con->getConnection()->query($sqlQuery);
            if($resultado->num_rows == 1){
                $row = $resultado->fetch_assoc();
                $usu = new UsuarioDTO(0,0);
                $usu->__constructInitComplete($row['id_usuarios'], $row['contrasenia'], $row['nombre'], $row['apellido'], $row['correo'], $row['fecha_alta'], $row['fecha_baja'], $row['ficha_municipal'], 0);
                
                return $usu;
            }
            else{
                return null;
            }
        }
        
        public function changePassword($email, $contrasenia, $sha1){
            $con = new Conexion();
            $sqlQuery = "UPDATE `usuarios` SET `contrasenia` = '". ($sha1? sha1($contrasenia):$contrasenia )."' WHERE `usuarios`.`correo` like '".$email."'";
            $con->getConnection()->query($sqlQuery);
        }
        
        public function listUsuarios() {
            $con = new Conexion();
            $sqlQuery = "SELECT * FROM `usuarios`";
            $resultados = $con->getConnection()->query($sqlQuery);
            
            $array = new ArrayObject();
            
            if($resultados->num_rows >= 1){
                while($row = $resultados->fetch_assoc()){
                   $usu = new UsuarioDTO(0,0);
                   $usu->__constructInitComplete($row['id_usuarios'], $row['contrasenia'], $row['nombre'], $row['apellido'], $row['correo'], $row['fecha_alta'], $row['fecha_baja'], $row['ficha_municipal'], 0);
                   $usu->setM_rol(RolesDAO::getUserRoles($usu));
                   $array->append($usu);
                }
            }
            
            return $array;
        }
        
        public function editUsuario(UsuarioDTO $usuario, UsuarioDTO $dummy){
            $con = new Conexion();
            $sqlQuery = "UPDATE usuarios SET nombre = ?, apellido = ?, contrasenia = ?, ficha_municipal = ?, correo = ? WHERE id_usuarios = ?";
            $prepare = $con->getConnection()->prepare($sqlQuery);
            $prepare->bind_param("sssisi",$nombre, $apellido,$contrasenia, $ficha, $correo , $id);
            
            $nombre = $dummy->getM_nombre();
            $apellido = $dummy->getM_apellido();
            $contrasenia = $dummy->getM_contrasenia();
            $ficha = $dummy->getM_Ficha_municial();
            $correo = $dummy->getM_correo();
            $id = $usuario->getM_idusuario();
            
            if(!$prepare->execute()){
                echo "error (" . $prepare->errno.") ".$prepare->error;
                return false;
            }
            return true;
        }
        
        public function editFlagUsuario($idusuario){
            $con = new Conexion();
            $sqlQuery = "SELECT fecha_baja FROM `usuarios` WHERE `id_usuarios` = $idusuario";
            $resultados = $con->getConnection()->query($sqlQuery);
            if($resultados->num_rows >= 1){
                $row = $resultados->fetch_assoc();
                $sqlQuery = "UPDATE `usuarios` SET `fecha_baja` = ".($row['fecha_baja'] == null?"CURRENT_DATE":"NULL")." WHERE `usuarios`.`id_usuarios` = ?;";
                $prepare = $con->getConnection()->prepare($sqlQuery);
                $prepare->bind_param("i",$id);
                $id = $idusuario;
                $prepare->execute();
            }
        }
    }
?>