<?php
class editarCedida extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idCedida){
			$cedida = Fabrica::getFromDB("Cedida", $this->request->idCedida);
			$this->addVar("factura",$cedida->getFactura());
			$this->addVar("fechaFactura",$cedida->getFechaFactura("DATE"));
			$this->addVar("observaciones",$cedida->getObservaciones());
			$this->addVar("moneda",$cedida->getMoneda());
			$seleccionados = array();
			$seleccionados_tipo = array();
			$tipos = array();
			$cobros = Fabrica::getAllFromDB("Cobro",array("idCedida = '" . $this->request->idCedida . "'"));
			foreach($cobros as $cobro){
				$moneda = $cobro->getMoneda();
				$seleccionados[] = '"'.$cobro->getId().'"';
				$seleccionados_tipo[] = '"'.$cobro->tipo().'"';
				//echo $cobro->tipo() . " - " . $cobro->getId(). "<br />";
			}
			
			$this->addVar("ids",implode(",", $seleccionados));
			$this->addVar("tipos",implode(",", $seleccionados_tipo));			
			
			$this->addBlock("bloqueEditarLiquidacion");
			$this->addVar("editar","Editar Liquidacion");
			$this->addVar("idCedida", $this->request->idCedida);
			
			$persona = Fabrica::getFromDB("Persona",$cedida->getIdPersona());
			
			$this->addVar("persona",$cedida->getIdPersona());
			$this->addVar("personaNombre",$persona->getNombres() . " " . $persona->getApellidoPaterno() . " " . $persona->getApellidoMaterno());
			$this->addLayout("ace");
			$this->processTemplate("liquidaciones/editarCedida.html");

		}else{
			$this->addBlock("bloqueEditarLiquidacion");
			$this->addVar("editar","Crear Liquidacion de Comisiones Cedidas");
			
			
			$personas=Fabrica::getAllFromDB("Persona",array(),"nombres ASC");	
			$selectPersona = '<option value=""></option>';
			foreach($personas as $persona){
				if($persona->getId()!="1"){
				$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" >'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
				}
			}
			$this->addVar("vendedores",$selectPersona);

			$this->addLayout("ace");
			$this->processTemplate("liquidaciones/crearCedida.html");
		}
		
	}
}
?>