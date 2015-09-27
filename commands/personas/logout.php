<?php
class logout extends SessionCommand{
	function execute(){
		$fc=&FrontController::instance();
		$fc->import("lib.Persona");
		
		Fabrica::deleteFromSession("visitante");
		Fabrica::deleteFromSession("URL_ACTUAL");
		
		$fc->redirect("/");
	}
}