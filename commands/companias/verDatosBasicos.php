<?php
class verDatosBasicos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idCompania){
			$polizas = Fabrica::getAllFromDB("Poliza", array('idCompania ="'. $this->request->idCompania .'"'));
			$this->addVar("numPolizas", count($polizas));
			$compania = Fabrica::getFromDB("Compania",$this->request->idCompania);
			$this->addVar("idCompania", $this->request->idCompania);
			$this->addVar("nombre", $compania->getNombre());
			$this->addVar("sigla", $compania->getSigla());
			$i = 1;
			$ramos = Fabrica::getAllFromDB("Comision", array("idCompania = " . $this->request->idCompania));
			foreach($ramos as $ramo){
				$comision[$i]["idComision"] = $ramo->getId();
				$ramoActual = Fabrica::getFromDB("Ramo",$ramo->getIdRamo());				
				$comision[$i]["ramo"] = $ramoActual->getNombre();
				$comision[$i]["valor"] = $ramo->getComision();
				//echo $cupon->getId();
				$i++;				
			}
			//print_r($comision);
			$comision = $this->subval_sort($comision,'ramo');
			//print_r($comision);
			$this->addLoop("comision", $comision);
			$this->addLayout("ace");
			$this->addBlock("bloqueEditarCompanias");
			$this->processTemplate("companias/verDatosBasicos.html");
		}
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