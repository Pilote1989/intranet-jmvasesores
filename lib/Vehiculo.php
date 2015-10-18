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
	function textoTipo(){
		switch($this->getTipo()){
			case 1: 
				return "Auto";
				break;	
			case 2: 
				return "Camioneta Pick Up";
				break;	
			case 3: 
				return "Camioneta Panel";
				break;	
			case 4: 
				return "Camioneta Station Wagon";
				break;	
			case 5: 
				return "Camioneta Rural 4x4";
				break;	
			case 6: 
				return "Camioneta Rural 4x2";
				break;	
			case 7: 
				return "Carreta";
				break;	
			case 8: 
				return "Tracto";
				break;	
			case 9: 
				return "Camion";
				break;	
			case 10: 
				return "Moto";
				break;	
		}						
		return "error";
	}
}
?>