<?php
class busqueda extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$nameSession = "busquedaCompanias";
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
		$this->addLayout("ace");
		$this->processTemplate("companias/busqueda.html");
	}}
?>