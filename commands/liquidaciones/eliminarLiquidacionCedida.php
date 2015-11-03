<?php
class eliminarLiquidacionCedida extends SessionCommand{
	function execute(){
		$fc=FrontController::instance();
		if($this->request->idCedida){
			$cobros = Fabrica::getAllFromDB("Cobro",array("idCedida = '" . $this->request->idCedida . "'"));
			if(count($cobros)==0){
				$cedida = Fabrica::getFromDB("Cedida",$this->request->idCedida);
				$cedida->deleteFromDB();
				echo json_encode(true);
			}else{
				echo json_encode(false);
			}
		}else{
			echo json_encode(false);
		}
	}
}
?>
