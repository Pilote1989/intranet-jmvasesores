<?php
class crearPoliza extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
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
			$clientes=Fabrica::getAllFromDB("Cliente",array(),"nombre ASC");	
			$selectCliente = '';
			foreach($clientes as $cliente){
				$selectCliente=$selectCliente.'<option value="'.$cliente->getId().'" x-asesor="'.$cliente->getIdPersona().'" >'.$cliente->getNombre().'</option>';
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
					$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" x-comision="'.$persona->getComision().'">'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';					
				}else{
					$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" selected="selected" x-comision="'.$persona->getComision().'">'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
				}
			}
			$this->addVar("personas",$selectPersona);
			
			
		$this->addLayout("ace");
		$this->processTemplate("polizas/crearPoliza.html");
	}
}
?>