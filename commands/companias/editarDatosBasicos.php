<?php
class editarDatosBasicos extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idCompania){
			$compania=Fabrica::getFromDB("Compania",$this->request->idCompania);
			$this->addBlock("bloqueEditarCompanias");
			$this->addVar("editar","Editar Datos Básicos");
			$this->addBlock("idCompania");
			$this->addVar("idCompania",$compania->getId());
			$this->addVar("nombre",$compania->getNombre());
			$this->addVar("direccion",$compania->getDireccion());
			$this->addVar("correo",$compania->getCorreo());
			$this->addVar("correo2", $compania->getCorreoAlternativo());
			$ramos = Fabrica::getAllFromDB("Ramo", array());
			foreach($ramos as $ramo){				
				$listaRamos[$i]["idRamo"] = $ramo->getId();
				$listaRamos[$i]["nombre"] = $ramo->getNombre();
				//el valor es lo unico diferente
				
				$comisionTemp = Fabrica::getAllFromDB("Comision", 
					array(
						"idCompania = " . $this->request->idCompania,
						"idRamo = " . $ramo->getId(),
					)
				);	
				$listaRamos[$i]["valor"] = $comisionTemp[0]->getComision();
				//echo $cupon->getId();
				$i++;				
			}
			$listaRamos = $this->subval_sort($listaRamos,'nombre');
			$this->addLoop("listaRamos", $listaRamos);
		}else{
			$this->addBlock("bloqueEditarCompanias");
			$this->addVar("editar","Crear Nueva Compañia");
			$this->addEmptyVar("idCompania");
			$this->addEmptyVar("nombre");
			$this->addEmptyVar("direccion");
			$this->addEmptyVar("correo");
			$this->addEmptyVar("correo2");
			$ramos = Fabrica::getAllFromDB("Ramo", array());
			foreach($ramos as $ramo){				
				$listaRamos[$i]["idRamo"] = $ramo->getId();
				$listaRamos[$i]["nombre"] = $ramo->getNombre();
				$listaRamos[$i]["valor"] = "0";
				//echo $cupon->getId();
				$i++;				
			}
			//print_r($comision);
			$listaRamos = $this->subval_sort($listaRamos,'nombre');
			$this->addLoop("listaRamos", $listaRamos);
			
			
		}
		$this->addLayout("ace");
		$this->processTemplate("companias/editarDatosBasicos.html");
	}
	function subval_sort($a,$subkey) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
		asort($b);
		foreach($b as $key=>$val) {
			$c[] = $a[$key];
		}
		return $c;
	}
}
?>