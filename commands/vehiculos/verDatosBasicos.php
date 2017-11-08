<?php
class verDatosBasicos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idVehiculo){
			$vehiculo = Fabrica::getFromDB("Vehiculo",$this->request->idVehiculo);
			$this->addVar("idVehiculo",$vehiculo->getId());
			$this->addVar("placa",$vehiculo->getPlaca());
			$this->addVar("tipo",$vehiculo->textoTipo());
			$this->addVar("marca",$vehiculo->textoMarca());
			$this->addVar("modelo",$vehiculo->textoModelo());
			$this->addVar("anio",$vehiculo->getAnio());
			$this->addVar("sumaAsegurada",number_format($vehiculo->getSumaAsegurada(),2));
			//$this->addVar("gps",($vehiculo->getGps() ? "Si" : "No"));
			if($vehiculo->getGps()){
				$this->addBlock("siGPS");
			}else{
				$this->addBlock("noGPS");
			}
			$this->addVar("motor",$vehiculo->getMotor());
			$this->addVar("chasis",$vehiculo->getChasis());
			$this->addVar("endosatario",$vehiculo->getEndosatario());
			$this->processTemplate("vehiculos/verDatosBasicos.html");
		}
	}
}
?>