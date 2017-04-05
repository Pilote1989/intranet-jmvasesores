<?php
class eliminarPoliza extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Poliza");
		if($this->request->idPoliza){
			$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$poliza->setEstado('0');
			$poliza->storeIntoDB();
			$fc->redirect("?do=polizas.verPolizas");
		}else{
			$fc->redirect("?do=polizas.verPolizas");
		}				
	}
}
?>