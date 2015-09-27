<?php
class editarDatosBasicos extends sessionCommand{
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
				$this->addVar("idPersona", $this->request->idPersona);
				$this->addVar("nombres", $persona->getNombres());
				$this->addVar("ruc", $persona->getRuc());
				$this->addVar("apellidoPaterno", $persona->getApellidoPaterno());
				$this->addVar("apellidoMaterno", $persona->getApellidoMaterno());
				$this->addVar("usuario", $persona->getUserName());
				$this->addVar("comision", $persona->getComision());
				$this->addLayout("admin");
				$this->processTemplate("personas/editarDatosBasicos.html");
				
			}else{
				$fc->redirect("?do=personas.verPortada");
			}
		}else{
				$fc->redirect("?do=personas.verPortada");
		}		
	}
}
?>