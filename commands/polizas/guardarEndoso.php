<?php
class guardarEndoso extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$fc->import("lib.Endoso");
		$fc->import("lib.Cobro");
		$usuario=$this->getUsuario();
		$response["respuesta"]="FAIL";
		if($this->request->idPoliza){
			$poliza = Fabrica::getFromDB("Poliza", $this->request->idPoliza);
			$cobroPoliza = Fabrica::getFromDB("Cobro", $poliza->getIdCobro());
			if($this->request->idEndoso){
				$endoso = Fabrica::getFromDB("Endoso",$this->request->idEndoso);
				$cobro = Fabrica::getFromDB("Cobro",$endoso->getIdCobro());
			}else{
				$endoso = new Endoso();
				$cobro = new Cobro();
			}	
			$endoso->setDocumento($this->request->documento);
			$endoso->setDetalle($this->request->detalle);
			$endoso->setInicioVigencia($this->request->inicioVigencia,"DATE");
			$endoso->setFinVigencia($this->request->finVigencia,"DATE");
			$endoso->setIdPoliza($this->request->idPoliza);
			$cobro->setAvisoDeCobranza($this->request->cobranza);
			$cobro->setPrimaNeta($this->request->primaNeta);
			$cobro->setDerechoEmision($this->request->derechoEmision);
			$cobro->setPrimaComercial($this->request->primaComercial);
			$cobro->setIgv($this->request->igv);
			$cobro->setIntereses($this->request->intereses);
			$cobro->setTotalFactura($this->request->totalFactura);
			$cobro->setComisionP($this->request->comisionP);
			$cobro->setComision($this->request->comision);
			$cobro->setComisionCedidaP($this->request->comisionVendedorP);
			$cobro->setComisionCedida($this->request->comisionVendedor);
			//$cobro->setIdPersona($cobroPoliza->getIdPersona());
			$cobro->setIdPersona($this->request->idPersona);
			$cobro->storeIntoDB();	
			if(!$this->request->idEndoso){
				$dbLink=&FrontController::instance()->getLink();			
				$id=$dbLink->insert_id;
				//si no existe
				$endoso->setIdCobro($id);
			}	
			$endoso->storeIntoDB();		
			$response["respuesta"]="SUCCESS";
		}
		echo json_encode($response);
	}
}
?>