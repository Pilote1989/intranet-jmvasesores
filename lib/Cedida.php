<?php
class Cedida extends DBObject{
	var $tableName="Cedida";
	function vendedor(){
		$vendedor = Fabrica::getAllFromDB("Persona",array("idPersona = '" . $this->getIdPersona() . "'"));
		return $vendedor[0]->getNombres() . " " . $vendedor[0]->getApellidoPaterno() .  " " . $vendedor[0]->getApellidoMaterno(); 			
	}
}
?>