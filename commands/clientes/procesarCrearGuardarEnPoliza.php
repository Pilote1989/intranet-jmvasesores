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
			$cliente->setDoc($this->request->doc);
			$cliente->setTipoDoc($this->request->tipoDoc);
			$cliente->setIdPersona("1");
			$cliente->setFechaDeCreacion(date('Y',time()) . "/" . date('m',time()). "/" . date('d',time()));
			$cliente->storeIntoDB();
			$dbLink=&FrontController::instance()->getLink();
			$id=$dbLink->insert_id;
			$clienteEnPoliza = new ClienteEnPoliza();
			$clienteEnPoliza->setIdPoliza($this->request->idPoliza);
			$clienteEnPoliza->setIdCliente($id);
			$clienteEnPoliza->storeIntoDB();			
			//$dbLink=&FrontController::instance()->getLink();			
			//$id=$dbLink->insert_id;
			$response["respuesta"]="SUCCESS";
		}
		echo json_encode($response);
	}
}
?>