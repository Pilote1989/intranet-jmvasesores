<?php
class busqueda extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$nameSession = "busquedaVehiculos";
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
		if($_SESSION[$nameSession]["placa"]){
			$this->addVar("placa",$_SESSION[$nameSession]["placa"]);
		}else{
			$this->addEmptyVar("placa");
		}
		$this->addLayout("ace");
		$this->processTemplate("vehiculos/busqueda.html");
	}}
?>