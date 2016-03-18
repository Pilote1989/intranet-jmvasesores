<?php
class oldMenuPolizas extends sessionCommand{
	function execute(){
		// -> Banner
		if($this->checkAccess("crearUsuario", true)){
			$this->addBlock("admin");
		}
		$fc=FrontController::instance();
		$this->addVar("idPoliza",$this->request->idPoliza);
		$this->processTemplate("menus/oldMenuPolizas.html");
	}
}
?>