<?php
class anularEndoso extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Endoso");
		$response["respuesta"]="FAIL";
		if($this->request->idEndoso){
			$endoso=Fabrica::getFromDB("Endoso",$this->request->idEndoso);
			if($endoso->getAnulada()=="0"){
				//lo tengo que anular
				$endoso->setAnulada("1");
				$endoso->setMotivoAnulacion($this->request->motivo);
				$endoso->storeIntoDB();
				$response["respuesta"]="SUCCESS";
			}else{
				$testAviso=true;
				//lo tengo que rehabilitar
				$cobro=Fabrica::getFromDB("Cobro",$endoso->getIdCobro());
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
					$endoso->setAnulada('0');
					$endoso->storeIntoDB();
					$response["respuesta"]="SUCCESS";	
				}else{
					$response["respuesta"]="FAIL";	
				}
			}
			echo json_encode($response);
		}else{
			echo json_encode(false);	
		}
	}
}
?>