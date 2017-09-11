<?php
class busqueda extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$nameSession = "busquedaClientes";
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
		$this->addLayout("ace");
		$this->processTemplate("clientes/busqueda.html");
	}}
?>