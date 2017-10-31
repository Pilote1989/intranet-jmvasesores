<?php
class autentificar extends SessionCommand{
	function execute(){
		$fc=&FrontController::instance();
		$fc->import("lib.Persona");
			
		// Si es login de Administracion
		if(!isset($_GET['login'])){
			if(Persona::login($this->request->mail, $this->request->password)){
				$usuario=$this->getUsuario();
				//Si no tiene acceso
				if(isset($_SESSION["URL_ACTUAL"])){
					$fc->redirect($_SESSION["URL_ACTUAL"]);
				}else{
					$fc->redirect("?do=personas.verPortada");
				}
			}else{
				error_log("Error de Login: User: " . $this->request->mail . " - Pass: ". $this->request->password, 0);
				$fc->redirect("?do=login&error=2");
			}
		}else{
			error_log("Error de Login: User: " . $this->request->mail . " - Pass: ". $this->request->password, 0);
			$fc->redirect("?do=login&error=1");			
		}
	}
}
?>