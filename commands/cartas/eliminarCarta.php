<?php
class eliminarCarta extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Carta");				
		if($this->request->idCarta){
			$carta = Fabrica::getFromDB("Carta",$this->request->idCarta);
			$carta->deleteFromDB();
			//$fc->redirect("?do=polizas.ver&idPoliza=" . $poliza . "&t=c");
			echo json_encode(true);
		}
	}
}
?>