<?php
class editarDatosBasicos extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idRamo){
			$ramo=Fabrica::getFromDB("Ramo",$this->request->idRamo);
			$this->addBlock("bloqueEditarRamos");
			$this->addVar("editar","Editar Datos Básicos");
			$this->addBlock("idRamo");
			$this->addVar("idRamo",$ramo->getId());
			$this->addVar("sigla",$ramo->getSigla());
			$this->addVar("nombre",$ramo->getNombre());
		}else{
			$this->addBlock("bloqueEditarRamos");
			$this->addVar("editar","Crear Nuevo Ramo");
			$this->addEmptyVar("idRamo");
			$this->addEmptyVar("sigla");
			$this->addEmptyVar("nombre");
		}
		$this->addLayout("ace");
		$this->processTemplate("ramos/editarDatosBasicos.html");
	}
}
?>