<?php
class eliminarCliente extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cliente");
		
		if($this->request->idCliente){
			
			$polizas=Fabrica::getAllFromDB("Poliza",array("idCliente = " . $this->request->idCliente, "estado = '1'"));
			if(count($polizas)>0){
				$fc->redirect("?do=clientes.ver&idCliente=" . $this->request->idCliente . "&m=1");				
			}else{
				$cliente=Fabrica::getFromDB("Cliente",$this->request->idCliente);
				$cliente->setEstado('0');
				$cliente->storeIntoDB();
				$fc->redirect("?do=clientes.verCliente");				
				//echo "2";
			}
		}else{
			//echo "1";
			$fc->redirect("?do=clientes.verClientes");
		}				
	}
}
?>