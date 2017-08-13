<?php
class procesarCedida extends SessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$fc->import("lib.Cedida");
		$fc->import("lib.Cobro");
		if($this->request->idCedida){
			//edito
			$cedida = Fabrica::getFromDB("Cedida",$this->request->idCedida);
			$id = $this->request->idCedida;
			$cedida->setFactura($this->request->factura);
			$cedida->setFechaFactura($this->request->fecha,"DATE");
			$cedida->setObservaciones($this->request->obser);
			$cedida->setSubTotal($this->request->subTotal);
			$cedida->setIgv($this->request->igv);
			$cedida->setTotalFactura($this->request->totalFac);
			$cedida->storeIntoDB();
			$i = 0;
			foreach($this->request->idCobro as $idCobro){
				$cobro = Fabrica::getFromDB("Cobro", $idCobro);
				$cobro->setComisionCedidaP($this->request->facComisionP[$i]);
				$cobro->setComisionCedida($this->request->facComision[$i]);
				$cobro->setIdCedida($id);
				$cobro->storeIntoDB();
				$i++;
			}
			$fc->redirect("?do=liquidaciones.verCedida&idCedida=" . $id);
		}else{
			//nuevo
			$cedida = new Cedida();
			$cedida->setFactura($this->request->factura);
			$cedida->setFechaFactura($this->request->fecha,"DATE");
			$cedida->setObservaciones($this->request->obser);
			$cedida->setIdPersona($this->request->persona);
			$cedida->setSubTotal($this->request->subTotal);
			$cedida->setIgv($this->request->igv);
			$cedida->setTotalFactura($this->request->totalFac);
			$cedida->setMoneda($this->request->mon);
			$cedida->storeIntoDB();
			$dbLink=&FrontController::instance()->getLink();
			$dbLink->next_result();
			$id=$dbLink->insert_id;
			$dbLink->next_result();
			$i = 0;
			foreach($this->request->idCobro as $idCobro){
				$cobro = Fabrica::getFromDB("Cobro", $idCobro);
				$cobro->setComisionCedidaP($this->request->facComisionP[$i]);
				$cobro->setComisionCedida($this->request->facComision[$i]);
				$cobro->setIdCedida($id);
				$cobro->storeIntoDB();
				$i++;
			}
			print_r($this->request);
		}
		$fc->redirect("?do=liquidaciones.verCedida&idCedida=" . $id);
	}
}
?>