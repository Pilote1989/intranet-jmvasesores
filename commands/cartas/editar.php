<?php
class editar extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->idCarta){
			//editando carta			
			$carta = Fabrica::getFromDB("Carta",$this->request->idCarta);
			$pieces = explode("-", $carta->getFecha());	
			$this->addVar("idCarta", $this->request->idCarta);
			$this->addVar("anio", $pieces[0]);
			$this->addVar("numero", sprintf('%05d', $this->request->idCarta));
			$this->addVar("carta", $carta->getCarta());
			$this->addVar("detalle", $carta->getDetalle());
			$this->addVar("idPoliza", $carta->getIdPoliza());
			$this->processTemplate("cartas/editar.html");
		}
	}
}
?>