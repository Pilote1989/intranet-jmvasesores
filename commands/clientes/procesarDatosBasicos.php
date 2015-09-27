<?php
class procesarDatosBasicos extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cliente");
		
		$new = true;
		if($this->request->idCliente){
			if($this->request->idCliente=="nan"){
				$cliente=new Cliente();				
			}else{
				$cliente=Fabrica::getFromDB("Cliente",$this->request->idCliente);
				$new = false;
			}
		}else{
			$cliente=new Cliente();
		}
		
		$cliente->setNombre($this->request->nombre);
		$cliente->setDireccion($this->request->direccion);
		$cliente->setCorreo($this->request->correo);
		$cliente->setCorreoAlternativo($this->request->correo2);
		$cliente->setDistrito($this->request->distrito);
		$cliente->setDoc($this->request->doc);
		$cliente->setTipoDoc($this->request->tipoDoc);
		$cliente->setIdPersona($this->request->asesor);
		$cliente->setFechaDeCreacion(date('Y',time()) . "/" . date('m',time()). "/" . date('d',time()));
		
		$cliente->storeIntoDB();
		
		$dbLink=&FrontController::instance()->getLink();
		
		if($new){
			$id=$dbLink->insert_id;
		}else{
			$id=$cliente->getId();
		}
		
		$fc->redirect("?do=clientes.verDatosBasicos&idCliente=".$id);
	}
}
?>