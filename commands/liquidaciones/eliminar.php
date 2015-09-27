<?php
class eliminar extends SessionCommand{
	function execute(){
		$fc=FrontController::instance();
		if($this->request->idCobro){
			$cobro = Fabrica::getFromDB("Cobro",$this->request->idCobro);
			$idLiquidacion = $cobro->getIdLiquidacion();
			$cobro->setIdLiquidacion("");
			$cobro->storeIntoDB();
			$fc->redirect("?do=liquidaciones.ver&idLiquidacion=" . $idLiquidacion);
		}
	}
}
?>
