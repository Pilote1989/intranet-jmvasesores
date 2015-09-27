<?php
class menuUsuario extends SessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$user = $this->getUsuario();
		$this->addVar("nombreUsuario", $user->getNombres());
		$this->processTemplate("menus/menuUsuario.html");
	}
}
?>
