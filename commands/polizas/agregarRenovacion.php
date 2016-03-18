<?php
class agregarRenovacion extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		if($this->request->idPoliza){
			$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$this->addBlock("bloqueEditarPolizas");
			$this->addBlock("idPoliza");
			$this->addVar("numeroPoliza",$poliza->getNumeroPoliza());
			//falta procesar fechas
			$this->addVar("fechaInicio",date("d/m/Y",strtotime($poliza->finGrupo($poliza->getNumeroPoliza()))));
			$this->addEmptyVar("fechaFin");
			//fin
			$this->addVar("observaciones",$poliza->getObservaciones());
			$this->addVar("idPoliza",$poliza->getId());
			$this->addVar("moneda",$poliza->getMoneda());
			$cliente = Fabrica::getFromDB("Cliente", $poliza->getIdCliente());
			$this->addVar("cliente", $cliente->getNombre());
			$ramo = Fabrica::getFromDB("Ramo", $poliza->getIdRamo());
			$this->addVar("idRamo", $ramo->getNombre());
			$compania = Fabrica::getFromDB("Compania", $poliza->getIdCompania());
			$this->addVar("idCompania", $compania->getNombre());
			$this->addVar("idPersona",$cliente->getIdPersona());

			$personas=Fabrica::getAllFromDB("Persona",array(),"nombres ASC");	
			$selectCompania = '<option value=""></option>';
			$this->addEmptyVar("documento");
			$this->addEmptyVar("cobranza");
			$this->addEmptyVar("renovacion");
			$this->addEmptyVar("prima");
			$this->addEmptyVar("derecho");
			$this->addEmptyVar("igv");
			$this->addEmptyVar("total");
			$this->addEmptyVar("comision");
			$this->addVar("comisionVendedorP",number_format(0,2,'.',''));	
			$this->addVar("comisionVendedor",number_format(0,2,'.',''));
			$this->addEmptyVar("intereses");
			foreach($personas as $persona){
				$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" x-com='.$persona->getComision().'>'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
			}
			$this->addVar("personas",$selectPersona);
			$this->addBlock("polizaPDF");
		}else{
			
			
		}
		$this->addLayout("ace");
		$this->processTemplate("polizas/agregarRenovacion.html");
	}
}
?>