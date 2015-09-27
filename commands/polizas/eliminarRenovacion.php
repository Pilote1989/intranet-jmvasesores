<?php
class eliminarRenovacion extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Poliza");
		if($this->request->idPoliza){
			$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$poliza->setEstado('0');
			$poliza->storeIntoDB();
			//http://intranet.jmvasesores.com/?do=polizas.ver&idPoliza=274&vig=288
			$vigencias = Fabrica::getAllFromDB("Poliza", array("numeroPoliza = " . $poliza->getNumeroPoliza(), "estado = '1'"), "inicioVigencia ASC");
			if(count($vigencias)>0){
				$fc->redirect("?do=polizas.ver&idPoliza&idPoliza=" . $poliza->matriz());
			}else{
				$fc->redirect("?do=polizas.verPolizas");
			}
			
		}else{
			$fc->redirect("?do=polizas.verPolizas");
		}				
	}
}
?>