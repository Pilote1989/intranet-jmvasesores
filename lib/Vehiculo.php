<?php
class Vehiculo extends DBObject{
	var $tableName="Vehiculo";
	function textoMarca(){
        if($this->getIdModelo() != ""){
            $modelo = Fabrica::getFromDB("Modelo",$this->getIdModelo());
        	$marca = Fabrica::getFromDB("Marca",$modelo->getIdMarca());
    		return $marca->getMarca();
        }else{
            return "Sin definir";
        }		
	}
	
	function textoModelo(){
        if($this->getIdModelo() != ""){
            $modelo = Fabrica::getFromDB("Modelo",$this->getIdModelo());
    	    return $modelo->getModelo();
        }else{
            return "Sin definir";
        }
	}
	function idMarca(){
        if($this->getIdModelo() != ""){
            $modelo = Fabrica::getFromDB("Modelo",$this->getIdModelo());
        	$marca = Fabrica::getFromDB("Marca",$modelo->getIdMarca());
            return $marca->getId();
        }else{
            return 0;
        }
	}
}
?>