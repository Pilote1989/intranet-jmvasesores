<?php
class crearBono extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$companias=Fabrica::getAllFromDB("Compania",array(),"nombre ASC");	
		if($this->request->idLiquidacion){
			$liquidacion = Fabrica::getFromDB("Liquidacion",$this->request->idLiquidacion);	
			$monedas = array("Dolares","Soles","Euros");
			$selectMonedas = '';
			foreach ($monedas as $valor) {
				if($liquidacion->getMoneda() == $valor){
					$selectMonedas=$selectMonedas.'\n<option value="'.$valor.'" selected="selected">'.$valor.'</option>';
				}else{
					$selectMonedas=$selectMonedas.'\n<option value="'.$valor.'">'.$valor.'</option>';
				}
			}
			$this->addVar("monedas",$selectMonedas);
			
			$this->addVar("factura",$liquidacion->getFactura());			
			$this->addVar("fechaFactura",$liquidacion->getFechaFactura("DATE"));
			$this->addVar("obser",$liquidacion->getObservaciones());
			$this->addVar("subTotal",$liquidacion->getSubTotal());
			$this->addVar("igv",$liquidacion->getIgv());
			$this->addVar("totalFac",$liquidacion->getTotalFactura());
			$this->addVar("idLiquidacion",$liquidacion->getId());
			$selectCompania = '<option value=""></option>';
			foreach($companias as $compania){
				if($liquidacion->getIdCompania() == $compania->getId()){
					$selectCompania=$selectCompania.'\n<option value="'.$compania->getId().'" selected="selected">'.$compania->getNombre().'</option>';
				}else{
					$selectCompania=$selectCompania.'\n<option value="'.$compania->getId().'" >'.$compania->getNombre().'</option>';
				}
			}
			$this->addVar("companias",$selectCompania);
		}else{
			$monedas = array("Dolares","Soles","Euros");
			$selectMonedas = '';
			foreach ($monedas as $valor) {
				$selectMonedas=$selectMonedas.'\n<option value="'.$valor.'">'.$valor.'</option>';
			}
			$this->addVar("monedas",$selectMonedas);
			$this->addEmptyVar("factura");			
			$this->addEmptyVar("fechaFactura");	
			$this->addEmptyVar("idLiquidacion");
			$this->addEmptyVar("obser");
			$this->addVar("subTotal","0.00");
			$this->addVar("igv","0.00");
			$this->addVar("totalFac","0.00");
			$selectCompania = '<option value=""></option>';
			foreach($companias as $compania){
				$selectCompania=$selectCompania.'\n<option value="'.$compania->getId().'" >'.$compania->getNombre().'</option>';
			}
			$this->addVar("companias",$selectCompania);
		}

		
		$this->addLayout("admin");
		$this->processTemplate("liquidaciones/crearBono.html");
	}
}
?>