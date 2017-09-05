<?php
class Ubigeo extends DBObject{
    var $tableName="Ubigeo";
    function departamento(){
        $temp = Fabrica::getAllFromDB("Ubigeo",array("departamento = " . $this->getDepartamento(),"provincia = '00'","distrito = '00'"));
        if(count($temp)>0){
            return $temp[0]->getNombre();
        }
        return "Sin Definir";
	}
	function provincia(){
        $temp = Fabrica::getAllFromDB("Ubigeo",array("departamento = " . $this->getDepartamento(),"provincia = " .  $this->getProvincia(),"distrito = '00'"));
        if(count($temp)>0){
            return $temp[0]->getNombre();
        }
        return "Sin Definir";
	}
}
?>