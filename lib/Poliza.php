<?php
class Poliza extends DBObject{
	var $tableName="Poliza";
	function renovaciones(){
		$vigencias = Fabrica::getAllFromDB("Poliza", array("numeroPoliza = '" . $this->getNumeroPoliza() . "'","estado = '1'"), "inicioVigencia DESC");			
		return count($vigencias) - 1;
	}
	function estado(){
		//revisar si esta anulada!!
		$todays_date = date("Y-m-d"); 
		$today = strtotime($todays_date);
		$inicio = $this->getInicioVigencia();
		$fin = $this->getFinVigencia();
		if($this->getAnulada()==1){
			return "Anulada";
		}
		if($today<strtotime($fin) && $today>=strtotime($inicio)){
			return "Vigente";
		}elseif($today>strtotime($fin)){
			return "Vencida";
		}elseif($today<strtotime($fin)){
			return "Por iniciar";
		}else{
			return "No Encontrada";
		}
	}
	function estadoLabel(){
		//revisar si esta anulada!!
		$todays_date = date("Y-m-d"); 
		$today = strtotime($todays_date);
		$inicio = $this->getInicioVigencia();
		$fin = $this->getFinVigencia();
		if($this->getAnulada()==1){
			return '<span class="label label-danger">Anulada</span>';
		}
		if($today<=strtotime($fin) && $today>=strtotime($inicio)){
			return '<span class="label label-success">Vigente</span>';
		}elseif($today>strtotime($fin)){
			return '<span class="label label-warning">Vencida</span>';
		}elseif($today<strtotime($fin)){
			return '<span class="label label-info">Por Iniciar</span>';
		}else{
			return '<span class="label label-danger">No Encontrada</span>';
		}
	}
	function estadoColor(){
		//revisar si esta anulada!!
		$todays_date = date("Y-m-d"); 
		$today = strtotime($todays_date);
		$inicio = $this->getInicioVigencia();
		$fin = $this->getFinVigencia();
		if($this->getAnulada()==1){
			return "red";
		}
		if($today<strtotime($fin) && $today>=strtotime($inicio)){
			return 'green';
		}elseif($today>strtotime($fin)){
			return 'yellow';
		}elseif($today<strtotime($fin)){
			return 'blue';
		}else{
			return 'red';
		}
	}
	function inicioGrupo($a){
		$query ='SELECT min(inicioVigencia) AS a FROM jmvclientes.Poliza WHERE numeroPoliza="' . $a. '" AND estado = "1" AND anulada = "0"';
		$query=utf8_decode($query);		
		$link = &$this->fc->getLink();	
			if($countResult=$link->query($query)){
				$row=$countResult->fetch_assoc();
				$resultado=$row['a'];
				return $resultado;
			}else{
				printf("Error: %s\n", $dbLink->error);
				return null;
			}
	}
	function finGrupo($a){
		$query ='SELECT max(finVigencia) AS a FROM jmvclientes.Poliza WHERE numeroPoliza="' . $a. '" AND estado = "1" AND anulada = "0"';
		$query=utf8_decode($query);		
		$link = &$this->fc->getLink();	
			if($countResult=$link->query($query)){
				$row=$countResult->fetch_assoc();
				$resultado=$row['a'];
				return $resultado;
			}else{
				printf("Error: %s\n", $dbLink->error);
				return null;
			}
	}
	function prima(){
		$query ='SELECT SUM(monto) AS a FROM jmvclientes.Cupon WHERE idPoliza="' . $this->getId(). '" group by idPoliza';
		//echo $query;
		$query=utf8_decode($query);		
		$link = &$this->fc->getLink();	
			if($countResult=$link->query($query)){
				$row=$countResult->fetch_assoc();
				$resultado=$row['a'];
				return $resultado;
			}else{
				printf("Error: %s\n", $dbLink->error);
				return null;
			}
	}
	function primaNetaTotal(){
		//primero Obtengo la prima neta de la poliza
		$query ='SELECT SUM(primaNeta) AS a FROM jmvclientes.Cobro WHERE idCobro="' . $this->getIdCobro() . '"';
		$query=utf8_decode($query);		
		$link = &$this->fc->getLink();
		//echo $query . "<br/>";	
		if($countResult=$link->query($query)){
			$row=$countResult->fetch_assoc();
			$resultado=$row['a'];
		}else{
			printf("Error: %s\n", $dbLink->error);
			return null;
		}//luego obtengo la prima neta de los endosos
		$query ='SELECT SUM(primaNeta) AS a 
					FROM jmvclientes.Cobro, jmvclientes.Endoso 
					WHERE jmvclientes.Endoso.idPoliza = "' . $this->getId() . '"
					AND jmvclientes.Endoso.IdCobro = jmvclientes.Cobro.idCobro';
		$query=utf8_decode($query);		
		//echo $query;
		$link = &$this->fc->getLink();	
		if($countResult=$link->query($query)){
			$row=$countResult->fetch_assoc();
			$resultado += $row['a'];
			return $resultado;
		}else{
			printf("Error: %s\n", $dbLink->error);
			return null;
		}		
		//echo $query;
	}
	function matriz(){
		$query ='SELECT idPoliza AS a FROM jmvclientes.Poliza WHERE numeroPoliza="' . $this->getNumeroPoliza() . '" AND tipo = "POL"';
		//echo $query;
		$query=utf8_decode($query);		
		$link = &$this->fc->getLink();	
			if($countResult=$link->query($query)){
				$row=$countResult->fetch_assoc();
				$resultado=$row['a'];
				return $resultado;
			}else{
				printf("Error: %s\n", $dbLink->error);
				return null;
			}
	}
	function getRamo(){
		$ramo = Fabrica::getFromDB("Ramo", $this->getIdRamo());			
		return $ramo->getNombre();
	}
	function getCliente(){
		$cliente = Fabrica::getFromDB("Cliente", $this->getIdCliente());			
		return $cliente->getNombre();
	}
	function getCiaNumero(){
		$compania = Fabrica::getFromDB("Compania", $this->getIdCompania());
		return $compania->getSigla()." - ".$this->getNumeroPoliza();
	}
}
?>