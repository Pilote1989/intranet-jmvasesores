<?php
class Cliente extends DBObject{
	var $tableName="Cliente";
	function getDocumento(){
		return $this->getTipoDoc()." - ".$this->getDoc();
	}
	function obtenerProvincia(){
		$a = Fabrica::getFromDB("Ubigeo",$this->getIdUbigeo());
		$temp = Fabrica::getAllFromDB("Ubigeo",array("departamento = '".$this->obtenerDepartamento()->getDepartamento()."'","provincia = '".$a->getProvincia()."'","distrito = '00'"));
		return $temp[0];
	}
	function obtenerDepartamento(){
		$a = Fabrica::getFromDB("Ubigeo",$this->getIdUbigeo());
		$temp = Fabrica::getAllFromDB("Ubigeo",array("departamento = '".$a->getDepartamento()."'","provincia = '00'","distrito = '00'"));
		return $temp[0];
	}
	function obtenerUbigeo(){
		if($this->getIdUbigeo()!="0"){
			return Fabrica::getFromDB("Ubigeo",$this->getIdUbigeo())->getNombre().", ".$this->obtenerDepartamento()->getNombre();	
		}
		return "Sin Distrito";
	}
}
?>