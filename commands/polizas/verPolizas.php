<?php
class verPolizas extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->limpiar){
			$_SESSION["busquedaPolizas"]["contratante"]="";
			$_SESSION["busquedaPolizas"]["ramo"]="";
			$_SESSION["busquedaPolizas"]["fechaInicio"]="";
			$_SESSION["busquedaPolizas"]["fechaFin"]="";
			$_SESSION["busquedaPolizas"]["limite"]="";
		}
	
		$ramos=Fabrica::getAllFromDB("Ramo");	
		$selectRamo = '<option value=""></option>';
		foreach($ramos as $ramo){
			$selectRamo=$selectRamo.'\n<option value="'.$ramo->getId().'" >'.$ramo->getNombre().'</option>';
		}
		$this->addVar("ramos",$selectRamo);

		/* Todo para el fechaInicio */
		if($_SESSION["busquedaPolizas"]["fechaInicio"]){
			$this->addVar("fechaInicio",$_SESSION["busquedaPolizas"]["fechaInicio"]);
		}
		else{
			$this->addEmptyVar("fechaInicio");
		}

		
		/* Todo para el fechaInicio */
		if($_SESSION["busquedaPolizas"]["fechaFin"]){
			$this->addVar("fechaFin",$_SESSION["busquedaPolizas"]["fechaFin"]);
		}
		else{
			$this->addEmptyVar("fechaFin");
		}
		
		/* Seleccinar el tipo */	
		if($_SESSION["busquedaPolizas"]["ramo"]){
			$this->addVar("ramo",$_SESSION["busquedaPolizas"]["ramo"]);
		}
		else{
			$this->addEmptyVar("ramo");
		}
		
		/* Seleccinar el nombre */	
		if($_SESSION["busquedaPolizas"]["contratante"]){
			$this->addVar("contratante",$_SESSION["busquedaPolizas"]["contratante"]);
		}
		else{
			$this->addEmptyVar("contratante");
		}
		
		/* Limite por pagina */	
		if($_SESSION["busquedaPolizas"]["limite"]){
			$this->addVar("limite",$_SESSION["busquedaPolizas"]["limite"]);
		}
		else{
			$this->addEmptyVar("limite");
		}
		//print_r($_SESSION);
		$this->addLayout("ace");
		$this->processTemplate("polizas/verPolizas.html");
	}
}
?>