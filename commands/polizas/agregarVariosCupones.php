<?php
class agregarVariosCupones extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("idPoliza",$this->request->idPoliza);
		$this->processTemplate("polizas/agregarVariosCupones.html");
		
	}
}
?>