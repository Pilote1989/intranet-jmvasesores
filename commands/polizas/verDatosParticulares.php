<?php
class verDatosParticulares extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->idPoliza){
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			if($poliza->getIdRamo()=="2"){
				$vehiculos = Fabrica::getAllFromDB("VehiculoEnPoliza", array("idPoliza = '" .$this->request->idPoliza . "'"));	
				//$vehiculos = Fabrica::getAllFromDB("Vehiculo", array("idPoliza = '" .$this->request->idPoliza . "'"));	
				$listaVehiculos = array();
				$i = 0;
				if(count($vehiculos)){
					$this->addBlock("bloqueResultados");	
				}else{
					$this->addBlock("bloqueNoResultados");				
				}
				foreach($vehiculos as $vehiculoTemp){
					$vehiculo = Fabrica::getFromDB("Vehiculo", $vehiculoTemp->getIdVehiculo());
					$listaVehiculos[$i]["idVehiculo"] = $vehiculo->getId();
					$listaVehiculos[$i]["placa"] = $vehiculo->getPlaca();	
					$listaVehiculos[$i]["sumaAsegurada"] = $vehiculo->getSumaAsegurada();	
					$listaVehiculos[$i]["chasis"] = $vehiculo->getChasis();	
					$listaVehiculos[$i]["motor"] = $vehiculo->getMotor();	
					$listaVehiculos[$i]["anio"] = $vehiculo->getAnio();	
					$listaVehiculos[$i]["color"] = $vehiculo->getColor();
					switch($vehiculo->getTipo()){
						case 1: 
							$listaVehiculos[$i]["tipo"] = "Auto";
							break;	
						case 2: 
							$listaVehiculos[$i]["tipo"] = "Camioneta Pick Up";
							break;	
						case 3: 
							$listaVehiculos[$i]["tipo"] = "Camioneta Panel";
							break;	
						case 4: 
							$listaVehiculos[$i]["tipo"] = "Camioneta Station Wagon";
							break;	
						case 5: 
							$listaVehiculos[$i]["tipo"] = "Camioneta Rural 4x4";
							break;	
						case 6: 
							$listaVehiculos[$i]["tipo"] = "Camioneta Rural 4x2";
							break;	
						case 7: 
							$listaVehiculos[$i]["tipo"] = "Carreta";
							break;	
						case 8: 
							$listaVehiculos[$i]["tipo"] = "Tracto";
							break;	
						case 9: 
							$listaVehiculos[$i]["tipo"] = "Camion";
							break;	
						case 10: 
							$listaVehiculos[$i]["tipo"] = "Moto";
							break;	
					}					
					$listaVehiculos[$i]["modelo"] = $vehiculo->textoModelo();
					$listaVehiculos[$i]["marca"] = $vehiculo->textoMarca();
					$listaVehiculos[$i]["color"] = $vehiculo->getColor();
					$i++;
				}
				$this->addVar("idPoliza",$this->request->idPoliza);
				$this->addLoop("vehiculos", $listaVehiculos);
				if($this->checkAccess("crearUsuario", true)){
					$this->addBlock("admin");
				}					
					$this->addBlock('vehiculos');
				if($poliza->getAnulada()=='1'){
					$this->addBlock('Anulada');
				}else{
					$this->addBlock('NoAnulada');
				}
				$this->processTemplate("polizas/verVehiculos.html");	
			}else if($poliza->getIdRamo()=="4"){
				//ver personas de asistencia medica	
				$asegurados = Fabrica::getAllFromDB("ClienteEnPoliza", array("idPoliza = '" .$this->request->idPoliza . "'"));	
				if(count($asegurados)){
					$this->addBlock("bloqueResultados");	
				}else{
					$this->addBlock("bloqueNoResultados");				
				}
				$listaAsegurados = array();
				foreach($asegurados as $asegurado){
					$aseguradoTemp = Fabrica::getFromDB("Cliente", $asegurado->getIdCliente());
					$listaAsegurados[$i]["nombre"] = $aseguradoTemp->getNombre();
					$listaAsegurados[$i]["tipoDoc"] = $aseguradoTemp->getTipoDoc();	
					$listaAsegurados[$i]["documento"] = $aseguradoTemp->getDoc();	
					$listaAsegurados[$i]["nacimiento"] = "-";
					$listaAsegurados[$i]["edad"] = "-";
					$listaAsegurados[$i]["id"] = $asegurado->getId();
					$i++;
				}			
				$this->addLoop("asegurados", $listaAsegurados);
				
				$this->addVar("idPoliza",$this->request->idPoliza);								
				if($poliza->getAnulada()=='1'){
					$this->addBlock('Anulada');
				}else{
					$this->addBlock('NoAnulada');
				}
				$this->processTemplate("polizas/verAsegurados.html");
			}else{
				$this->processTemplate("polizas/enConstruccion.html");
			}
		}
	}
}
?>