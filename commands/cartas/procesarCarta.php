<?php
class procesarCarta extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		//if($this->request->idPoliza){if($this->request->idCarta){
			if($this->request->idCarta){
				$carta = Fabrica::getFromDB("Carta", $this->request->idCarta);
				$this->addVar("carta", $carta->getCarta());
				$pieces = explode("-", $carta->getFecha());	
				$this->addVar("numero", "JMV - " . sprintf('%05d', $this->request->idCarta) . " - " . $pieces[0]);
				$this->processTemplate("cartas/procesarCarta.html");
			}
			//var_dump($_REQUEST);
		//}
	}
}
?>