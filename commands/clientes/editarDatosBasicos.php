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
		}
		$distritos = array(
			"99" => "Sin Definir",
			"98" => "Provincia",
			"1" => "Lima",
			"2" => "Ancón",
			"3" => "Ate",
			"4" => "Barranco", 
			"5" => "Breña",
			"6" => "Carabayllo",
			"7" => "Chaclacayo",
			"8" => "Chorrillos",
			"40" => "Cieneguilla",
			"7" => "Comas",
			"10" => "El Agustino",
			"28" => "Independencia",
			"11" => "Jesús María",
			"12" => "La Molina",
			"13" => "La Victoria",
			"14" => "Lince",
			"39" => "Los Olivos",
			"15" => "Lurigancho-Chosica",
			"16" => "Lurin",
			"17" => "Magdalena del Mar",
			"21" => "Pueblo Libre",
			"18" => "Miraflores",
			"19" => "Pachacámac",
			"20" => "Pucusana",
			"22" => "Puente Piedra",
			"24" => "Punta Hermosa",
			"23" => "Punta Negra",
			"25" => "Rímac",
			"26" => "San Bartolo",
			"41" => "San Borja",
			"27" => "San Isidro",
			"36" => "San Juan de Lurigancho",
			"29" => "San Juan de Miraflores",
			"30" => "San Luis",
			"31" => "San Martín de Porres",
			"32" => "San Miguel",
			"43" => "Santa Anita",
			"37" => "Santa María del Mar",
			"38" => "Santa Rosa",
			"33" => "Santiago de Surco",
			"34" => "Surquillo",
			"42" => "Villa El Salvador",
			"35" => "Villa María del Triunfo"
		);
		$selectDistritos = "";
		foreach($distritos as $id => $dis){
			if($id == $distrito){
				$selectDistritos .= "<option value='" . $id . "' selected='selected'>" . $dis . "</option>";
			}else{
				$selectDistritos .= "<option value='" . $id . "'>" . $dis . "</option>";
			}
		}
		
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
		$this->addVar("distritos", $selectDistritos);
		$this->addVar("asesores", $selectAsesores);
		$this->addLayout("admin");

		$this->processTemplate("clientes/editarDatosBasicos.html");
	}
}
?>