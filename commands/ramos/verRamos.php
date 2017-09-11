<?php
class verRamos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->limpiar){
			$_SESSION["busquedaRamos"]["nombre"]="";
		}
	
		/* Seleccinar el nombre */	
		if($_SESSION["busquedaRamos"]["nombre"]){
			$this->addVar("nombre",$_SESSION["busquedaRamos"]["nombre"]);
		}
		else{
			$this->addEmptyVar("nombre");
		}
		
		/* Limite por pagina */	
		if($_SESSION["busquedaRamos"]["limite"]){
			$this->addVar("limite",$_SESSION["busquedaRamos"]["limite"]);
		}
		else{
			$this->addEmptyVar("limite");
		}
		$this->addLayout("ace");
		$this->processTemplate("ramos/verRamos.html");
	}
}
?>