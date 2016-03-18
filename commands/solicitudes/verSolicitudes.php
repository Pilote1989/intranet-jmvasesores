<?php
class verSolicitudes extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$this->addVar("doFalso", $this->request->do);
		$usuario=$this->getUsuario();
		if($this->request->limpiar){
			$_SESSION["busquedaSolicitudes"]["observaciones"]="";
			$_SESSION["busquedaSolicitudes"]["tipo"]="";
			$_SESSION["busquedaSolicitudes"]["fechaInicio"]="";
			$_SESSION["busquedaSolicitudes"]["fechaFin"]="";
			$_SESSION["busquedaSolicitudes"]["limite"]="";
		}
	
		/* Todo para el nombre */	
		if($_SESSION["busquedaSolicitudes"]["observaciones"]){
			$this->addVar("observaciones",$_SESSION["busquedaSolicitudes"]["observaciones"]);
		}
		else{
			$this->addEmptyVar("observaciones");
		}
		/*******/
		
		/* Todo para el fechaInicio */
		if($_SESSION["busquedaSolicitudes"]["fechaInicio"]){
			$this->addVar("fechaInicio",$_SESSION["busquedaSolicitudes"]["fechaInicio"]);
		}
		else{
			$this->addEmptyVar("fechaInicio");
		}

		
		/* Todo para el fechaInicio */
		if($_SESSION["busquedaSolicitudes"]["fechaFin"]){
			$this->addVar("fechaFin",$_SESSION["busquedaSolicitudes"]["fechaFin"]);
		}
		else{
			$this->addEmptyVar("fechaFin");
		}
		
		/* Seleccinar el tipo */	
		if($_SESSION["busquedaProgramas"]["tipo"]){
			$this->addVar("tipo",$_SESSION["busquedaProgramas"]["tipo"]);
		}
		else{
			$this->addEmptyVar("tipo");
		}
		
		/* Seleccinar el nombre */	
		if($_SESSION["busquedaProgramas"]["usuario"]){
			$this->addVar("usuario",$_SESSION["busquedaProgramas"]["usuario"]);
		}
		else{
			$this->addEmptyVar("usuario");
		}
		
		/* Limite por pagina */	
		if($_SESSION["busquedaSolicitudes"]["limite"]){
			$this->addVar("limite",$_SESSION["busquedaSolicitudes"]["limite"]);
		}
		else{
			$this->addEmptyVar("limite");
		}
		if($this->checkAccess("verSolicitudes", true)){
			$this->addBlock("filtroUsuario");
		}else{
			$this->addBlock("noFiltroUsuario");
		}
		$this->addVar("nombre", $usuario->getNombres()." ".$usuario->getApellidoPaterno());
		$this->addLayout("ace");
		$this->processTemplate("solicitudes/verSolicitudes.html");
	}
}
?>