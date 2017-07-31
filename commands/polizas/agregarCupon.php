<?php
class agregarCupon extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("idPoliza",$this->request->idPoliza);
		if($this->request->idCupon){
			$this->addVar("idCupon",$this->request->idCupon);
			$cupon = Fabrica::getFromDB("Cupon",$this->request->idCupon);
			$this->addVar("monto",$cupon->getMonto());	
			$this->addVar("fechaVencimiento",$cupon->getFechaVencimiento("DATE"));	
			$this->addVar("numeroCupon",$cupon->getNumeroCupon());
		}else{
			$this->addEmptyVar("idCupon");
			$this->addEmptyVar("monto");	
			$this->addEmptyVar("fechaVencimiento");	
			$this->addEmptyVar("numeroCupon");
		}
		
		$this->processTemplate("polizas/agregarCupon.html");
		
	}
}
?>