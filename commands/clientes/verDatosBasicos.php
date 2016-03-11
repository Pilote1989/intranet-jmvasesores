<?php
class verDatosBasicos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idCliente){
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
			if($this->request->m){
				$this->addBlock("mensaje");	
				$this->addVar("m", "No se puede eliminar a un cliente que tiene polizas");	
			}
			$cliente = Fabrica::getFromDB("Cliente",$this->request->idCliente);
			$this->addVar("idCliente", $this->request->idCliente);
			$this->addVar("nombre", $cliente->getNombre());
			$this->addVar("direccion", $cliente->getDireccion());
			$this->addVar("correo", $cliente->getCorreo());
			$this->addVar("doc", $cliente->getDoc());
			$this->addVar("tipoDoc", $cliente->getTipoDoc());
			$this->addVar("aniversario", $cliente->getAniversario('DATE'));
			if($cliente->getTipoDoc()=="RUC"){
				$this->addBlock("cia");
				$this->addVar("ggeneral", $cliente->getGerente());
				$this->addVar("cumpleGGeneral", $cliente->getFechaGerente('DATE'));
				$this->addVar("encargado", $cliente->getEncargado());
				$this->addVar("cumpleEncargado", $cliente->getFechaEncargado('DATE'));
				
			}
			$this->addVar("correo2", $cliente->getCorreoAlternativo());
			$this->addVar("nombreFicha", "Ficha Cliente");
			$this->addVar("idPoliza", $this->request->idPoliza);
			$this->addVar("distrito", $distritos[$cliente->getDistrito()]);
			$this->addVar("menu", "menuClientes?idCliente=".$this->request->idCliente);
			
			$persona = Fabrica::getFromDB("Persona", $cliente->getIdPersona());
			//var_dump($persona);
			$this->addVar("asesor", $persona->getNombres() . " " . $persona->getApellidoPaterno() . " " . $persona->getApellidoMaterno());
			
			
			
			$polizas = Fabrica::getAllFromDB("Poliza",array("idCliente = '" . $this->request->idCliente . "'", "estado = '1'"));
			$this->addVar("polizas", count($polizas));
			
			//$this->addLayout("admin");
			$this->addBlock("bloqueEditarClientes");
			$this->processTemplate("clientes/verDatosBasicos.html");
		}
	}
}
?>