<?php
class anularRenovacion extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Poliza");
		if($this->request->idPoliza){
			$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$poliza->setAnulada('1');
			$poliza->storeIntoDB();
			//http://intranet.jmvasesores.com/?do=polizas.ver&idPoliza=274&vig=288
			$fc->redirect("?do=polizas.ver&idPoliza&idPoliza=" . $poliza->matriz());
		}else{
			$fc->redirect("?do=polizas.verPolizas");
		}				
	}
}
?>