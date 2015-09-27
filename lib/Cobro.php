<?php
class Cobro extends DBObject{
	var $tableName="Cobro";
	function tipo(){
		
		$query ='SELECT COUNT(*) as a FROM jmvclientes.Endoso WHERE idCobro= "' . $this->getId() . '"';
		//echo $query;
		$query=utf8_decode($query);		
		$link = &$this->fc->getLink();	
		if($countResult=$link->query($query)){
			$row=$countResult->fetch_assoc();
			$resultado=$row['a'];
			if($row['a']!='0'){
				return "END";
			}
		}else{
			printf("Error: %s\n", $dbLink->error);
			return "POL";
		}
		return "POL";
	}
	function cliente(){
			if($this->tipo()=="POL"){
				$poliza = Fabrica::getAllFromDB("Poliza",array("idCobro = '" . $this->getId() . "'"));	
				return Fabrica::getFromDB("Cliente", $poliza[0]->getIdCliente())->getNombre();			
			}elseif($this->tipo()=="END"){
				$endoso = Fabrica::getAllFromDB("Endoso",array("idCobro = '" . $this->getId() . "'"));
				$poliza = Fabrica::getFromDB("Poliza", $endoso[0]->getIdPoliza());
				return Fabrica::getFromDB("Cliente", $poliza->getIdCliente())->getNombre();			
			}	
	}
	function poliza(){
			if($this->tipo()=="POL"){
				$poliza = Fabrica::getAllFromDB("Poliza",array("idCobro = '" . $this->getId() . "'"));	
				return $poliza[0]->getNumeroPoliza();			
			}elseif($this->tipo()=="END"){
				$endoso = Fabrica::getAllFromDB("Endoso",array("idCobro = '" . $this->getId() . "'"));
				$poliza = Fabrica::getFromDB("Poliza", $endoso[0]->getIdPoliza());
				return $poliza->getNumeroPoliza();			
			}	
	}
	function moneda(){
			if($this->tipo()=="POL"){
				$poliza = Fabrica::getAllFromDB("Poliza",array("idCobro = '" . $this->getId() . "'"));	
				return $poliza[0]->getMoneda();			
			}elseif($this->tipo()=="END"){
				$endoso = Fabrica::getAllFromDB("Endoso",array("idCobro = '" . $this->getId() . "'"));
				$poliza = Fabrica::getFromDB("Poliza", $endoso[0]->getIdPoliza());
				return $poliza->getMoneda();			
			}	
	}
	function ramo(){
			if($this->tipo()=="POL"){
				$poliza = Fabrica::getAllFromDB("Poliza",array("idCobro = '" . $this->getId() . "'"));	
				return Fabrica::getFromDB("Ramo", $poliza[0]->getIdRamo())->getSigla();			
			}elseif($this->tipo()=="END"){
				$endoso = Fabrica::getAllFromDB("Endoso",array("idCobro = '" . $this->getId() . "'"));
				$poliza = Fabrica::getFromDB("Poliza", $endoso[0]->getIdPoliza());
				return Fabrica::getFromDB("Ramo", $poliza->getIdRamo())->getSigla();			
			}	
	}
}
?>