<?php
class eliminarVehiculo extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Vehiculo");				
		if($this->request->idVehiculo){
			$vehiculo = Fabrica::getFromDB("Vehiculo",$this->request->idVehiculo);
			$vehiculo->deleteFromDB();
			//$fc->redirect("?do=polizas.ver&idPoliza=" . $poliza . "&t=c");
			echo json_encode(true);
		}
	}
}
?>