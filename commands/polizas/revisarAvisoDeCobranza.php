<?php
class revisarAvisoDeCobranza extends sessionCommand{
	function execute(){
		header('Content-type: application/json');
		//ini_set('display_errors', 1);
		//ini_set('display_startup_errors', 1);
		//error_reporting(E_ALL);
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$valid = false;
		if($this->request->aviso){
			$cobro=Fabrica::getAllFromDB("Cobro",array("avisoDeCobranza LIKE'" . $this->request->aviso . "'"));
			if(count($cobro)>0){
				$valid = false;
			}else{
				$valid = true;
			}		
		}
		echo json_encode($valid);
	}
}
?>