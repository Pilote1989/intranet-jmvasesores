<?php
class busqueda extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$nameSession = "busquedaLiquidaciones";
		if($_SESSION[$nameSession]["length"]){
			$this->addVar("length",$_SESSION[$nameSession]["length"]);
		}else{
			$this->addVar("length","10");
		}
		if($_SESSION[$nameSession]["start"]){
			$this->addVar("start",$_SESSION[$nameSession]["start"]);
		}else{
			$this->addVar("start","0");
		}
		if($_SESSION[$nameSession]["idCompania"]){
			$this->addVar("idCompania",$_SESSION[$nameSession]["idCompania"]);
		}else{
			$this->addEmptyVar("idCompania");
		}
		if($_SESSION[$nameSession]["factura"]){
			$this->addVar("factura",$_SESSION[$nameSession]["factura"]);
		}else{
			$this->addEmptyVar("factura");
		}		
		$companias=Fabrica::getAllFromDB("Compania",array(),"nombre ASC");	
		$selectCompania = '<option value=""></option>';
		foreach($companias as $compania){
			$selectCompania=$selectCompania.'\n<option value="'.$compania->getId().'" >'.$compania->getNombre().'</option>';
		}
		$this->addVar("companias",$selectCompania);		
		$this->addLayout("ace");
		$this->processTemplate("liquidaciones/busqueda.html");
	}
}
?>