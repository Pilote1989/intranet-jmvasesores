<?php
class Compra extends DBObject{
	var $tableName="Compra";
	function fechaPresentacion(){
	    return substr($this->getFechaPresentacion("DATE"),3);
	}	
	function moneda(){
		if($this->getMoneda()=="Soles"){
			return "Soles (S/.)";
		}elseif($this->getMoneda()=="Dolares"){
			return "Dolares ($)"; 
		}elseif($this->getMoneda()=="Euros"){
			return "Euros (&euro;)";
		}
		return "Error";
	}	
}
?>