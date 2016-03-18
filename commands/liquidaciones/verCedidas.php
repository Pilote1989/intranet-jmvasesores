<?php
class verCedidas extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->limpiar){
			$_SESSION["busquedaLiquidacionesC"]["vendedor"]="";
			$_SESSION["busquedaLiquidacionesC"]["factura"]="";
			$_SESSION["busquedaLiquidacionesC"]["limite"]="";
		}
	
		$personas=Fabrica::getAllFromDB("Persona",array(),"nombres ASC");	
		$selectPersona = '<option value=""></option>';
		foreach($personas as $persona){
			if($persona->getId()!="1"){
			$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" >'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
			}
		}
		$this->addVar("vendedores",$selectPersona);

		
		/* Seleccinar el nombre */	
		if($_SESSION["busquedaLiquidacionesC"]["factura"]){
			$this->addVar("factura",$_SESSION["busquedaLiquidacionesC"]["factura"]);
		}
		else{
			$this->addEmptyVar("factura");
		}
		
		/* Limite por pagina */	
		if($_SESSION["busquedaLiquidacionesC"]["limite"]){
			$this->addVar("limite",$_SESSION["busquedaLiquidacionesC"]["limite"]);
		}
		else{
			$this->addEmptyVar("limite");
		}
		//print_r($_SESSION);
		$this->addLayout("ace");
		$this->processTemplate("liquidaciones/verCedidas.html");
	}
}
?>