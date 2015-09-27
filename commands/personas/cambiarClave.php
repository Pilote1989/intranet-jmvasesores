<?php
class cambiarClave extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->mail){
			$personas=Fabrica::getAllFromDB("Persona",array("mail='".$this->request->mail."'"));
			// Si viene del correo
			if($this->request->seed){
				if(count($personas)>0){
					$persona=$personas[0];
					if($this->request->seed == $persona->getPassword()){
						$this->addBlock("mostrarForm");
						$this->addEmptyVar("errorUsuario");
						$this->addEmptyVar("errorLogin");
						$this->addVar("mail",$persona->getMail());
					}else{
						$this->addVar("errorUsuario", "La clave ya ha sido modificada");
					}
				}else{
					$this->addVar("errorUsuario", "No existe el usuario");
				}
			}elseif($this->request->password){
				if(count($personas)>0){
					$persona=$personas[0];
					$persona->setPassword($this->request->password, "MD5");	
					$persona->storeIntoDB();
					// Dependiendo de donde esté el usuario se muestra el mensaje
					$this->addVar("errorUsuario", "Se ha cambiado la Clave.");
					$this->addEmptyVar("errorLogin");
				}else{
					$this->addBlock("mostrarForm");
					$this->addEmptyVar("errorUsuario");
					$this->addVar("errorLogin", "No existe el usuario");
					$this->addVar("mail", $this->request->mail);
				}
			}else{
				$this->addVar("errorUsuario", "Intente Nuevamente");
			}
			$this->addBlock("admin");
			$this->addLayout("admin");
			$this->processTemplate("personas/cambiarClave.html");
		}else{
			$fc->redirect("?do=login");
		}
	}
}
?>