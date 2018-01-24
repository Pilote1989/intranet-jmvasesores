<?php
class eliminarPDFendoso extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$response = 0;
		if($this->request->idEndoso){
			$endoso = Fabrica::getFromDB("Endoso",$this->request->idEndoso);
			$endoso->setPdf("");
			$endoso->storeIntoDB();
			$response = 1;
		}
		return json_decode($response);
	}
}
?>