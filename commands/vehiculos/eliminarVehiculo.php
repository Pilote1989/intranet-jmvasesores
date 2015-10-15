<?php
class eliminarVehiculo extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Vehiculo");				
		if($this->request->idVehiculo){			
			if($this->request->idPol){			
				$vehiculoEnPoliza = Fabrica::getAllFromDB("VehiculoEnPoliza",array("idPoliza='".$this->request->idPol."'","idVehiculo='".$this->request->idVehiculo."'"));
				$vehiculoEnPoliza[0]->deleteFromDB();
				//$fc->redirect("?do=polizas.ver&idPoliza=" . $poliza . "&t=c");
				echo json_encode(true);
			}
		}
	}
}
?>