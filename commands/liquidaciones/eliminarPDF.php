<?php
class eliminarPDF extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$response = false;
		if($this->request->idLiquidacion){
			$liquidacion = Fabrica::getFromDB("Liquidacion",$this->request->idLiquidacion);
			$liquidacion->setPdf("");
			$liquidacion->storeIntoDB();
			$response = true;
		}
		echo json_encode($response);
	}
}
?>