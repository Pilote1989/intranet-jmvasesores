<?php
class especial extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$this->addLayout("ace");
		$this->processTemplate("reportes/especial.html");
	}
}
?>