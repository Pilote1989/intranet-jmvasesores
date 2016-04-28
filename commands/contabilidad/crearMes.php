<?php
class crearMes extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$usuario=$this->getUsuario();
		//reviso si esta creado
		$fc->import("lib.Mes");
		
		$response["respuesta"]="FAIL";
		$mesCheck = Fabrica::getAllFromDB("Mes", array("mes = '" . date('n') . "'","anio = '" . date('Y') . "'"));	
		if(count($mesCheck) == 0){
			$mes = new Mes();
			$mes->setEstado(0);
			$mes->setMes(date('n'));
			$mes->setAnio(date('Y'));	
			$mes->storeIntoDB();
			$response["respuesta"]="SUCCESS";
		}
		echo json_encode($response);
	}
}
?>