<?php
class oldMenuUsuario extends SessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$user = $this->getUsuario();
		$this->addVar("nombreUsuario", $user->getNombres());
		$this->processTemplate("menus/oldMenuUsuario.html");
	}
}
?>
