<?php
class procesarAnulacion extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Poliza");
		$response["respuesta"]="FAIL";
		if($this->request->idPoliza){
			$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$poliza->setAnulada('1');
			$poliza->setMotivoAnulacion($this->request->motivo);
			$poliza->storeIntoDB();
			$response["respuesta"]="SUCCESS";
		}
		echo json_encode($response);			
	}
}
?>