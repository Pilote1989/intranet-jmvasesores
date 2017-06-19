<?php
class rehabilitaRenovacion extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Poliza");
		if($this->request->idPoliza){
			$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$poliza->setAnulada('0');
			$poliza->storeIntoDB();
		}				
	}
}
?>