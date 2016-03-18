<?php
class generar extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		
		
		$ramos=Fabrica::getAllFromDB("Ramo");	
		$selectRamo = '<option value=""></option>';
		foreach($ramos as $ramo){
			$selectRamo=$selectRamo.'\n<option value="'.$ramo->getId().'" >'.$ramo->getNombre().'</option>';
		}
		$this->addVar("ramos",$selectRamo);
		
		
		$companias=Fabrica::getAllFromDB("Compania");	
		$selectCompania = '<option value=""></option>';
		foreach($companias as $compania){
			$selectCompania=$selectCompania.'\n<option value="'.$compania->getId().'" >'.$compania->getNombre().'</option>';
		}
		$this->addVar("companias",$selectCompania);
		
		$this->addLayout("ace");
		
		$this->processTemplate("reportes/generar.html");
	}
}
?>