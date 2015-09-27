<?php
class verCompanias extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->limpiar){
			$_SESSION["busquedaCompanias"]["nombre"]="";
		}
	
		/* Seleccinar el nombre */	
		if($_SESSION["busquedaCompanias"]["nombre"]){
			$this->addVar("nombre",$_SESSION["busquedaCompanias"]["nombre"]);
		}
		else{
			$this->addEmptyVar("nombre");
		}
		
		/* Limite por pagina */	
		if($_SESSION["busquedaCompanias"]["limite"]){
			$this->addVar("limite",$_SESSION["busquedaCompanias"]["limite"]);
		}
		else{
			$this->addEmptyVar("limite");
		}
		$this->addLayout("admin");
		$this->processTemplate("companias/verCompanias.html");
	}
}
?>