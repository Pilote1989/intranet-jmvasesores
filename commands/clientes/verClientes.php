<?php
class verClientes extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->limpiar){
			$_SESSION["busquedaClientes"]["nombre"]="";
			$_SESSION["busquedaClientes"]["doc"]="";
		}
	
		/* Seleccinar el nombre */	
		if($_SESSION["busquedaClientes"]["nombre"]){
			$this->addVar("nombre",$_SESSION["busquedaClientes"]["nombre"]);
		}
		else{
			$this->addEmptyVar("nombre");
		}
		
		if($_SESSION["busquedaClientes"]["doc"]){
			$this->addVar("doc",$_SESSION["busquedaClientes"]["doc"]);
		}
		else{
			$this->addEmptyVar("doc");
		}
		
		/* Limite por pagina */	
		if($_SESSION["busquedaClientes"]["limite"]){
			$this->addVar("limite",$_SESSION["busquedaClientes"]["limite"]);
		}
		else{
			$this->addEmptyVar("limite");
		}
		$this->addLayout("admin");
		$this->processTemplate("clientes/verClientes.html");
	}
}
?>