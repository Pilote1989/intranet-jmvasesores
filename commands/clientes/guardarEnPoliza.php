<?php
class guardarEnPoliza extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$fc->import("lib.ClienteEnPoliza");
		$usuario=$this->getUsuario();
		$response["respuesta"]="FAIL";
		if($this->request->idPoliza){
			if($this->request->idCliente){
				$clienteEnPoliza = new ClienteEnPoliza();
				$clienteEnPoliza->setIdPoliza($this->request->idPoliza);
				$clienteEnPoliza->setIdCliente($this->request->idCliente);
				$clienteEnPoliza->storeIntoDB();			
				$response["respuesta"]="SUCCESS";
			}
		}
		echo json_encode($response);
	}
}
?>