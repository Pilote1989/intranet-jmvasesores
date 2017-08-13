<?php
class procesarCrearGuardarEnPoliza extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$response["respuesta"]="FAIL";	
		$fc->import("lib.Cliente");
		$fc->import("lib.ClienteEnPoliza");
		if($this->request->idPoliza){
			$cliente=new Cliente();				
			$cliente->setNombre($this->request->nombre);
			$cliente->setDireccion($this->request->direccion);
			$cliente->setCorreo($this->request->correo);
			$cliente->setDistrito($this->request->distrito);
			if($this->request->tipoDocu == "SD"){
				$query = "
					SELECT MAX( CAST(  `doc` AS UNSIGNED ) ) AS maximo
					FROM  `Cliente` 
					WHERE  `tipoDoc` =  'TEMP'				
				";
				$query=utf8_decode($query);		
				$link=&$this->fc->getLink();	
				$suma = array();			
				if($result=$link->query($query)){
					while($row=$result->fetch_assoc()){
						$suma[]=$row;
					}			
				}else{
					printf("Error: %s\n", $link->error);
					return null;
				}
				$cliente->setDoc($suma[0]["maximo"] + 1);
				$response["doc"] = $suma[0]["maximo"];
				$cliente->setTipoDoc("TEMP");
			}else{
				$cliente->setDoc($this->request->doc);
				$cliente->setTipoDoc($this->request->tipoDocu);
			}
			$cliente->setIdPersona("1");
			$cliente->setFechaDeCreacion(date('Y',time()) . "/" . date('m',time()). "/" . date('d',time()));
			$cliente->storeIntoDB();
			$dbLink=&FrontController::instance()->getLink();
			$dbLink->next_result();
			$id=$dbLink->insert_id;
			$dbLink->next_result();
			$clienteEnPoliza = new ClienteEnPoliza();
			$clienteEnPoliza->setIdPoliza($this->request->idPoliza);
			$clienteEnPoliza->setIdCliente($id);
			$clienteEnPoliza->storeIntoDB();			
			$response["respuesta"]="SUCCESS";
		}
		echo json_encode($response);
	}
}
?>