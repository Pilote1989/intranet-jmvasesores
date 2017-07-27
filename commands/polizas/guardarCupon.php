<?php
class guardarCupon extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cupon");
		$fc->import("lib.Poliza");
		$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);				
		if($this->request->idCupon){
			$cupon=Fabrica::getFromDB("Cupon",$this->request->idCupon);
		}else{
			$cupon=new Cupon();
		}
		$cupon->setFechaVencimiento($this->request->vencimiento,"DATE");
		$cupon->setMonto($this->request->monto);
		$cupon->setNumeroCupon($this->request->numeroCupon);
		$cupon->setIdPoliza($this->request->idPoliza);		
		$cupon->storeIntoDB();
		$response["respuesta"]="SUCCESS";
		echo json_encode($response);
	}
}
?>