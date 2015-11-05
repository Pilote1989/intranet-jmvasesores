<?php
class buscarMes extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->mes && $this->request->anio){
			$meses = array(
					1 => 'Enero',
					2 => 'Febrero',
					3 => 'Marzo',
					4 => 'Abril',
					5 => 'Mayo',
					6 => 'Junio',
					7 => 'Julio',
					8 => 'Agosto',
					9 => 'Setiembre',
					10 => 'Octubre',
					11 => 'Noviembre',
					12 => 'Diciembre'
			);
			if( ! ( is_numeric($this->request->anio) && is_numeric($this->request->mes) && $this->request->mes > 0 && $this->request->mes < 13 ) ){
				return;
			}
			if($this->request->reporte=="1"){
				echo "Reporte en desarrollo.";
				exit;
			
			
			}else if($this->request->reporte=="2"){
				//comisiones recibidas
				$liquidaciones = Fabrica::getAllFromDB("Liquidacion",array("MONTH(fechaFactura) = '" . $this->request->mes . "'", "YEAR(fechaFactura) = '" . $this->request->anio . "'"));
				$i=0;
				$lista = array();
				$comisionSoles = 0;
				$igvSoles = 0;
				$totalSoles = 0;
				$comisionDolares = 0;
				$igvDolares = 0;
				$totalDolares = 0;
				$comisionEuros = 0;
				$igvEuros = 0;
				$totalEuros = 0;
				foreach($liquidaciones as $liquidacion){
					$monedas = "";
					$lista[$i]["idLista"] = $i + 1;
					$lista[$i]["id"] = $i + 1;
					$lista[$i]["numero"] = $liquidacion->getFactura();
					$lista[$i]["fecha"] = $liquidacion->getFechaFactura("DATE");
					$lista[$i]["cia"] = Fabrica::getFromDB("Compania",$liquidacion->getIdCompania())->getNombre();
					$lista[$i]["observaciones"] = $liquidacion->getObservaciones();
					$lista[$i]["comision"] = number_format($liquidacion->getSubTotal(),2);
					$lista[$i]["IGV"] = number_format($liquidacion->getIgv(),2);
					$lista[$i]["total"] = number_format($liquidacion->getTotalFactura(),2);
					$cobros = Fabrica::getAllFromDB("Cobro",array("idLiquidacion = '" . $liquidacion->getId() . "'"));
					if(count($cobros)>0){
						$lista[$i]["moneda"] = $cobros[0]->moneda();
						if($cobros[0]->moneda() == "Soles"){
							$comisionSoles += $liquidacion->getSubTotal();
							$igvSoles += $liquidacion->getIgv();
							$totalSoles += $liquidacion->getTotalFactura();
						}elseif($cobros[0]->moneda() == "Dolares"){
							$comisionDolares += $liquidacion->getSubTotal();
							$igvDolares += $liquidacion->getIgv();
							$totalDolares += $liquidacion->getTotalFactura();
						}elseif($cobros[0]->moneda() == "Euros"){
							$comisionEuros += $liquidacion->getSubTotal();
							$igvEuros += $liquidacion->getIgv();
							$totalEuros += $liquidacion->getTotalFactura();					
						}
					}else{
						$lista[$i]["moneda"] = "Sin definir";
					}
					$j = 0;
					foreach($cobros as $cobro){
						$lista[$i]["cobros"][$j]["id"] = $j + 1;
						$lista[$i]["cobros"][$j]["asegurado"] = $cobro->cliente();
						$lista[$i]["cobros"][$j]["avisoCobranza"] = $cobro->getAvisoDeCobranza();
						$lista[$i]["cobros"][$j]["poliza"] = $cobro->poliza();
						$lista[$i]["cobros"][$j]["ramo"] = $cobro->ramo();
						$lista[$i]["cobros"][$j]["comision"] = number_format($cobro->getComision(),2);
						$lista[$i]["cobros"][$j]["IGV"] = number_format($cobro->getComision()*0.18,2);
						$lista[$i]["cobros"][$j]["total"] = number_format($cobro->getComision()*1.18,2);
						$j++;
					}
					$i++;
				}
				$this->addVar("comisionSoles", number_format($comisionSoles,2));
				$this->addVar("igvSoles", number_format($igvSoles,2));
				$this->addVar("totalSoles", number_format($totalSoles,2));
				$this->addVar("comisionDolares", number_format($comisionDolares,2));
				$this->addVar("igvDolares", number_format($igvDolares,2));
				$this->addVar("totalDolares", number_format($totalDolares,2));
				$this->addVar("comisionEuros", number_format($comisionEuros,2));
				$this->addVar("igvEuros", number_format($igvEuros,2));
				$this->addVar("totalEuros", number_format($totalEuros,2));
				//var_dump($lista);
				$this->addLoop("facturas", $lista);
				$this->addVar("mesNum", $this->request->mes);
				$this->addVar("mes", $meses[$this->request->mes]);
				$this->addVar("anio", $this->request->anio);
				if($this->request->vista!=1){
					$this->addLayout("adminAlone");
					$this->processTemplate("liquidaciones/reporteRecibidas2.html");
				}else{
					$this->processTemplate("liquidaciones/reporteRecibidas.html");
				}
			}else if($this->request->reporte=="3"){
				//comisiones cedidas
				if($this->request->asesor!=""){
					$cedidas = Fabrica::getAllFromDB("Cedida",array("MONTH(fechaFactura) = '" . $this->request->mes . "'", "YEAR(fechaFactura) = '" . $this->request->anio . "'","idPersona = '" . $this->request->asesor . "'"));
				}else{
					$cedidas = Fabrica::getAllFromDB("Cedida",array("MONTH(fechaFactura) = '" . $this->request->mes . "'", "YEAR(fechaFactura) = '" . $this->request->anio . "'"));
				}
				
				$i=0;
				$lista = array();
				$comisionSoles = 0;
				$igvSoles = 0;
				$totalSoles = 0;
				$comisionDolares = 0;
				$igvDolares = 0;
				$totalDolares = 0;
				$comisionEuros = 0;
				$igvEuros = 0;
				$totalEuros = 0;
				foreach($cedidas as $cedida){
					$monedas = "";
					$lista[$i]["idLista"] = $i + 1;
					$lista[$i]["id"] = $i + 1;
					$lista[$i]["numero"] = $cedida->getFactura();
					$lista[$i]["fecha"] = $cedida->getFechaFactura("DATE");
					$lista[$i]["vendedor"] = $cedida->vendedor();
					$lista[$i]["observaciones"] = $cedida->getObservaciones();
					$lista[$i]["subTotal"] = number_format($cedida->getSubTotal(),2);
					$lista[$i]["IGV"] = number_format($cedida->getIgv(),2);
					$lista[$i]["total"] = number_format($cedida->getTotalFactura(),2);
					$cobros = Fabrica::getAllFromDB("Cobro",array("idCedida = '" . $cedida->getId() . "'"));
					if(count($cobros)>0){
						$lista[$i]["moneda"] = $cobros[0]->moneda();
						if($cobros[0]->moneda() == "Soles"){
							$comisionSoles += $cedida->getSubTotal();
							$igvSoles += $cedida->getIgv();
							$totalSoles += $cedida->getTotalFactura();
						}elseif($cobros[0]->moneda() == "Dolares"){
							$comisionDolares += $cedida->getSubTotal();
							$igvDolares += $cedida->getIgv();
							$totalDolares += $cedida->getTotalFactura();
						}elseif($cobros[0]->moneda() == "Euros"){
							$comisionEuros += $cedida->getSubTotal();
							$igvEuros += $cedida->getIgv();
							$totalEuros += $cedida->getTotalFactura();					
						}
					}else{
						$lista[$i]["moneda"] = "Sin definir";
					}
					$j = 0;
					foreach($cobros as $cobro){
						$lista[$i]["cobros"][$j]["id"] = $j + 1;
						$lista[$i]["cobros"][$j]["asegurado"] = $cobro->cliente();
						$lista[$i]["cobros"][$j]["avisoCobranza"] = $cobro->getAvisoDeCobranza();
						$lista[$i]["cobros"][$j]["poliza"] = $cobro->poliza();
						$lista[$i]["cobros"][$j]["ramo"] = $cobro->ramo();
						$lista[$i]["cobros"][$j]["primaNeta"] = number_format($cobro->getPrimaNeta(),2);
						$lista[$i]["cobros"][$j]["comisionP"] = number_format($cobro->getComisionP(),2);
						$lista[$i]["cobros"][$j]["comision"] = 	number_format($cobro->getComision(),2);										
						$lista[$i]["cobros"][$j]["comisionCP"] = number_format($cobro->getComisionCedidaP(),2);
						$lista[$i]["cobros"][$j]["comisionC"] = number_format($cobro->getComisionCedida(),2);
						$j++;
					}
					$i++;
				}
				$this->addVar("comisionSoles", number_format($comisionSoles,2));
				$this->addVar("igvSoles", number_format($igvSoles,2));
				$this->addVar("totalSoles", number_format($totalSoles,2));
				$this->addVar("comisionDolares", number_format($comisionDolares,2));
				$this->addVar("igvDolares", number_format($igvDolares,2));
				$this->addVar("totalDolares", number_format($totalDolares,2));
				$this->addVar("comisionEuros", number_format($comisionEuros,2));
				$this->addVar("igvEuros", number_format($igvEuros,2));
				$this->addVar("totalEuros", number_format($totalEuros,2));
				//var_dump($lista);
				$this->addLoop("facturas", $lista);
				$this->addVar("mesNum", $this->request->mes);
				$this->addVar("mes", $meses[$this->request->mes]);
				$this->addVar("anio", $this->request->anio);
				$this->addVar("asesor", $this->request->asesor);
				if($this->request->vista!=1){
					$this->addLayout("adminAlone");
					$this->processTemplate("liquidaciones/reporteCedidas2.html");
				}else{
					$this->processTemplate("liquidaciones/reporteCedidas.html");
				}				
			}
		}
	}
}
?>