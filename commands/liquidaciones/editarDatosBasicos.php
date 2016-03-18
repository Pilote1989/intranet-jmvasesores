<?php
class editarDatosBasicos extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idLiquidacion){
			$liquidacion = Fabrica::getFromDB("Liquidacion", $this->request->idLiquidacion);
			$this->addVar("factura",$liquidacion->getFactura());
			$this->addVar("fechaFactura",$liquidacion->getFechaFactura("DATE"));
			$this->addVar("observaciones",$liquidacion->getObservaciones());
			$seleccionados = array();
			$seleccionados_tipo = array();
			$tipos = array();
			$cobros = Fabrica::getAllFromDB("Cobro",array("idLiquidacion = '" . $this->request->idLiquidacion . "'"));
			foreach($cobros as $cobro){
				$seleccionados[] = '"'.$cobro->getId().'"';
				$seleccionados_tipo[] = '"'.$cobro->tipo().'"';
				//echo $cobro->tipo() . " - " . $cobro->getId(). "<br />";
				if($cobro->getMoneda()=="Dolares"){
					$this->addVar("moneda","1");
				}elseif($cobro->getMoneda()=="Soles"){
					$this->addVar("moneda","2");
				}elseif($cobro->getMoneda()=="Euros"){
					$this->addVar("moneda","3");
				}
			}
			
			$this->addVar("ids",implode(",", $seleccionados));
			$this->addVar("tipos",implode(",", $seleccionados_tipo));
			
			$this->addBlock("bloqueEditarLiquidacion");
			$this->addVar("editar","Editar Liquidacion");
			$this->addVar("idLiquidacion", $this->request->idLiquidacion);
			
			$compania = Fabrica::getFromDB("Compania",$liquidacion->getIdCompania());
			$this->addVar("compania",$compania->getId());
			$this->addVar("companiaNombre",$compania->getNombre());
			

			$this->addLayout("ace");
			$this->processTemplate("liquidaciones/editarDatosBasicos.html");

		}else{
			$this->addBlock("bloqueEditarLiquidacion");
			$this->addVar("editar","Crear Liquidacion");
			$companias=Fabrica::getAllFromDB("Compania",array(),"nombre ASC");	
			$selectCompania = '<option value=""></option>';
			foreach($companias as $compania){
				$selectCompania=$selectCompania.'\n<option value="'.$compania->getId().'" >'.$compania->getNombre().'</option>';
			}
			$this->addVar("companias",$selectCompania);
			$this->addLayout("ace");
			$this->processTemplate("liquidaciones/crear.html");
		}
		
	}
}
?>