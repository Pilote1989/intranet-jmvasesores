<?php
class eliminarCuponAjax extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cupon");
		$fc->import("lib.Poliza");					
		if($this->request->idCupon){
			$cupon = Fabrica::getFromDB("Cupon",$this->request->idCupon);
			$cupon->deleteFromDB();
			echo json_encode(true);
		}else{
			echo json_encode(false);	
		}
	}
}
?>