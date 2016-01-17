<?php
class toggleMes extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$usuario=$this->getUsuario();
		$response["respuesta"]="FAIL";
		if($this->request->id){
			$mes = Fabrica::getFromDB("Mes",$this->request->id);
			if($mes->getEstado()=="1"){
				$mes->setEstado("0");
			}else{
				$mes->setEstado("1");
			}
			$mes->storeIntoDB();	
			$response["respuesta"]="SUCCESS";
		}
		echo json_encode($response);
	}
}
?>