<?php
class verDatosBasicos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->idPoliza){
			$this->addBlock("bloqueEditarPolizas");
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$cobro = Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
			$this->addVar("observaciones", $poliza->getObservaciones());
			$this->addVar("moneda", $cobro->getMoneda());
			$simbolo="US$ ";
			if($poliza->getMoneda()=="Dolares"){
				$this->addVar("prima", "US&#36; " . number_format($cobro->getPrimaNeta(),2));
			}elseif($poliza->getMoneda()=="Soles"){
				$this->addVar("prima", "S/. " . number_format($cobro->getPrimaNeta(),2));
				$simbolo="S/. ";
			}elseif($poliza->getMoneda()=="Euros"){
				$this->addVar("prima", "&euro; " . number_format($cobro->getPrimaNeta(),2));
				$simbolo="&euro; ";
			}else{
				$this->addVar("prima", "US&#36; " . number_format($cobro->getPrimaNeta(),2));
			}
			$this->addVar("fechaInicio", $poliza->getInicioVigencia("DATE"));
			$this->addVar("fechaFin", $poliza->getFinVigencia("DATE"));
			$compania = Fabrica::getFromDB("Compania", $poliza->getIdCompania());
			$this->addVar("compania", $compania->getNombre());
			$this->addVar("idCliente", $poliza->getIdCliente());
			$cliente = Fabrica::getFromDB("Cliente", $poliza->getIdCliente());
			$this->addVar("nombre", $cliente->getNombre());
			$ramo = Fabrica::getFromDB("Ramo", $poliza->getIdRamo());
			$this->addVar("ramo", $ramo->getNombre());
			$this->addVar("nombreFicha", "Ficha Poliza");
			$this->addVar("idPoliza",$this->request->idPoliza);
			$this->addVar("menu", "menuPolizas?idPoliza=".$this->request->idPoliza);
			//print_r($poliza);
			$this->addVar("pdf", $poliza->getPdf());
			$this->addVar("colorEstado", $poliza->estadoColor());
			$this->addVar("estado", $poliza->estado());
			if($cobro->getIdLiquidacion() == ""){
				$this->addVar("estadoCobranza", '<i class="ace-icon fa fa-circle red"></i>&nbsp;&nbsp;&nbsp;Pendiente');
			}else{
				$liquidacion = Fabrica::getFromDB("Liquidacion", $cobro->getIdLiquidacion());
				$this->addVar("estadoCobranza", '<i class="ace-icon fa fa-circle green"></i>&nbsp;&nbsp;&nbsp;<a href="?do=liquidaciones.ver&idLiquidacion=' . $cobro->getIdLiquidacion() . '">Liquidada - Factura : ' . $liquidacion->getFactura() . '</a>');
			}
			$this->addVar("cobranza", $cobro->getAvisoDeCobranza());
			$this->addVar("documento", $poliza->getDocumento());
			$this->addVar("primaNeta", $simbolo . number_format($cobro->getPrimaNeta(),2));
			$this->addVar("derecho", $simbolo . number_format($cobro->getDerechoEmision(),2));
			$this->addVar("intereses", $simbolo . number_format($cobro->getIntereses(),2));
			$this->addVar("igv", $simbolo . number_format($cobro->getIgv(),2));
			$this->addVar("factura", $simbolo . number_format($cobro->getTotalFactura(),2));
			$this->addVar("comision", number_format($cobro->getComisionP(),2) . "%");
			$this->addVar("comisionNum", $simbolo . number_format($cobro->getComision(),2));
			if($cobro->getIdPersona()!=1){
    		    $this->addBlock("datosComision");
            }
			if($cobro->getIdCedida() == ""){
				$this->addVar("estadoComisionCedida", '<i class="ace-icon fa fa-circle red"></i>&nbsp;&nbsp;&nbsp;Pendiente');
			}else{
				$cedida = Fabrica::getFromDB("Cedida", $cobro->getIdCedida());
				$this->addVar("estadoComisionCedida", '<i class="ace-icon fa fa-circle green"></i>&nbsp;&nbsp;&nbsp;<a href="?do=liquidaciones.verCedida&idCedida=' . $cobro->getIdCedida() . '">Liquidada - Factura : ' . $cedida->getFactura() . '</a>');
			}
			$persona = Fabrica::getFromDB("Persona", $cobro->getIdPersona());
			$this->addVar("asesor", $persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno());
			$this->addVar("comisionCedidaNum", $simbolo . number_format($cobro->getComisionCedida(),2));
			$this->addVar("comisionCedida", number_format($cobro->getComisionCedidaP(),2) . "%");
			//echo $poliza->getPdf() . "sd";
			if($poliza->getPdf()==""){
				$this->addBlock("noPDF");
			}else{
				$this->addBlock("siPDF");
			}
			
			//$vigencias = Fabrica::getAllFromDB("Vigencia", array("idPoliza = " . $poliza->getId()), "inicioVigencia ASC");
			//$listaVigencias = array();
			//$i = 0;
			if($this->checkAccess("crearUsuario", true)){
				$this->addBlock("admin");
			}
			$vigencias = Fabrica::getAllFromDB("Poliza", array("numeroPoliza = '" . $poliza->getNumeroPoliza() . "'","estado = '1'"), "inicioVigencia DESC");			
			foreach($vigencias as $vigencia){
				$cobroTemp = Fabrica::getFromDB("Cobro",$vigencia->getIdCobro());
				$listaVigencias[$i]["aviso"] = $cobroTemp->getAvisoDeCobranza();
				$listaVigencias[$i]["inicioVigencia"] = $vigencia->getInicioVigencia("DATE");
				$listaVigencias[$i]["finVigencia"] = $vigencia->getFinVigencia("DATE");
				
				if($vigencia->getMoneda()=="Dolares"){
					$listaVigencias[$i]["prima"] =  "US&#36; " . number_format($vigencia->primaNetaTotal(),2);
				}elseif($vigencia->getMoneda()=="Soles"){
					$listaVigencias[$i]["prima"] =  "S/. " . number_format($vigencia->primaNetaTotal(),2);
				}elseif($vigencia->getMoneda()=="Euros"){
					$listaVigencias[$i]["prima"] =  "&euro; " . number_format($vigencia->primaNetaTotal(),2);
				}else{
					$listaVigencias[$i]["prima"] =  "US&#36; " . number_format($vigencia->primaNetaTotal(),2);
				}
				
				if($vigencia->getId()!=$this->request->idPoliza){
					$listaVigencias[$i]["idLista"] = $i + 1;
				}else{
					$listaVigencias[$i]["idLista"] = $i + 1;
					$listaVigencias[$i]["idLista"] = "<i class='ace-icon fa fa-arrow-right ace-icon fa fa-on-right'></i> ". $listaVigencias[$i]["idLista"];					
				}
					$listaVigencias[$i]["estado"] = $vigencia->estadoLabel() . "";
				$listaVigencias[$i]["idVigencia"] = $vigencia->getId();
				if($vigencia->getTipo()=='POL'){
					$this->addVar("matriz", $vigencia->getId());
				}
				$i++;
			}
			if($poliza->getAnulada()=='1'){
				$this->addBlock('Anulada');
			}else{
				$this->addBlock('NoAnulada');
			}
					
			$this->addLoop("vigencias", $listaVigencias);
			$this->processTemplate("polizas/verDatosBasicos.html");
		}
	}
}
?>