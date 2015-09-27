<?php
class procesarCupones extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cupon");
		$fc->import("lib.Poliza");
		$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);				
		if($this->request->idCupon){
			$cupon=Fabrica::getFromDB("Cupon",$this->request->idCupon);
			$varVencimiento = "vencimiento_" . $this->request->idCupon;
			$varMonto = "monto_" . $this->request->idCupon;
			$varNumeroCupon = "numeroCupon_" . $this->request->idCupon;
		}else{
			$cupon=new Cupon();
			$varVencimiento = "vencimiento";
			$varMonto = "monto";
			$varNumeroCupon = "numeroCupon";
		}
		$cupon->setFechaVencimiento($this->request->$varVencimiento,"DATE");
		$cupon->setMonto($this->request->$varMonto);
		$cupon->setNumeroCupon($this->request->$varNumeroCupon);
		$cupon->setIdPoliza($this->request->idPoliza);		
		$cupon->storeIntoDB();
		$fc->redirect("?do=polizas.ver&idPoliza=" . $poliza->matriz() . "&vig=" . $this->request->idPoliza . "&t=c");
	}
}
?>