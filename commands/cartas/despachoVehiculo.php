<?php
class despachoVehiculo extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->idPoliza){
			//nueva carta
			setlocale(LC_ALL,"es_ES");
			$this->addVar("idPoliza", $this->request->idPoliza);
			$this->addBlock("poliza");
			$this->addVar("fecha", strftime("%A %d de %B del %Y"));
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$cobro = Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
			//print_r($cobro);
			$simbolo="US$ ";
			if($cobro->getMoneda()=="Dolares"){
				$simbolo="US$ ";
			}elseif($cobro->getMoneda()=="Soles"){
				$simbolo="S/. ";
			}elseif($poliza->getMoneda()=="Euros"){
				$simbolo="&euro; ";
			}
			$distritos = array(
				"99" => "Sin Definir",
				"98" => "Provincia",
				"1" => "Lima",
				"2" => "Ancón",
				"3" => "Ate",
				"4" => "Barranco",
				"5" => "Breña",
				"6" => "Carabayllo",
				"7" => "Chaclacayo",
				"8" => "Chorrillos",
				"40" => "Cieneguilla",
				"7" => "Comas",
				"10" => "El Agustino",
				"28" => "Independencia",
				"11" => "Jesús María",
				"12" => "La Molina",
				"13" => "La Victoria",
				"14" => "Lince",
				"39" => "Los Olivos",
				"15" => "Lurigancho-Chosica",
				"16" => "Lurin",
				"17" => "Magdalena del Mar",
				"21" => "Pueblo Libre",
				"18" => "Miraflores",
				"19" => "Pachacámac",
				"20" => "Pucusana",
				"22" => "Puente Piedra",
				"24" => "Punta Hermosa",
				"23" => "Punta Negra",
				"25" => "Rímac",
				"26" => "San Bartolo",
				"41" => "San Borja",
				"27" => "San Isidro",
				"36" => "San Juan de Lurigancho",
				"29" => "San Juan de Miraflores",
				"30" => "San Luis",
				"31" => "San Martín de Porres",
				"32" => "San Miguel",
				"43" => "Santa Anita",
				"37" => "Santa María del Mar",
				"38" => "Santa Rosa",
				"33" => "Santiago de Surco",
				"34" => "Surquillo",
				"42" => "Villa El Salvador",
				"35" => "Villa María del Triunfo"
			);
			$this->addVar("inicio", $poliza->getInicioVigencia("DATE"));
			$this->addVar("pol", $poliza->getNumeroPoliza());
			$this->addVar("fin	", $poliza->getFinVigencia("DATE"));
			$compania = Fabrica::getFromDB("Compania", $poliza->getIdCompania());
			$this->addVar("cia", $compania->getSigla());
			$cliente = Fabrica::getFromDB("Cliente", $poliza->getIdCliente());
			$this->addVar("encargadoSeguros", $cliente->getEncargado());
			$this->addVar("nombre", $cliente->getNombre());
			if($cliente->getDireccion() == ""){
				$this->addVar("direccion", "[direccion]");
			}else{
				$this->addVar("direccion", $cliente->getDireccion());
				
			}
			//$this->addVar("distrito", $distritos[$cliente->getDistrito()]);
			if($cliente->getIdUbigeo()!="0"){
				$this->addVar("distrito", $cliente->obtenerUbigeo());
			}else{
				$this->addVar("distrito", "");	
			}
			$ramo = Fabrica::getFromDB("Ramo", $poliza->getIdRamo());
			$this->addVar("ramo", $ramo->getNombre());
			$this->addVar("idPoliza",$this->request->idPoliza);
			$this->addVar("aviso", $cobro->getAvisoDeCobranza());
			$this->addVar("convenio", $cobro->getAvisoDeCobranza());
			$this->addVar("documento", $cobro->getDocumento());
			$this->addVar("moneda", $simbolo);
			$this->addVar("monto", number_format($cobro->getTotalFactura(),2));
			$VehiculoEnPoliza = Fabrica::getAllFromDB("VehiculoEnPoliza", array("idPoliza = '" .$this->request->idPoliza . "'"));
			if(count($VehiculoEnPoliza)=="1"){
				$this->addBlock("unVehiculo");
				$vehiculos = Fabrica::getFromDB("Vehiculo", $VehiculoEnPoliza[0]->getIdVehiculo());
				$modelo = Fabrica::getFromDB("Modelo", $vehiculos->getIdModelo());
				if($vehiculos->getIdModelo()!=""){
					$this->addVar("modelo", $modelo->getModelo());
					$marca = Fabrica::getFromDB("Marca", $modelo->getIdMarca());
					$this->addVar("marca", $marca->getMarca());
				}else{
					$this->addVar("modelo", "Sin Definir");
					$this->addVar("marca", "Sin Definir");
				}
				if($vehiculos->getGps()=="1"){
					$this->addBlock("GPS");
				}
				$this->addVar("placa", $vehiculos->getPlaca());	
				$this->addVar("endosatario", $vehiculos->getEndosatario());
			}elseif(count($VehiculoEnPoliza)>"1"){
				$this->addBlock("variosVehiculos");
				$listaVehiculos = array();
				$i=0;
				foreach($VehiculoEnPoliza as $temp){
					$vehiculo = Fabrica::getFromDB("Vehiculo", $temp->getIdVehiculo());
					if($vehiculo->getIdModelo()!=""){
						$modelo = Fabrica::getFromDB("Modelo", $vehiculo->getIdModelo());
						$listaVehiculos[$i]["modelo"] = $modelo->getModelo();	
						$marca = Fabrica::getFromDB("Marca", $modelo->getIdMarca());
						$listaVehiculos[$i]["marca"] = $marca->getMarca();
					}else{
						$listaVehiculos[$i]["modelo"] = "Sin Definir";
						$listaVehiculos[$i]["marca"] = "Sin Definir";
					}
					if($vehiculo->getGps()=="1"){
						$listaVehiculos[$i]["gps"] = "Si";
					}else{
						$listaVehiculos[$i]["gps"] = "No";
					}
					$listaVehiculos[$i]["placa"] = $vehiculo->getPlaca();
					$listaVehiculos[$i]["endosatario"] = $vehiculo->getEndosatario();
					$i++;
				}
				$this->addLoop("vehiculos",$listaVehiculos);
			}
			$cupones = Fabrica::getAllFromDB("Cupon", array("idPoliza = " . $poliza->getId()), "fechaVencimiento ASC");
			$this->addVar("cuotas", count($cupones));
			if(count($cupones)>0){
				$pieces = explode("-", $cupones[0]->getFechaVencimiento());
				$this->addVar("ultimo", $pieces[2] . "/" . $pieces[1] . "/" . $pieces[0]);
				$this->addVar("primero", $simbolo . " " . number_format($cupones[0]->getMonto(),2));
				
			}
			$this->addLayout("carta");
			$this->processTemplate("cartas/despachoVehiculo.html");
		}
	}
}
?>