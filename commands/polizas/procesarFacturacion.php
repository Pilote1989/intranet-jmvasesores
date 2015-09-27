<?php
class procesarFacturacion extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$fc->import("lib.Endoso");
		$fc->import("lib.Cobro");
		$usuario=$this->getUsuario();
		$response["respuesta"]="FAIL";
		if($this->request->idPoliza){
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$cobro = Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
			$cobro->setAvisoDeCobranza($this->request->cobranza);
			$cobro->setPrimaNeta($this->request->primaNeta);
			$cobro->setDerechoEmision($this->request->derechoEmision);
			$cobro->setPrimaComercial($this->request->primaComercial);
			$cobro->setIgv($this->request->igv);
			$cobro->setIntereses($this->request->intereses);
			$cobro->setTotalFactura($this->request->totalFactura);
			$cobro->setComisionP($this->request->comisionP);
			$cobro->setComision($this->request->comision);
			$cobro->storeIntoDB();		
			$response["respuesta"]="SUCCESS";
		}
		echo json_encode($response);
	}
}
?>