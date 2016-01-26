<?php
class editarDatosBasicos extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idPoliza){
			$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$cobro = Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
			$this->addBlock("bloqueEditarPolizas");
			$this->addVar("editar","Editar Datos Básicos");
			$this->addBlock("idPoliza");
			$this->addVar("numeroPoliza",$poliza->getNumeroPoliza());
			$this->addVar("cobro",$poliza->getIdCobro());
			$this->addVar("fechaInicio",$poliza->getInicioVigencia("DATE"));
			$this->addVar("fechaFin",$poliza->getFinVigencia("DATE"));
			$this->addVar("observaciones",$poliza->getObservaciones());
			$this->addVar("idPoliza",$poliza->getId());
			$this->addVar("moneda",$cobro->getMoneda());
			//datos de facturacion
			
			$this->addVar("comision", $cobro->getComision());
			if($cobro->getIdLiquidacion() == ""){
				$this->addEmptyVar("readonly");
			}else{
				$this->addVar("readonly","readonly");	
				$this->addBlock("bloqueado");				
			}
			
			if($cobro->getIdCedida() == ""){
				$this->addEmptyVar("readonlyVendedor");
			}else{
				$this->addVar("readonlyVendedor","readonly");	
				$this->addBlock("bloqueadoVendedor");				
			}
			
			$this->addVar("primaNeta",number_format($cobro->getPrimaNeta(),2,'.',''));
			$this->addVar("derecho",number_format($cobro->getDerechoEmision(),2,'.',''));
			$this->addVar("primaComercial",number_format($cobro->getPrimaComercial(),2,'.',''));
			$this->addVar("intereses",number_format($cobro->getIntereses(),2,'.',''));
			$this->addVar("igv",number_format($cobro->getIgv(),2,'.',''));
			$this->addVar("total",number_format($cobro->getTotalFactura(),2,'.',''));
			//datos de la comision
			$this->addVar("comisionP",number_format($cobro->getComisionP(),2,'.',''));
			$this->addVar("comision",number_format($cobro->getComision(),2,'.',''));
			
			$this->addVar("comisionVendedorP",number_format($cobro->getComisionCedidaP(),2,'.',''));	
			$this->addVar("comisionVendedor",number_format($cobro->getComisionCedida(),2,'.',''));
			
			$this->addVar("documento",$cobro->getDocumento());
			$this->addVar("cobranza",$cobro->getAvisoDeCobranza());
			$this->addVar("renovacion",$poliza->getRenovacion());
			$this->addVar("nuevoCliente","1");
			if($poliza->getRecordatorio()==1)
				$this->addVar("recorda", " checked ");
			else
				$this->addEmptyVar("recorda");
			$this->addVar("cliente",$poliza->getIdCliente());
			$clientes=Fabrica::getAllFromDB("Cliente", array("estado = '1'"),"nombre ASC");	
			$selectCliente = '<option value=""></option>';
			foreach($clientes as $cliente){
				if($poliza->getIdCliente()!=$cliente->getId()){
					$selectCliente=$selectCliente.'\n<option value="'.$cliente->getId().'" x-asesor="'.$cliente->getIdPersona().'">'.$cliente->getNombre().'</option>';
				}else{
					$selectCliente=$selectCliente.'\n<option value="'.$cliente->getId().'" selected="selected" x-asesor="'.$cliente->getIdPersona().'">'.$cliente->getNombre().'</option>';
				}
				
			}
			$this->addVar("clientes",$selectCliente);	
			$this->addVar("idRamo",$poliza->getIdRamo());
			$ramos=Fabrica::getAllFromDB("Ramo",array(),"nombre ASC");	
			$selectRamo = '<option value=""></option>';
			foreach($ramos as $ramo){
				$selectRamo=$selectRamo.'\n<option value="'.$ramo->getId().'" >'.$ramo->getNombre().'</option>';
			}
			$this->addVar("ramos",$selectRamo);		
			$this->addVar("idCompania",$poliza->getIdCompania());
			$companias=Fabrica::getAllFromDB("Compania",array(),"nombre ASC");	
			$selectCompania = '<option value=""></option>';
			foreach($companias as $compania){
				$selectCompania=$selectCompania.'\n<option value="'.$compania->getId().'" >'.$compania->getNombre().'</option>';
			}
			$this->addVar("companias",$selectCompania);
			
			
			$this->addBlock("revisaAviso");
					
			$this->addVar("idPersona",$poliza->getIdPersona());
			$personas=Fabrica::getAllFromDB("Persona",array(),"nombres ASC");	
			$selectCompania = '<option value=""></option>';
			foreach($personas as $persona){
				if($cobro->getIdPersona()!=$persona->getId()){
					$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" x-com='.$persona->getComision().'>'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
				}else{
					$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" selected="selected" x-com='.$persona->getComision().'>'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
					
				}
			}
			$this->addVar("personas",$selectPersona);
			//$this->addBlock("polizaPDF");
			if($poliza->getPdf()==""){
				$this->addBlock("polizaPDFn");
			}else{
				$this->addBlock("polizaPDF");
				$this->addVar("pdf",$poliza->getPdf());	
				
			}
		}else{
			$this->addBlock("numeroPoliza");
			$this->addBlock("polizaPDFn");
			$this->addBlock("bloqueEditarPolizas");
			$this->addEmptyVar("cliente");
			$this->addVar("editar","Crear Nueva Poliza");
			$this->addEmptyVar("nuevoCliente");
			$this->addEmptyVar("idPoliza");
			$this->addEmptyVar("idRamo");
			$this->addEmptyVar("idCompania");
			$this->addEmptyVar("recorda");
			$this->addEmptyVar("idCliente");
			$this->addEmptyVar("numeroPoliza");
			$this->addEmptyVar("fechaInicio");
			$this->addEmptyVar("moneda");
			$this->addEmptyVar("fechaFin");
			

			$this->addVar("primaNeta",number_format(0,2,'.',''));
			$this->addVar("derecho",number_format(0,2,'.',''));
			$this->addVar("primaComercial",number_format(0,2,'.',''));
			$this->addVar("intereses",number_format(0,2,'.',''));
			$this->addVar("igv",number_format(0,2,'.',''));
			$this->addVar("total",number_format(0,2,'.',''));	
			$this->addVar("comisionP",number_format(0,2,'.',''));	
			$this->addVar("comision",number_format(0,2,'.',''));	
			$this->addVar("comisionVendedorP",number_format(0,2,'.',''));	
			$this->addVar("comisionVendedor",number_format(0,2,'.',''));	
			
			
	
			$this->addEmptyVar("observaciones");
			$this->addEmptyVar("idPersona");
			$this->addEmptyVar("documento");
			$this->addEmptyVar("cobranza");
			$this->addEmptyVar("renovacion");
			$clientes=Fabrica::getAllFromDB("Cliente", array("estado = '1'"),"nombre ASC");	
			$selectCliente = '<option value=""></option>';
			foreach($clientes as $cliente){
				$selectCliente=$selectCliente.'<option value="'.$cliente->getId().'" x-asesor="'.$cliente->getIdPersona().'">'.$cliente->getNombre().'</option>';
			}
			$this->addVar("clientes",$selectCliente);
			$this->addBlock("bloqueEditarCliente");
			$companias=Fabrica::getAllFromDB("Compania",array(),"nombre ASC");	
			$selectCompania = '<option value=""></option>';
			foreach($companias as $compania){
				$selectCompania=$selectCompania.'\n<option value="'.$compania->getId().'" >'.$compania->getNombre().'</option>';
			}
			$this->addVar("companias",$selectCompania);
			$ramos=Fabrica::getAllFromDB("Ramo",array(),"nombre ASC");	
			$selectRamo = '<option value=""></option>';
			foreach($ramos as $ramo){
				$selectRamo=$selectRamo.'\n<option value="'.$ramo->getId().'" >'.$ramo->getNombre().'</option>';
			}
			$this->addVar("ramos",$selectRamo);
			$this->addBlock("bloqueEditarRamo");

			$this->addEmptyVar("idPersona");
			$personas=Fabrica::getAllFromDB("Persona",array(),"nombres ASC");	
			$selectCompania = '<option value=""></option>';
			foreach($personas as $persona){
				if($persona->getId() != "1"){
					$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" x-com='.$persona->getComision().'>'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';					
				}else{
					$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" selected="selected" x-com='.$persona->getComision().'>'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
				}
			}
			$this->addVar("personas",$selectPersona);
			
			
		}
		
		$this->addLayout("admin");

		//if($this->request->idPoliza){
		//	$this->addLayout("ficha");
		//	$this->addVar("nombreFicha", "Ficha Poliza");
		//	$this->addVar("menu", "menuPolizas?idPoliza=".$this->request->idPoliza);
		//}

		$this->processTemplate("polizas/editarDatosBasicos.html");
	}
}
?>