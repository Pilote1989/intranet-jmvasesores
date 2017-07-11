<?php
class eliminarCompra extends SessionCommand{
	function execute(){
		$fc=FrontController::instance();
		if($this->request->idCompra){
			$compra = Fabrica::getFromDB("Compra", $this->request->idCompra );
			$compra->deleteFromDB();
			echo json_encode(true);
		}else{
			echo json_encode(false);
		}
	}
}
?>
