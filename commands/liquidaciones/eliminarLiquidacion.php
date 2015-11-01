<?php
class eliminarLiquidacion extends SessionCommand{
	function execute(){
		$fc=FrontController::instance();
		if($this->request->idLiquidacion){
			$cobros = Fabrica::getAllFromDB("Cobro",array("idLiquidacion = '" . $this->request->idLiquidacion . "'"));
			if(count($cobros)==0){
				$liquidacion = Fabrica::getFromDB("Liquidacion",$this->request->idLiquidacion);
				$liquidacion->deleteFromDB();
				echo json_encode(true);
			}			
			
		}else{
			echo json_encode(false);
		}
	}
}
?>
