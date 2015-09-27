<?php
class check extends sessionCommand{
	function execute(){
		header('Content-type: application/json');
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$valid = false;
		if($this->request->doc){
			$clientes = Fabrica::getAllFromDB("Cliente", array("doc = '" . $this->request->doc . "'", "tipoDoc = '" . $this->request->tipoDoc . "'"));
			if(count($clientes)>0){
				if($clientes[0]->getId()==$this->request->id){
					$valid = true;
				}else{
					$valid = false;
				}
			}else{
				$valid = true;
			}		
		}
		echo json_encode($valid);
	}
}
?>