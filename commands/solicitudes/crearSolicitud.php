<?php
class crearSolicitud extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$this->addVar("doFalso", $this->request->do);
		if(!$usuario=$this->getUsuario()){
			$fc->redirect("?do=home");
		}
		$persona = Fabrica::getFromDB('Persona', $usuario->getId());
		// Chequea si tiene perfiles Asociados
		if(!$perfiles = $usuario->getPerfiles()){
			$fc->redirect("?do=home");
		}
		//$perfiles = Fabrica::getAllFromDB('PersonaEnPerfil',array("idPersona = " . $usuario->getId()));
		//foreach($perfiles as $perfil){
			//$nombre = Fabrica::getFromDB('Perfil',$perfil->getIdPerfil())->getNombre();
			//if($nombre == "Coordinador de Plan" || $nombre == "Coordinador de Asentamiento"){
				//$this->addBlock("nuevaPortada");
			//}
		//}
		// Nombre
		$this->addVar("nombre", $usuario->getNombres()." ".$usuario->getApellidoPaterno());
		$this->addLayout("ace");
		$this->processTemplate("solicitudes/crearSolicitud.html");
	}
}
?>