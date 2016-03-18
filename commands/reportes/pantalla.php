<?php
class pantalla extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		
		
		$ramos=Fabrica::getAllFromDB("Ramo", array(), "nombre");	
		$selectRamo = '<option value=""></option>';
		foreach($ramos as $ramo){
			$selectRamo=$selectRamo.'\n<option value="'.$ramo->getId().'" >'.$ramo->getNombre().'</option>';
		}
		$this->addVar("ramos",$selectRamo);
		
		
		$companias=Fabrica::getAllFromDB("Compania", array(), "nombre");	
		$selectCompania = '<option value=""></option>';
		foreach($companias as $compania){
			$selectCompania=$selectCompania.'\n<option value="'.$compania->getId().'" >'.$compania->getNombre().'</option>';
		}
		$this->addVar("companias",$selectCompania);
		
		$clientes=Fabrica::getAllFromDB("Cliente", array(), "nombre");	
		$selectCliente = '<option value=""></option>';
		foreach($clientes as $cliente){
			$selectCliente=$selectCliente.'\n<option value="'.$cliente->getId().'" >'.$cliente->getNombre().'</option>';
		}
		$this->addVar("clientes",$selectCliente);		
		
		
		$this->addLayout("ace");
		
		$this->processTemplate("reportes/pantalla.html");
	}
}
?>