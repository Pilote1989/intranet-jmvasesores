<?php
class estadoUsuario extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		if($this->checkAccess("cambiarClaves", true) && !(is_null($this->request->idPersona))){
			$persona = Fabrica::getFromDB("Persona",$this->request->idPersona);
			if($persona->getHabilitado() == "1"){
				$persona->setHabilitado("0");
			}else{
				$persona->setHabilitado("1");
			}
			$persona->storeIntoDB();
			$fc->redirect("?do=personas.verDatosBasicos&idPersona=".$this->request->idPersona);
		}else{
			$fc->redirect("?do=personas.verPortada");
		}
	}
}
?>