<?php
class rehabilitaRenovacion extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Poliza");
		$response["respuesta"]="FAIL";
		$testAviso = true;
		if($this->request->idPoliza){
			$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$cobro=Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
			$cobroParaRevisar=Fabrica::getAllFromDB("Cobro",array("avisoDeCobranza LIKE '" . $cobro->getAvisoDeCobranza() . "'"));
			foreach($cobroParaRevisar as $tempCobro){
				$polizaTest=Fabrica::getAllFromDB("Poliza",array("idCobro = '" . $tempCobro->getId() . "'","anulada = '0'","estado = '1'"));
				$endosoTest=Fabrica::getAllFromDB("Endoso",array("idCobro = '" . $tempCobro->getId() . "'","anulada = '0'"));
				if(count($polizaTest)){
					$testAviso = false;
					$response["m"]="No se puede rehabilitar, hay una poliza con ese aviso de cobranza.";
				}elseif(count($endosoTest)){
					$testAviso = false;
					$response["m"]="No se puede rehabilitar, hay un endoso con ese aviso de cobranza.";
				}
			}
			if($testAviso){
				$poliza->setAnulada('0');
				$poliza->storeIntoDB();
				$response["respuesta"]="SUCCESS";	
			}else{
				$response["respuesta"]="FAIL";	
			}
		}			
		echo json_encode($response);
	}
}
?>