<?php
class eliminarPDF extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$response = 0;
		if($this->request->idCliente){
			$cliente = Fabrica::getFromDB("Cliente",$this->request->idCliente);
			$cliente->setCartanombramiento("");
			$cliente->storeIntoDB();
			$response = 1;
		}
		return json_decode($response);
	}
}
?>