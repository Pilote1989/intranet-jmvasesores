<?php
class eliminaVehiculo extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$response = false;
		if($this->request->idVehiculo){
			$vehiculo = Fabrica::getFromDB("Vehiculo",$this->request->idVehiculo);
			$vehiculoEnPoliza = Fabrica::getAllFromDB("VehiculoEnPoliza",array("idVehiculo = '".$this->request->idVehiculo."'"));
			if(count($vehiculoEnPoliza)){
				//no se puede eliminar hay un vehiculo en poliza
				$response = false;
			}else{
				$vehiculo->deleteFromDB();
				$response = true;
			}
		}
		echo json_encode($response);
	}
}
?>