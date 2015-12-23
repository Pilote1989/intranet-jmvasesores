<?php
class generaSBS extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$this->addLayout("admin");
		$this->processTemplate("reportes/generaSBS.html");
	}
}
?>