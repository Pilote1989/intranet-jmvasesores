<?php
class procesarVariosCupones extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cupon");
		$fc->import("lib.Poliza");		
		//print_r($this->request);
		//echo count($this->request->numeroCupon);
		$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);
		for($i=0 ; $i<count($this->request->numeroCupon) ; $i++){
			//echo $this->request->numeroCupon[$i] . " " . $this->request->monto[$i] .  " " . $this->request->vencimiento[$i] .  "<br/>";
			$cupon=new Cupon();
			$cupon->setFechaVencimiento($this->request->vencimiento[$i],"DATE");
			$cupon->setMonto($this->request->monto[$i]);
			$cupon->setNumeroCupon($this->request->numeroCupon[$i]);
			$cupon->setIdPoliza($this->request->idPoliza);		
			$cupon->storeIntoDB();
			//echo $i;
		}
		//$fc->redirect("?do=polizas.ver&idPoliza=" . $this->request->idPoliza . "&t=c");
		//echo $poliza->matriz();
		$fc->redirect("?do=polizas.ver&idPoliza=" . $poliza->matriz() . "&vig=" . $this->request->idPoliza . "&t=c");		
		/*		
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
		$fc->redirect("?do=polizas.verCupones&idPoliza=" . $this->request->idPoliza);   */
	}
}
?>