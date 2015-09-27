<?php
class verLiquidaciones extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->limpiar){
			$_SESSION["busquedaLiquidaciones"]["compania"]="";
			$_SESSION["busquedaLiquidaciones"]["factura"]="";
			$_SESSION["busquedaLiquidaciones"]["limite"]="";
		}
	
		$companias = Fabrica::getAllFromDB("Compania");	
		$selectCompanias = '<option value=""></option>';
		foreach($companias as $compania){
			$selectCompanias = $selectCompanias.'\n<option value="'.$compania->getId().'" >'.$compania->getNombre().'</option>';
		}
		$this->addVar("companias",$selectCompanias);

		
		/* Seleccinar el nombre */	
		if($_SESSION["busquedaLiquidaciones"]["factura"]){
			$this->addVar("contratante",$_SESSION["busquedaLiquidaciones"]["factura"]);
		}
		else{
			$this->addEmptyVar("factura");
		}
		
		/* Limite por pagina */	
		if($_SESSION["busquedaLiquidaciones"]["limite"]){
			$this->addVar("limite",$_SESSION["busquedaLiquidaciones"]["limite"]);
		}
		else{
			$this->addEmptyVar("limite");
		}
		//print_r($_SESSION);
		$this->addLayout("admin");
		$this->processTemplate("liquidaciones/verLiquidaciones.html");
	}
}
?>