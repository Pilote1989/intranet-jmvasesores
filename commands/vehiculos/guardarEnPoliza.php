<?php
class guardarEnPoliza extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$fc->import("lib.VehiculoEnPoliza");
		$usuario=$this->getUsuario();
		$response["respuesta"]="FAIL";
		if($this->request->idPoliza){
			if($this->request->idVehiculo){
				$vehiculoEnPoliza = new VehiculoEnPoliza();
				$vehiculoEnPoliza->setIdPoliza($this->request->idPoliza);
				$vehiculoEnPoliza->setIdVehiculo($this->request->idVehiculo);
				$vehiculoEnPoliza->storeIntoDB();
				$response["respuesta"]="SUCCESS";
			}
		}
		echo json_encode($response);
	}
}
?>