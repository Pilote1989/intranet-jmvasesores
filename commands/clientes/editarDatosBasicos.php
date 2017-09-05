<?php
class editarDatosBasicos extends sessionCommand{
	function execute(){
		// -> Banner
		$distrito = 99;
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idCliente){
			$this->addBlock("editando");
			$cliente=Fabrica::getFromDB("Cliente",$this->request->idCliente);
			//$this->addVar("departamento",$cliente->obtenerDepartamento());
			//$this->addVar("provincia",$cliente->obtenerProvincia());
			//echo "D : " . $cliente->obtenerDepartamento()->getNombre() . " - ";
			//echo "P : " . $cliente->obtenerProvincia()->getNombre();
			if($cliente->getIdUbigeo()!="0"){
				$this->addBlock("setSelect");
				$this->addVar("idProvincia",$cliente->obtenerProvincia()->getId());
				$this->addVar("idDepartamento",$cliente->obtenerDepartamento()->getId());
			}
			$this->addVar("idDistrito",$cliente->getIdUbigeo());
			$this->addBlock("bloqueEditarClientes");
			$this->addVar("editar","Editar Datos Básicos");
			$this->addBlock("idCliente");
			$this->addVar("idCliente",$cliente->getId());
			$this->addVar("nombre",$cliente->getNombre());
			$this->addVar("doc",$cliente->getDoc());
			$this->addVar("direccion",$cliente->getDireccion());
			$this->addVar("correo",$cliente->getCorreo());
			$this->addVar("correo2", $cliente->getCorreoAlternativo());
			$doc = $cliente->getTipoDoc();
			$distrito = $cliente->getDistrito();
			$asesorCliente = $cliente->getIdPersona();
			if($cliente->getAniversario()!='0000-00-00'){
				$this->addVar("nac", $cliente->getAniversario('DATE'));
			}else{
				$this->addEmptyVar("nac");
			}
			$this->addVar("ggeneral", $cliente->getGerente());
			if($cliente->getFechaGerente()!='0000-00-00'){
				$this->addVar("nacGG", $cliente->getFechaGerente('DATE'));
			}else{
				$this->addEmptyVar("nacGG");
			}
			$this->addVar("encargado", $cliente->getEncargado());
			if($cliente->getFechaEncargado()!='0000-00-00'){
				$this->addVar("nacEncargado", $cliente->getFechaEncargado('DATE'));
			}else{
				$this->addEmptyVar("nacEncargado");
			}
		}else{
			$this->addBlock("creando");
			$this->addBlock("bloqueEditarClientes");
			$this->addVar("editar","Crear Nuevo Cliente");
			$this->addVar("idCliente","nan");
			$this->addEmptyVar("nombre");
			$this->addEmptyVar("doc");
			$this->addEmptyVar("direccion");
			$this->addEmptyVar("correo");
			$this->addEmptyVar("correo2");
			$this->addEmptyVar("nac");
			$this->addEmptyVar("ggeneral");
			$this->addEmptyVar("nacGG");
			$this->addEmptyVar("encargado");
			$this->addEmptyVar("nacEncargado");
		}
		$departamentos = Fabrica::getAllFromDB("Ubigeo",array("provincia = '00'","distrito = '00'"));
		$selectDepartamentos = "<option></option>";
		foreach($departamentos as $departamento){
			$selectDepartamentos .= "<option value='" . $departamento->getId() . "'>" . $departamento->getNombre() . "</option>";
		}
		$tipoDoc = array(
			"DNI" => "DNI - Documento Nacional de Identidad",
			"RUC" => "RUC - Registro Unico de Contribuyente",
			"TEMP" => "TEMP- Temporal",
			"CEX" => "CEX - Carnet de Extranjeria",
		);         
		$selectDoc = "";
		$empresa = false;
		foreach($tipoDoc as $id => $dis){
			if($id == $doc){
				$selectDoc .= "<option value='" . $id . "' selected='selected'>" . $dis . "</option>";
				if($id=="RUC"){
					$empresa=true;
				}
			}else{
				$selectDoc .= "<option value='" . $id . "'>" . $dis . "</option>";
			}
		}
		if(!$empresa){
			$this->addBlock("noEmpresa");
		}
		$asesores = Fabrica::getAllFromDB("Persona",array());
		$selectAsesores = "";
		foreach($asesores as $asesor){
			if($asesor->getId() == $asesorCliente){
				$selectAsesores .= "<option value='" . $asesor->getId() . "' selected='selected'>" . $asesor->getNombres() . " " . $asesor->getApellidoPaterno()  . " " . $asesor->getApellidoMaterno()  . "</option>";
			}else{
				$selectAsesores .= "<option value='" . $asesor->getId() . "'>" . $asesor->getNombres() . " " . $asesor->getApellidoPaterno()  . " " . $asesor->getApellidoMaterno()  . "</option>";
			}
		}
		$this->addVar("tipoDocVal", $doc);
		$this->addVar("tipoDoc", $selectDoc);
		$this->addVar("departamentos", $selectDepartamentos);
		$this->addVar("asesores", $selectAsesores);
		$this->addLayout("ace");
		$this->processTemplate("clientes/editarDatosBasicos.html");
	}
}
?>