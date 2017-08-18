<?php
class Cliente extends DBObject{
	var $tableName="Cliente";
	function getDocumento(){
		return $this->getTipoDoc()." - ".$this->getDoc();
	}
}
?>