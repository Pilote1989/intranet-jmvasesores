<?php
class procesarDatosBasicos extends SessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$fc->import("lib.Liquidacion");
		$fc->import("lib.Cobro");
		//print_r($this->request);
		//echo $this->request->fecha;
		
		if($this->request->idLiquidacion){
			//edito
			$liquidacion = Fabrica::getFromDB("Liquidacion",$this->request->idLiquidacion);
			$id = $this->request->idLiquidacion;
			$liquidacion->setFactura($this->request->factura);
			$liquidacion->setFechaFactura($this->request->fecha,"DATE");
			$liquidacion->setObservaciones($this->request->obser);
			$liquidacion->setIdCompania($this->request->compania);
			$liquidacion->setSubTotal($this->request->subTotal);
			$liquidacion->setMoneda($this->request->mon);
			$liquidacion->setIgv($this->request->igv);
			$liquidacion->setTotalFactura($this->request->totalFac);
			$liquidacion->storeIntoDB();
			$i = 0;
			foreach($this->request->idCobro as $idCobro){
				$cobro = Fabrica::getFromDB("Cobro",$idCobro);
				$cobro->setComisionP($this->request->facComisionP[$i]);
				$cobro->setComision($this->request->facComision[$i]);
				$cobro->setIdLiquidacion($id);
				$cobro->storeIntoDB();
				$i++;
			}			
		}else{
			//nuevo
			$liquidacion = new Liquidacion();
			$liquidacion->setFactura($this->request->factura);
			$liquidacion->setFechaFactura($this->request->fecha,"DATE");
			$liquidacion->setObservaciones($this->request->obser);
			$liquidacion->setIdCompania($this->request->compania);
			$liquidacion->setSubTotal($this->request->subTotal);
			$liquidacion->setMoneda($this->request->mon);
			$liquidacion->setIgv($this->request->igv);
			$liquidacion->setTotalFactura($this->request->totalFac);
			$liquidacion->storeIntoDB();
			$dbLink=&FrontController::instance()->getLink();
			$dbLink->next_result();
			$id=$dbLink->insert_id;
			$dbLink->next_result();
			$i = 0;
			foreach($this->request->idCobro as $idCobro){
				$cobro = Fabrica::getFromDB("Cobro",$idCobro);
				$cobro->setComisionP($this->request->facComisionP[$i]);
				$cobro->setComision($this->request->facComision[$i]);
				$cobro->setIdLiquidacion($id);
				$cobro->storeIntoDB();
				$i++;
			}
			//print_r($this->request);
		}
		$fc->redirect("?do=liquidaciones.ver&idLiquidacion=" . $id);
	}
}
?>
