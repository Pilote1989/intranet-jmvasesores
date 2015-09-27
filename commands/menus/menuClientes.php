<?php
class menuClientes extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$this->addVar("idCliente",$this->request->idCliente);
		$this->processTemplate("menus/menuClientes.html");
	}
}
?>