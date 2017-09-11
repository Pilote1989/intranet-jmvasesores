<?php
class ver extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idRamo){
			$polizas = Fabrica::getAllFromDB("Poliza", array('idRamo ="'. $this->request->idRamo .'"'));
			$this->addVar("numPolizas", count($polizas));
			$ramo = Fabrica::getFromDB("Ramo",$this->request->idRamo);
			$this->addVar("idRamo", $this->request->idRamo);
			$this->addVar("nombre", $ramo->getNombre());
			$this->addVar("sigla", $ramo->getSigla());
			$this->addBlock("admin");
			$this->addLayout("ace");
			$this->addBlock("bloqueEditarRamos");
			$this->processTemplate("ramos/ver.html");
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