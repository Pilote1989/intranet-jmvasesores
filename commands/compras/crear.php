<?php
class crear extends sessionCommand{
	function execute(){
		// -> Banner
		$distrito = 99;
		$this->addVar("doFalso", $this->request->do);
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		if($this->request->idCliente==""){
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
				$selectDistritos .= "<option value='" . $id . "'>" . $dis . "</option>";
			}
			$this->addVar("ruc",$this->request->ruc);
			$this->addVar("nombre",$this->request->nombre);
			$this->addEmptyVar("idCliente");
			$this->addBlock("creacionCliente");
			$this->addVar("distritos", $selectDistritos);
		}else{
			$cliente=Fabrica::getFromDB("Cliente",$this->request->idCliente);
			$this->addBlock("completo");
			$this->addVar("idCliente",$this->request->idCliente);
			$this->addBlock("dataCliente");
			$this->addVar("ruc",$cliente->getDoc());
			$this->addVar("nombre",$cliente->getNombre());
		}
		$this->addLayout("ace");
		$this->processTemplate("compras/crear.html");
	}
}
?>