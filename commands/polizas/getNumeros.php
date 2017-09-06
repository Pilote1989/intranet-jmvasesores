<?php
class getNumeros extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$response = array();
		if($this->request->idPoliza){
			$endosos = Fabrica::getAllFromDB("Endoso", array("idPoliza = '" . $this->request->idPoliza . "'"));
			$response["endosos"] = count($endosos);
			$cupones = Fabrica::getAllFromDB("Cupon", array("idPoliza = " . $this->request->idPoliza));
			$response["cupones"] = count($cupones);
			$cartas = Fabrica::getAllFromDB("Carta", array("idPoliza = " . $this->request->idPoliza));
			$response["cartas"] = count($cartas);
		}
		$resultados=array();
		echo json_encode($response);
	}
}
?>