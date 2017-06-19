<?php
class anularEndoso extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Endoso");
		if($this->request->idEndoso){
			$endoso = Fabrica::getFromDB("Endoso",$this->request->idEndoso);
			$endoso->setMotivoAnulacion($this->request->motivo);
			if($endoso->getAnulada()=="1"){
				$endoso->setAnulada("0");
			}else{
				$endoso->setAnulada("1");
			}
			$endoso->storeIntoDB();
			echo json_encode(true);
		}else{
			echo json_encode(false);	
		}
	}
}
?>