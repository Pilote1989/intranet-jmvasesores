<?php
class busqueda extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$nameSession = "busquedaPolizas";
		if($_SESSION[$nameSession]["numeropoli"]){
			$this->addVar("numeropoli",$_SESSION[$nameSession]["numeropoli"]);
		}else{
			$this->addEmptyVar("numeropoli");
		}
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
		if($_SESSION[$nameSession]["nombre"]){
			$this->addVar("nombre",$_SESSION[$nameSession]["nombre"]);
		}else{
			$this->addEmptyVar("nombre");
		}
		if($_SESSION[$nameSession]["doc"]){
			$this->addVar("documento",$_SESSION[$nameSession]["doc"]);
		}else{
			$this->addEmptyVar("documento");
		}
		if($_SESSION[$nameSession]["idRamo"]){
			$this->addVar("idRamo",$_SESSION[$nameSession]["idRamo"]);
		}else{
			$this->addEmptyVar("idRamo");
		}
		if($_SESSION[$nameSession]["idCompania"]){
			$this->addVar("idCompania",$_SESSION[$nameSession]["idCompania"]);
		}else{
			$this->addEmptyVar("idCompania");
		}
		if($_SESSION[$nameSession]["idVendedor"]){
			$this->addVar("idVendedor",$_SESSION[$nameSession]["idVendedor"]);
		}else{
			$this->addEmptyVar("idVendedor");
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

		$personas=Fabrica::getAllFromDB("Persona",array("vendedor = '1'"),"nombres ASC");	
		$selectPersona = '<option value=""></option>';
		foreach($personas as $persona){
			$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" >'.$persona->getNombres(). ' ' . $persona->getApellidoPaterno() . ' ' . $persona->getApellidoMaterno() . '</option>';
		}
		$this->addVar("vendedores",$selectPersona);		
		
		
		$this->addLayout("ace");
		$this->processTemplate("polizas/busqueda.html");
	}}
?>