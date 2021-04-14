<?php
	class Conexion {
		private static $con = null;
		
		public function __construct(){}
		
		public static function getConnection(){
			if(self::$con == null){
				self::$con = new mysqli("127.0.0.1", "pp3", "1234", "pp3");
                                
				if (self::$con->connect_errno) {
					printf("Conexion fallida: %s\n", self::$con->connect_error);
					exit();
				}
			}
			return self::$con;
		}
	}
?>