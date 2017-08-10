<?php
class procesarBono extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$fc->import("lib.Liquidacion");
		$fc->import("lib.Cobro");
		if($this->request->idLiquidacion){
			//edito
			$liquidacion = Fabrica::getFromDB("Liquidacion",$this->request->idLiquidacion);
			$id = $this->request->idLiquidacion;
			$liquidacion->setFactura($this->request->factura);
			$liquidacion->setIdCompania($this->request->compania);
			$liquidacion->setFechaFactura($this->request->fecha,"DATE");
			$liquidacion->setObservaciones($this->request->obser);
			$liquidacion->setSubTotal($this->request->subTotal);
			$liquidacion->setMoneda($this->request->moneda);
			$liquidacion->setIgv($this->request->igv);
			$liquidacion->setTotalFactura($this->request->totalFac);
			$liquidacion->setBono("1");			
			$liquidacion->storeIntoDB();
		}else{
			//creo bono
			$liquidacion = new Liquidacion();
			$liquidacion->setFactura($this->request->factura);
			$liquidacion->setIdCompania($this->request->compania);
			$liquidacion->setFechaFactura($this->request->fecha,"DATE");
			$liquidacion->setObservaciones($this->request->obser);
			$liquidacion->setSubTotal($this->request->subTotal);
			$liquidacion->setMoneda($this->request->moneda);
			$liquidacion->setIgv($this->request->igv);
			$liquidacion->setTotalFactura($this->request->totalFac);
			$liquidacion->setBono("1");
			$liquidacion->storeIntoDB();
			$dbLink=&FrontController::instance()->getLink();
			$dbLink->next_result();
			$id=$dbLink->insert_id;
			$i = 0;
		}
		$fc->redirect("?do=liquidaciones.ver&idLiquidacion=" . $id);
	}
}
?>