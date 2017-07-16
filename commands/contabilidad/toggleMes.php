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
				//mes abierto
				$mes->setEstado("0");
				$mes->setPrimaNetaDolares("0");
				$mes->setComisionDolares("0");
				$mes->setIgvFacturasDolares("0");
				$mes->setTotalFacturasDolares("0");
				$mes->setComisionCedidaDolares("0");
				$mes->setIgvCedidasDolares("0");
				$mes->setTotalCedidasDolares("0");
				$mes->setSubtotalComprasDolares("0");
				$mes->setIgvComprasDolares("0");
				$mes->setTotalComprasDolares("0");
				
				$mes->setPrimaNetaSoles("0");
				$mes->setComisionSoles("0");
				$mes->setIgvFacturasSoles("0");
				$mes->setTotalFacturasSoles("0");
				$mes->setComisionCedidaSoles("0");
				$mes->setIgvCedidasSoles("0");
				$mes->setTotalCedidasSoles("0");
				$mes->setSubtotalComprasSoles("0");
				$mes->setIgvComprasSoles("0");
				$mes->setOtrosComprasSoles("0");
				$mes->setTotalComprasSoles("0");
			}else{
				//mes cerrado
				$mes->setEstado("1");
				$mes->setPrimaNetaDolares($mes->calPrimaNeta("Dolares"));
				$mes->setComisionDolares($mes->calComision("Dolares"));
				$mes->setIgvFacturasDolares($mes->calIgvFacturas("Dolares"));
				$mes->setTotalFacturasDolares($mes->calTotalFacturas("Dolares"));
				$mes->setComisionCedidaDolares($mes->calComisionCedida("Dolares"));
				$mes->setIgvCedidasDolares($mes->calIgvCedidas("Dolares"));
				$mes->setTotalCedidasDolares($mes->calTotalCedidas("Dolares"));
				$mes->setSubtotalComprasDolares($mes->calSubtotalCompras("Dolares"));
				$mes->setIgvComprasDolares($mes->calIgvCompras("Dolares"));
				$mes->setTotalComprasDolares($mes->calTotalCompras("Dolares"));
				
				$mes->setPrimaNetaSoles($mes->calPrimaNeta("Soles"));
				$mes->setComisionSoles($mes->calComision("Soles"));
				$mes->setIgvFacturasSoles($mes->calIgvFacturas("Soles"));
				$mes->setTotalFacturasSoles($mes->calTotalFacturas("Soles"));
				$mes->setComisionCedidaSoles($mes->calComisionCedida("Soles"));
				$mes->setIgvCedidasSoles($mes->calIgvCedidas("Soles"));
				$mes->setTotalCedidasSoles($mes->calTotalCedidas("Soles"));
				$mes->setSubtotalComprasSoles($mes->calSubtotalCompras("Soles"));
				$mes->setIgvComprasSoles($mes->calIgvCompras("Soles"));
				$mes->setOtrosComprasSoles($mes->calOtrosCompras("Soles"));
				$mes->setTotalComprasSoles($mes->calTotalCompras("Soles"));
			}
			$mes->storeIntoDB();	
			$response["respuesta"]="SUCCESS";
		}
		echo json_encode($response);
	}
}
?>