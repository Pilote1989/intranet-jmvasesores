<?php
class crear extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$anio = date("Y");
		$fc->import("lib.Marca");
		$fc->import("lib.Modelo");
		$selectAnios = '\n<option></option>';
		
		
		$marcas = Fabrica::getAllFromDB("Marca",array(),"marca ASC");	
		$selectMarcas = '\n<option></option>';
		if($this->request->idPoliza){
			if($this->request->idVehiculo){
				//editando vehiculo;
				$this->addVar("editar","Editar Vehiculo");
				$this->addVar("accion","Editar");	
				$this->addBlock("edito");
			}else{
				$this->addVar("editar","Agregar Nuevo Vehiculo");
				$this->addVar("accion","Crear");
				foreach($marcas as $marca){
					$selectMarcas .= '\n<option value="'.$marca->getId().'" >'.$marca->getMarca().'</option>';
				}		
				for($i = date("Y"); $i>1995; $i--){
					$selectAnios .= "\n<option value='" . $anio . "'>" . $anio . "</option>";
					$anio--;
				}
				$this->addEmptyVar("placa");
				$this->addEmptyVar("sumaAsegurada");
				$this->addEmptyVar("color");
				$this->addEmptyVar("motor");	
				$this->addEmptyVar("gps");
				$this->addEmptyVar("chasis");	
				$this->addEmptyVar("tipo");		
				$this->addEmptyVar("endosatario");	
				$this->addBlock("creo");		
			}
			$this->addVar("marcas",$selectMarcas);
			$this->addVar("anios",$selectAnios);
			$this->addEmptyVar("modelos");
			$this->addVar("idPoliza",$this->request->idPoliza);
			$this->addBlock("bloqueEditarVehiculo");
			$this->processTemplate("vehiculos/editarDatosBasicos.html");
		}else if($this->request->idVehiculo){
				//editando vehiculo;
				$vehiculo = Fabrica::getFromDB("Vehiculo",$this->request->idVehiculo);
				$this->addVar("editar","Editar Vehiculo");
				$this->addVar("accion","Editar");	
				$this->addBlock("edito");
				$this->addVar("placa", $vehiculo->getPlaca());
				$this->addVar("sumaAsegurada", $vehiculo->getSumaAsegurada());
				$this->addVar("color", $vehiculo->getColor());
				$this->addVar("motor", $vehiculo->getMotor());	
				$this->addVar("chasis", $vehiculo->getChasis());
				$this->addVar("endosatario", $vehiculo->getEndosatario());
				$this->addVar("tipo", $vehiculo->getTipo());
				$this->addVar("idMarca", $vehiculo->idMarca());

				if($vehiculo->getGps()=="1"){
					$this->addVar("gps", " checked");
				}else{
					$this->addEmptyVar("gps");
				}
				//echo $vehiculo->textoMarca();	
				foreach($marcas as $marca){
					$selectMarcas .= '\n<option value="'.$marca->getId().'" >'.$marca->getMarca().'</option>';
				}		
				$modelos = Fabrica::getAllFromDB("Modelo",array("idMarca = ". $vehiculo->idMarca()),"modelo ASC");
				foreach($modelos as $modelo){
					if($modelo->getId() != $vehiculo->getIdModelo()){
						$selectModelos .= '\n<option value="'.$modelo->getId().'" >'.$modelo->getModelo().'</option>';
					}else{
						$selectModelos .= '\n<option value="'.$modelo->getId().'" selected="selected">'.$modelo->getModelo().'</option>';
					}
				}		
				$this->addVar("modelos", $selectModelos);
				for($i = date("Y"); $i>1995; $i--){
					if($i != $vehiculo->getAnio()){
						$selectAnios .= "\n<option value='" . $anio . "'>" . $anio . "</option>";
					}else{
						$selectAnios .= "\n<option value='" . $anio . "' selected='selected'>" . $anio . "</option>";
					}
					$anio--;
				}
				$this->addVar("marcas",$selectMarcas);
				$this->addVar("anios",$selectAnios);
				$this->addVar("idPoliza", $vehiculo->getIdPoliza());
				$this->addVar("idVehiculo", $this->request->idVehiculo);
				$this->addBlock("bloqueIdVehiculo");
				$this->addBlock("bloqueEditarVehiculo");
				$this->processTemplate("vehiculos/editarDatosBasicos.html");
		}
	}
}
?>