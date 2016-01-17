<?php
class Mes extends DBObject{
	var $tableName="Mes";
	
	function estadoColor(){
		if($this->getEstado()==1)
			return "red";
		return "green";
	}
	function codBoot(){
		if($this->getEstado()==1)
			return "danger";
		return "success";
	}
}
?>