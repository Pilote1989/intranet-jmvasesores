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
	function calculaEdad($fecha = null){
		if($fecha==null){
			$fecha_poliza =  new DateTime(date('Y/m/d',time()));
		}else{
			$fecha_poliza =  new DateTime(date('Y/m/d',strtotime($fecha)));
		}
		$fecha_nac = new DateTime(date('Y/m/d',strtotime($this->getAniversario())));
		$edad = date_diff($fecha_nac,$fecha_poliza);
		if($edad->format('%r')=="-"){
			return "-";
		}else{
			return $edad->format('%Y')." años y ".$edad->format('%m')." meses";
		}
	}
}
?>