<?php
class ver extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$liquidacion = Fabrica::getFromDB("Liquidacion",$this->request->idLiquidacion);
		$this->addVar("idLiquidacion", $this->request->idLiquidacion);
		$this->addVar("numeroFactura", $liquidacion->getFactura());
		$this->addVar("fechaFactura", $liquidacion->getFechaFactura("DATE"));
		if($liquidacion->getObservaciones()==""){
			$this->addVar("observaciones", "-");
		}else{
			$this->addVar("observaciones", $liquidacion->getObservaciones());
		}
		$this->addVar("cia",Fabrica::getFromDB("Compania", $liquidacion->getIdCompania())->getNombre());
		$cobros = Fabrica::getAllFromDB("Cobro",array("idLiquidacion = '" . $this->request->idLiquidacion . "'"));
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
			$fac += $cobro->getComision();
			$i++;
		}
		$this->addVar("subtotal", number_format($fac,2));
		$this->addVar("igv", number_format(($fac*0.18),2));
		$this->addVar("totalFactura", number_format(($fac*1.18),2));
		$this->addLoop("cobros",$listaCobros);
		$this->addLayout("admin");
		if($liquidacion->getBono()=="1"){
			$this->addVar("subtotal", number_format($liquidacion->getSubTotal(),2));
			$this->addVar("moneda", $liquidacion->getMoneda());
			$this->addVar("igv", number_format($liquidacion->getIgv(),2));
			$this->addVar("totalFactura", number_format($liquidacion->getTotalFactura(),2));
			$this->processTemplate("liquidaciones/verBono.html");	
		}else{
			$this->processTemplate("liquidaciones/verLiquidacion.html");	
		}
	}
}
?>