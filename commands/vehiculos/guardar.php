<?php
class guardar extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$fc->import("lib.Vehiculo");
		$fc->import("lib.VehiculoEnPoliza");
		$usuario=$this->getUsuario();
		$response["respuesta"]="FAIL";
		//if($this->request->idPoliza){
			if($this->request->idVehiculo){
				$vehiculo = Fabrica::getFromDB("Vehiculo",$this->request->idVehiculo);
				$new=false;
			}else{
				$vehiculo = new Vehiculo();
				//nuevo vehiculo, lo tengo que asignar a la poliza
				$new=true;
			}
			$vehiculo->setPlaca($this->request->placa);
			$vehiculo->setSumaAsegurada($this->request->sumaAsegurada);	
			$vehiculo->setAnio($this->request->anio);	
			$vehiculo->setIdModelo($this->request->modelo);
			$vehiculo->setColor($this->request->color);
			$vehiculo->setMotor($this->request->motor);
			$vehiculo->setEndosatario($this->request->endosatario);
			$vehiculo->setChasis($this->request->chasis);
			$vehiculo->setTipo($this->request->tipo);
			$vehiculo->setIdPoliza($this->request->idPoliza);	
			if($this->request->gps){
				$vehiculo->setGps("1");
			}else{
				$vehiculo->setGps("0");
			}
			$vehiculo->storeIntoDB();			
			$dbLink=&FrontController::instance()->getLink();			
			$id=$dbLink->insert_id;
			if($new){
				$vehiculoEnPoliza = new VehiculoEnPoliza();
				$vehiculoEnPoliza->setIdPoliza($this->request->idPoliza);
				$vehiculoEnPoliza->setIdVehiculo($id);
				$vehiculoEnPoliza->storeIntoDB();	
			}
			$response["respuesta"]="SUCCESS";
		//}
		echo json_encode($response);
	}
}
?>