<?php
class ver extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idCliente){
			if($this->request->m){
				$this->addBlock("mensaje");	
				$this->addVar("m", "No se puede eliminar a un cliente que tiene polizas");	
			}
			$cliente = Fabrica::getFromDB("Cliente",$this->request->idCliente);
			$this->addVar("idCliente", $this->request->idCliente);
			$this->addVar("nombre", $cliente->getNombre());
			$polizas = Fabrica::getAllFromDB("Poliza",array("idCliente = '" . $this->request->idCliente . "'", "estado = '1'"));
			$this->addVar("polizas", count($polizas));
			if(count($polizas)=="0"){
				$this->addBlock("elimina");
			}
			$this->addLayout("ace");
			$this->addBlock("admin");
			$this->processTemplate("clientes/ver.html");
		}
	}
}
?>