<?php
class eliminarCedida extends SessionCommand{
	function execute(){
		$fc=FrontController::instance();
		if($this->request->idCobro){
			$cobro = Fabrica::getFromDB("Cobro",$this->request->idCobro);
			$idLiquidacion = $cobro->getIdCedida();
			$cobro->setIdCedida("");
			$cobro->storeIntoDB();
			$fc->redirect("?do=liquidaciones.verCedida&idCedida=" . $idLiquidacion);
		}
	}
}
?>
