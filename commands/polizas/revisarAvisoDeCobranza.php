<?php
class revisarAvisoDeCobranza extends sessionCommand{
	function execute(){
		header('Content-type: application/json');
		//ini_set('display_errors', 1);
		//ini_set('display_startup_errors', 1);
		//error_reporting(E_ALL);
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$valid = false;
		if($this->request->aviso){
			$cobro=Fabrica::getAllFromDB("Cobro",array("avisoDeCobranza LIKE '" . $this->request->aviso . "'"));
			if(count($cobro)>0){
				$valid = false;
				if($this->request->poliza){
					$poliza=Fabrica::getFromDB("Poliza",$this->request->poliza);
					$cobroPol=Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
					if($cobroPol->getAvisoDeCobranza() == $cobro[0]->getAvisoDeCobranza()){
						$valid = true;
					}
				}elseif($this->request->endoso){
					$endoso=Fabrica::getFromDB("Endoso",$this->request->endoso);
					$cobroEnd=Fabrica::getFromDB("Cobro",$endoso->getIdCobro());
					if($cobroEnd->getAvisoDeCobranza() == $cobro[0]->getAvisoDeCobranza()){
						$valid = true;
					}
				}
				$valid = true;
			}		
		}
		echo json_encode($valid);
	}
}
?>