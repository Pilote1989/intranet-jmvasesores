<?php
class checkNuevoRuc extends sessionCommand{
	function execute(){
		header('Content-type: application/json');
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$valid = false;
		if($this->request->doc){
			$clientes = Fabrica::getAllFromDB("Cliente", array("doc = '" . $this->request->doc . "'", "tipoDoc = 'RUC'"));
			if(count($clientes)>0){
					$valid = false;
			}else{
				$valid = true;
			}		
		}
		echo json_encode($valid);
	}
}
?>