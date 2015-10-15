<?php
class agregarVehiculo extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("idPoliza", $this->request->idPoliza);
		$this->processTemplate("polizas/agregarVehiculo.html");
		
	}
}
?>