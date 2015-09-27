<?php
class agregarAsegurado extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		
		
		$tipoDoc = array(
			"DNI" => "DNI - Documento Nacional de Identidad",
			"RUC" => "RUC - Registro Unico de Contribuyente",
			"TEMP" => "TEMP- Temporal",
			"CEX" => "CEX - Carnet de Extranjeria",
		);         
		$selectDoc = "";
		foreach($tipoDoc as $id => $dis){
			if($id == $doc){
				$selectDoc .= "<option value='" . $id . "' selected='selected'>" . $dis . "</option>";
			}else{
				$selectDoc .= "<option value='" . $id . "'>" . $dis . "</option>";
			}
		}
		$this->addVar("tipoDoc", $selectDoc);
		$this->addVar("idPoliza", $this->request->idPoliza);

		$this->processTemplate("polizas/agregarAsegurado.html");
		
	}
}
?>