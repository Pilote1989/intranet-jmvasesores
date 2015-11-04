<?php
class login extends BaseCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$cliente = Fabrica::getFromDB("Cliente",1);
		// Si ocurrio un error
		if($this->request->error){
			$this->addVar("errorLogin", "Error de Login");
			if($this->request->error == 1){
				$this->addBlock("error");
				$this->addVar("errorLogin", "Usuario y/o contraseña no validos");
			}else if($this->request->error == 2){
				$this->addBlock("error");
				$this->addVar("errorLogin", "Ingrese sus datos");
			}
		}else{
			$this->addEmptyVar("errorLogin");
				
		}
		
		$this->addLayout("publicLight");

		$this->processTemplate("loginLight.html");

	}
}
?>