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
				$this->addVar("errorLogin", "Usuario y/o Clave no validos");
			}
		}else{
			$this->addEmptyVar("errorLogin");
				
		}
		
		$this->addLayout("public");

		$this->processTemplate("login.html");

	}
}
?>