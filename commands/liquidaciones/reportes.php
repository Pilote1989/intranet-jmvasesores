<?php
class reportes extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$this->addLayout("admin");
		$this->processTemplate("liquidaciones/reportes.html");
	}
}
?>