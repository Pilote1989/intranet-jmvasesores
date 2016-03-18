<?php
class oldMenuClientes extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$this->addVar("idCliente",$this->request->idCliente);
		$this->processTemplate("menus/oldMenuClientes.html");
	}
}
?>