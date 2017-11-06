<?php
class crearGuardarEnPoliza extends sessionCommand{
	function execute(){
		// -> Banner
		$distrito = 99;
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$departamentos = Fabrica::getAllFromDB("Ubigeo",array("provincia = '00'","distrito = '00'"));
		$selectDepartamentos = "<option></option>";
		foreach($departamentos as $departamento){
			$selectDepartamentos .= "<option value='" . $departamento->getId() . "'>" . $departamento->getNombre() . "</option>";
		}
		$this->addVar("departamentos", $selectDepartamentos);
		$tipoDoc = array(
			"SD" => "SD - Sin Documento",
			"DNI" => "DNI - Documento Nacional de Identidad",
			"RUC" => "RUC - Registro Unico de Contribuyente",
			"TEMP" => "TEMP- Temporal",
			"CEX" => "CEX - Carnet de Extranjeria",
		);         
		$selectDoc = "";
		foreach($tipoDoc as $id => $dis){
			if($id == "DNI"){
				if($this->request->nodoc != "1"){
					$selectDoc .= "<option value='" . $id . "' selected='selected'>" . $dis . "</option>";
				}else{
					$selectDoc .= "<option value='" . $id . "'>" . $dis . "</option>";
				}
			}else{
				$selectDoc .= "<option value='" . $id . "'>" . $dis . "</option>";
			}
		}
		if($this->request->nodoc == "1"){
			$this->addBlock("noDoc");
		}

		$this->addVar("tipoDoc", $selectDoc);
		$this->addVar("idPoliza", $this->request->idPoliza);
		$this->addVar("distritos", $selectDistritos);

		$this->processTemplate("clientes/crearGuardarEnPoliza.html");
	}
}
?>