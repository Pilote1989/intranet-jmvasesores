<?php
class verCedida extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$cedida = Fabrica::getFromDB("Cedida",$this->request->idCedida);
		$this->addVar("idCedida", $this->request->idCedida);
		$this->addVar("numeroFactura", $cedida->getFactura());
		$this->addVar("fechaFactura", $cedida->getFechaFactura("DATE"));
		if($cedida->getObservaciones()==""){
			$this->addVar("observaciones", "-");
		}else{
			$this->addVar("observaciones", $cedida->getObservaciones());
		}
		$persona = Fabrica::getFromDB("Persona", $cedida->getIdPersona());
		$this->addVar("per", $persona->getNombres() . " " . $persona->getApellidoPaterno() . " " . $persona->getApellidoMaterno());
		$cobros = Fabrica::getAllFromDB("Cobro",array("idCedida = '" . $this->request->idCedida . "'"));
		$fac = 0;
		$listaCobros = array();
		foreach($cobros as $cobro){
			$listaCobros[$i]["id"] = $cobro->getId();
			$listaCobros[$i]["tipo"] = $cobro->tipo();	
			$listaCobros[$i]["avisoDeCobranza"] = $cobro->getAvisoDeCobranza();
			if($cobro->tipo()=="POL"){
				$poliza = Fabrica::getAllFromDB("Poliza",array("idCobro = '" . $cobro->getId() . "'"));	
				$listaCobros[$i]["poliza"] = $poliza[0]->getId();
				$listaCobros[$i]["numPoliza"] = $poliza[0]->getNumeroPoliza();		
				$listaCobros[$i]["cliente"] = Fabrica::getFromDB("Cliente", $poliza[0]->getIdCliente())->getNombre();			
			}elseif($cobro->tipo()=="END"){
				$endoso = Fabrica::getAllFromDB("Endoso",array("idCobro = '" . $cobro->getId() . "'"));
				$poliza = Fabrica::getFromDB("Poliza", $endoso[0]->getIdPoliza());
				$listaCobros[$i]["cliente"] = Fabrica::getFromDB("Cliente", $poliza->getIdCliente())->getNombre();			
				$listaCobros[$i]["poliza"] = $poliza->getId();	
				$listaCobros[$i]["numPoliza"] = $poliza->getNumeroPoliza();		
			}
			$listaCobros[$i]["primaNeta"] = number_format($cobro->getPrimaNeta(),2);	
			$listaCobros[$i]["comisionP"] = number_format($cobro->getComisionP(),2);
			$listaCobros[$i]["comision"] = number_format($cobro->getComision(),2);
			$listaCobros[$i]["comisionCP"] = number_format($cobro->getComisionCedidaP(),2);
			$listaCobros[$i]["comisionC"] = number_format($cobro->getComisionCedida(),2);
			//$fac += $cobro->getComision();
			$i++;
		}
		$this->addVar("subtotal", number_format($cedida->getSubTotal(),2));
		$this->addVar("igv", number_format($cedida->getIgv(),2));
		$this->addVar("totalFactura", number_format($cedida->getTotalFactura(),2));
		$this->addLoop("cobros",$listaCobros);
		$this->addLayout("admin");
		$this->processTemplate("liquidaciones/verCedida.html");
	}
}
?>