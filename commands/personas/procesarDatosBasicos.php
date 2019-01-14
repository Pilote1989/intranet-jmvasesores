<?php
class procesarDatosBasicos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$this->addVar("doFalso", $this->request->do);		
		$fc->import("lib.Persona");
		$fc->import("lib.PersonaEnPerfil");
		$usuario=$this->getUsuario();
		$this->checkAccess("crearUsuario");
		if($this->checkAccess("crearUsuario")){
			if($this->request->idPersona){
				$persona = Fabrica::getFromDB("Persona", $this->request->idPersona);
				$persona->setNombres($this->request->nombres);
				$persona->setApellidoPaterno($this->request->apellidoPaterno);
				$persona->setApellidoMaterno($this->request->apellidoMaterno);
				$persona->setRuc($this->request->ruc);
				$persona->setUserName($this->request->usuario);
				$persona->setComision($this->request->comision);
				if($this->request->vendedor){
					$persona->setVendedor(1);
				}else{
					$persona->setVendedor(0);
				}
				$persona->storeIntoDB();
				$fc->redirect("?do=personas.verDatosBasicos&idPersona=" . $this->request->idPersona);
			}else{
				$fc->redirect("?do=personas.verPortada");
			}
		}else{
				$fc->redirect("?do=personas.verPortada");
		}		
	}
}
?>