<?php
class eliminarCupon extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cupon");
		$fc->import("lib.Poliza");					
		if($this->request->idCupon){
			$cupon = Fabrica::getFromDB("Cupon",$this->request->idCupon);
			$poliza = $cupon->getIdPoliza();
			$poliza=Fabrica::getFromDB("Poliza",$poliza);
			$cupon->deleteFromDB();
			//$fc->redirect("?do=polizas.ver&idPoliza=" . $poliza . "&t=c");
			$fc->redirect("?do=polizas.ver&idPoliza=" . $poliza->matriz() . "&vig=" . $poliza->getId() . "&t=c");		
		
		}
	}
}
?>