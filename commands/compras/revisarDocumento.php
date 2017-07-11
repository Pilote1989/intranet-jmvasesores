<?php
class revisarDocumento extends sessionCommand{
	function execute(){
		header('Content-type: application/json');
		//ini_set('display_errors', 1);
		//ini_set('display_startup_errors', 1);
		//error_reporting(E_ALL);
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$valid = true;
		if($this->request->ruc){
			$cliente=Fabrica::getAllFromDB("Cliente",array("doc LIKE '" . $this->request->ruc . "'","tipoDoc LIKE 'RUC'"));
			if(count($cliente)>0){
				$compra=Fabrica::getAllFromDB("Compra",array("numeroFactura LIKE '" . $this->request->documento . "'","idCliente LIKE '" . $cliente[0]->getId() . "'"));
				if(count($compra)>0){
					$valid=false;
				}				
				
			}
		}
		echo json_encode($valid);
	}
}
?>