<?php
class eliminarAsegurado extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.ClienteEnPoliza");			
		if($this->request->id){
			$porEliminar = Fabrica::getFromDB("ClienteEnPoliza",$this->request->id);
			$porEliminar->deleteFromDB();
			//$fc->redirect("?do=polizas.ver&idPoliza=" . $poliza . "&t=c");
			echo json_encode(true);
		}
	}
}
?>