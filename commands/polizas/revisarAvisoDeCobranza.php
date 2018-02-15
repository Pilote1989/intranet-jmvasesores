<?php
class revisarAvisoDeCobranza extends sessionCommand{
	function execute(){
		header('Content-type: application/json');
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$valid = true;
		if($this->request->aviso){
			$cobroParaRevisar=Fabrica::getAllFromDB("Cobro",array("avisoDeCobranza LIKE '" . $this->request->aviso . "'"));
			foreach($cobroParaRevisar as $tempCobro){
				if($this->request->poliza){
					$polizaTest=Fabrica::getAllFromDB("Poliza",array("idCobro = '" . $tempCobro->getId() . "'","anulada = '0'","estado = '1'","idPoliza <> '".$this->request->poliza."'"));
				}else{
					$polizaTest=Fabrica::getAllFromDB("Poliza",array("idCobro = '" . $tempCobro->getId() . "'","anulada = '0'","estado = '1'"));
				}
				if($this->request->endoso){
					$endosoTest=Fabrica::getAllFromDB("Endoso",array("idCobro = '" . $tempCobro->getId() . "'","anulada = '0'","idEndoso <> '".$this->request->endoso."'"));
				}else{
					$endosoTest=Fabrica::getAllFromDB("Endoso",array("idCobro = '" . $tempCobro->getId() . "'","anulada = '0'"));
				}
				if(count($polizaTest)){
					$valid = false;
					$response["m"]="Hay una poliza con ese aviso de cobranza.";
				}elseif(count($endosoTest)){
					$valid = false;
					$response["m"]="Hay un endoso con ese aviso de cobranza.";
				}
			}
		}
		echo json_encode($valid);
	}
}
?>