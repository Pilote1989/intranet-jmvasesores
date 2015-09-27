<?php
class verLiquidacion extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$liquidacion = Fabrica::getFromDB("Liquidacion",$this->request->idLiquidacion);
		$cobros = Fabrica::getAllFromDB("Cobro",array("idLiquidacion = '" . $this->request->idLiquidacion . "'"));
		foreach($cobros as $cobro){
			$seleccionados[] = '"'.$cobro->getId().'"';
			$seleccionados_tipo[] = '"'.$cobro->tipo().'"';
			//echo $cobro->tipo() . " - " . $cobro->getId(). "<br />";
		}
		$this->addLayout("admin");
		$this->processTemplate("liquidaciones/verLiquidacion.html");
	}
}
?>