<?php
class eliminarPDF extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$response = 0;
		if($this->request->idPoliza){
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$poliza->setPdf("");
			$poliza->storeIntoDB();
			$response = 1;
		}
		return json_decode($response);
	}
}
?>