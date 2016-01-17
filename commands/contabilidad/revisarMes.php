<?php
class revisarMes extends sessionCommand{
	function execute(){
		header('Content-type: application/json');
		//ini_set('display_errors', 1);
		//ini_set('display_startup_errors', 1);
		//error_reporting(E_ALL);
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$valid = false;
		if($this->request->mes && $this->request->anio){
			$meses = Fabrica::getAllFromDB("Mes", array("mes = '" . $this->request->mes . "'", "anio = '" . $this->request->anio . "'"));
			if(count($meses)>0){
				if($meses[0]->getEstado()=="0"){
					$valid = true;
				}
			}else{
				$valid = false;
			}		
		}
		echo json_encode($valid);
	}
}
?>