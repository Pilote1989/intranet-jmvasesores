<?php
class agregar extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$this->addVar("doFalso", $this->request->do);
		$usuario=$this->getUsuario();
		$this->addLayout("ace");
		$this->processTemplate("compras/agregar.html");
		
	}
}
?>