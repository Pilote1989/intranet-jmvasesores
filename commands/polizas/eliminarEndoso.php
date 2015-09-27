<?php
class eliminarEndoso extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Endoso");		
		$fc->import("lib.Cobro");		
		if($this->request->idEndoso){
			$endoso = Fabrica::getFromDB("Endoso",$this->request->idEndoso);
			$cobro = Fabrica::getFromDB("Cobro",$endoso->getIdCobro());
			if($cobro->getIdLiquidacion()!="" || $cobro->getIdCedida()!=""){
				//no se puede eliminar ya ha sido cobrada
				echo json_encode(false);
			}else{
				//si se puede eliminar aun no ha sido cobrada
				$endoso->deleteFromDB();
				$cobro->deleteFromDB();
				echo json_encode(true);
			}
		}
	}
}
?>