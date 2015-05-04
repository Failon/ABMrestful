<?php


class UsuarisController extends AbstractController{
	protected $constants;

	function __construct(){
		require_once('constants.php');
		$this->constants = $constants;
	}

	public function usuaris($request){
		if(strtolower($request->method) == 'get'){
			if(count($request->url_elements) == 1){
		            try {
		                $model = new Connection($this->constants);
		                $connection = $model->connect();
		                $sql = "SELECT * FROM users";
		                $query = $connection->prepare($sql);
		                $query->execute();
		                $respuesta = array();
		                while ($row = $query->fetch()) {
		                    array_push($respuesta,$row['idusers']." ".$row['name']." ".$row['email']." ".$row['pass']." ".$row['rol']) ;
		                }
		                return $respuesta;
		            }catch(PDOException $e) {
		                return $e->getMessage();
		            }				
		    }else{
		    	return "formato incorrecto para el tipo de peticion";
		    }
								
		}
		else if(count($request->url_elements) > 1){
		    $accion = $request->url_elements;
		    return $this->$accion[1]($request);
		}
		else{
			return "metodo de peticion incorrecto, solo se acepta get";
		}
	}

	public function crearUsuari($request){
		if(strtolower($request->method) == 'post'){
			if(count($request->url_elements) == 2) {
		            try {
		                $model = new Connection($this->constants);
		                $connection = $model->connect();
		                $sql = "INSERT INTO users (name,email,pass,rol) VALUES(:nombre,:email,:password,:rol)";
		                $query = $connection->prepare($sql);
			            $query->bindParam(':nombre', $request->parameters['nombre']);
			            $query->bindParam(':email', $request->parameters['email']);
			            $query->bindParam(':password', $request->parameters['password']);
			            $query->bindParam(':rol', $request->parameters['rol']);		                
		                $query->execute();
			            if(!$query){
			                return $connection->errorInfo();
			            }else{
			                if (!$query->execute()) {
			                    return $query->errorInfo();
			                }else{
			                    return "Inserción realizada con exito";
			                }
			            }
		            }catch(PDOException $e) {
		                return $e->getMessage();
		            }
			}else{
				return "La accion ".$request->url_elements[1]." no requiere parametros adicionales y debe estar precedida por la accion usuaris";
			}
		}
		else{
			return 'Esta accion solo acepta peticiones POST';
		}
	}

	public function login($request){
		if(strtolower($request->method) == 'post'){
			if(count($request->url_elements) == 2) {
		            try {
		                $model = new Connection($this->constants);
		                $connection = $model->connect();
		                $sql = "SELECT * FROM users WHERE name=:usuario AND pass=:clave";
		                $query = $connection->prepare($sql);
		                $query->bindParam(":usuario",$request->parameters['nombre'],PDO::PARAM_STR);
		                $query->bindParam(":clave",$request->parameters['password'],PDO::PARAM_STR);
		                $query->execute();
		                $total = $query->rowCount();
		                if($total==0){
		                    return "Credenciales incorrectas";
		                }else{
		                    $row = $query->fetch();
		                    return "Bienvenido ".$row['name'];
		                }
		            }catch(PDOException $e) {
		                return $e->getMessage();
		            }					
			}else{
					return "La accion ".$request->url_elements[1]." no requiere parametros adicionales y debe estar precedida por la accion usuaris";
			}			
		}else{
			return "Esta accion solo acepta peticiones POST";
		}
	}

	public function actualitzarNom($request){
		if(strtolower($request->method) == 'put'){
			if(count($request->url_elements) == 3) {
		            try {
		                $model = new Connection($this->constants);
		                $connection = $model->connect();
		                $sql = "UPDATE users SET name=:usuario WHERE idusers=:idusuario";
		                $query = $connection->prepare($sql);
		                $query->bindParam(":usuario",$request->parameters['nombre'],PDO::PARAM_STR);
		                $query->bindParam(":idusuario",$request->url_elements[2],PDO::PARAM_STR);
		                $query->execute();
		                $total = $query->rowCount();
		                if($total==0){
		                   return "Error al actualizar el usuario.";
		                }else{
		                    return "Usuario actualizado correctamente.";
		                }
		            }catch(PDOException $e) {
		                return $e->getMessage();
		            }					
			}else{
					return "La accion ".$request->url_elements[1]." requiere el parametro idusuario y debe estar precedida por la accion usuaris";
			}	
		}else{
			return "Esta accion solo acepta peticiones PUT";
		}	
	}
	public function esborrarUsuari($request){
		if(strtolower($request->method) == 'delete'){
			if(count($request->url_elements) == 3) {
		            try {
		                $model = new Connection($this->constants);
		                $connection = $model->connect();
		                $sql = "DELETE FROM users WHERE idusers=:idusuario";
		                $query = $connection->prepare($sql);
		                $query->bindParam(":idusuario",$request->url_elements[2],PDO::PARAM_STR);
		                $query->execute();
		                $total = $query->rowCount();
		                if($total==0){
		                   return "Error al eliminar el usuario.";
		                }else{
		                    return "Usuario eliminao correctamente.";
		                }
		            }catch(PDOException $e) {
		                return $e->getMessage();
		            }						
			}else{
					return "La accion ".$request->url_elements[1]." requiere el parametro idusuario y debe estar precedida por la accion usuaris";
			}			
		}else{
			return "Esta accion solo acepta peticiones DELETE";
		}
	}
}

?>