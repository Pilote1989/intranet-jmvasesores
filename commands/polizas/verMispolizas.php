<?php
class verMispolizas extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->limpiar){
			$_SESSION["busquedaSolicitudes"]["usuario"]="";
			$_SESSION["busquedaSolicitudes"]["ramo"]="";
			$_SESSION["busquedaSolicitudes"]["fechaInicio"]="";
			$_SESSION["busquedaSolicitudes"]["fechaFin"]="";
			$_SESSION["busquedaSolicitudes"]["limite"]="";
		}
	
		$ramos=Fabrica::getAllFromDB("Ramo");	
		$selectRamo = '<option value=""></option>';
		foreach($ramos as $ramo){
			$selectRamo=$selectRamo.'\n<option value="'.$ramo->getId().'" >'.$ramo->getNombre().'</option>';
		}
		$this->addVar("ramos",$selectRamo);

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
		if($_SESSION["busquedaProgramas"]["ramo"]){
			$this->addVar("ramo",$_SESSION["busquedaProgramas"]["ramo"]);
		}
		else{
			$this->addEmptyVar("ramo");
		}
		
		/* Seleccinar el nombre */	
		if($_SESSION["busquedaProgramas"]["contratante"]){
			$this->addVar("usuario",$_SESSION["busquedaProgramas"]["contratante"]);
		}
		else{
			$this->addEmptyVar("contratante");
		}
		
		/* Limite por pagina */	
		if($_SESSION["busquedaSolicitudes"]["limite"]){
			$this->addVar("limite",$_SESSION["busquedaSolicitudes"]["limite"]);
		}
		else{
			$this->addEmptyVar("limite");
		}
		$this->addLayout("ace");
		$this->processTemplate("polizas/verPolizas.html");
	}
}
?>