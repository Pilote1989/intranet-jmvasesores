<?php
class verPolizas extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->limpiar){
			$_SESSION["busquedaPolizas"]["contratante"]="";
			$_SESSION["busquedaPolizas"]["ramo"]="";
			$_SESSION["busquedaPolizas"]["compania"]="";
			$_SESSION["busquedaPolizas"]["vendedor"]="";
			$_SESSION["busquedaPolizas"]["fechaInicio"]="";
			$_SESSION["busquedaPolizas"]["fechaFin"]="";
			$_SESSION["busquedaPolizas"]["limite"]="";
		}
	
		$ramos=Fabrica::getAllFromDB("Ramo",array(),"nombre ASC");	
		$selectRamo = '<option value=""></option>';
		foreach($ramos as $ramo){
			$selectRamo=$selectRamo.'\n<option value="'.$ramo->getId().'" >'.$ramo->getNombre().'</option>';
		}
		$this->addVar("ramos",$selectRamo);

		$companias=Fabrica::getAllFromDB("Compania",array(),"nombre ASC");	
		$selectCompania = '<option value=""></option>';
		foreach($companias as $compania){
			$selectCompania=$selectCompania.'\n<option value="'.$compania->getId().'" >'.$compania->getNombre().'</option>';
		}
		$this->addVar("companias",$selectCompania);

		$personas=Fabrica::getAllFromDB("Persona",array(),"nombres ASC");	
		$selectPersona = '<option value=""></option>';
		foreach($personas as $persona){
			$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" >'.$persona->getNombres().'</option>';
		}
		$this->addVar("vendedores",$selectPersona);

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
		
		/* Seleccinar el ramo */	
		if($_SESSION["busquedaPolizas"]["ramo"]){
			$this->addVar("ramo",$_SESSION["busquedaPolizas"]["ramo"]);
		}
		else{
			$this->addEmptyVar("ramo");
		}
		
		/* Seleccinar el compania */	
		if($_SESSION["busquedaPolizas"]["compania"]){
			$this->addVar("compania",$_SESSION["busquedaPolizas"]["compania"]);
		}
		else{
			$this->addEmptyVar("compania");
		}
		
		/* Seleccinar el vendedor */	
		if($_SESSION["busquedaPolizas"]["vendedor"]){
			$this->addVar("vendedor",$_SESSION["busquedaPolizas"]["vendedor"]);
		}
		else{
			$this->addEmptyVar("vendedor");
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