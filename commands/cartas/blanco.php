<?php
class blanco extends sessionCommand{
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
			$this->addVar("moneda", $simbolo);
			$this->addVar("monto", number_format($cobro->getTotalFactura(),2));
			$this->addVar("aviso", $cobro->getAvisoDeCobranza());
			$compania = Fabrica::getFromDB("Compania", $poliza->getIdCompania());
			$this->addVar("numPoliza", $poliza->getNumeroPoliza());
			$this->addVar("siglas", $compania->getSigla());
			$cliente = Fabrica::getFromDB("Cliente", $poliza->getIdCliente());
			$this->addVar("encargadoSeguros", $cliente->getEncargado());
			$this->addVar("convenio", $cobro->getAvisoDeCobranza());
			$this->addVar("nombre", $cliente->getNombre());
			if($cliente->getDireccion() == ""){
				$this->addVar("direccion", "[direccion]");
			}else{
				$this->addVar("direccion", $cliente->getDireccion());
			}
			$cupones = Fabrica::getAllFromDB("Cupon", array("idPoliza = " . $poliza->getId()), "fechaVencimiento ASC");
			$this->addVar("cuotas", count($cupones));
			if(count($cupones)>0){
				$pieces = explode("-", $cupones[0]->getFechaVencimiento());
				$this->addVar("ultimo", $pieces[2] . "/" . $pieces[1] . "/" . $pieces[0]);
				$this->addVar("primero", $simbolo . " " . number_format($cupones[0]->getMonto(),2));
				
			}
			if($cliente->getIdUbigeo()!="0"){
				$this->addVar("distrito", $cliente->obtenerUbigeo());
			}else{
				$this->addVar("distrito", "");	
			}
			//$this->addVar("distrito", $distritos[$cliente->getDistrito()]);
			$ramo = Fabrica::getFromDB("Ramo", $poliza->getIdRamo());
			$this->addVar("ramo", $ramo->getNombre());
			$this->addVar("idPoliza",$this->request->idPoliza);
			$this->addLayout("carta");
			$this->processTemplate("cartas/blanco.html");
		}
	}
}
?>