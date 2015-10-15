<?php
class buscar extends sessionCommand{
	function execute(){

		$fc=FrontController::instance();
		$this->fc->import("lib.Paginador");
		
		$usuario=$this->getUsuario();
		
		
		if($this->request->placa){
			$where=" WHERE v.placa LIKE '%".$this->request->placa."%' ";
		}	
				
	
		//echo $this->request->idPoliza;
		$vehiculoEnPolizas = Fabrica::getAllFromDB("VehiculoEnPoliza",array("idPoliza = '". $this->request->idPoliza."'"));
		
		$veh = array();
		$j=0;
		foreach($vehiculoEnPolizas as $vehiculoEnPoliza){
			$veh[$j++] = $vehiculoEnPoliza->getIdVehiculo();
		}
		//print_r($cli);
		
		$vehiculos=array();

		$query="
			SELECT SQL_CALC_FOUND_ROWS
				*
			FROM
				( Vehiculo v LEFT JOIN Modelo mol
				ON 
				mol.idModelo = v.idModelo ) LEFT JOIN Marca m
				ON m.idMarca = mol.idMarca
			". $where ."
			ORDER BY placa ASC
		"; 
		
		$query=utf8_decode($query);		
		$link=&$this->fc->getLink();
		
		if($result=$link->query($query)){
			$countQuery="SELECT FOUND_ROWS() as total";
		
			if($countResult=$link->query($countQuery)){
				$row=$countResult->fetch_assoc();
				$num_rows=$row['total'];
			}else{
				printf("Error: %s\n", $dbLink->error);
				return null;
			}
			
			$listaVehiculos=array();
			
			while($row=$result->fetch_assoc()){
				$listaVehiculos[]=$row;
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		$i=0;
		
		foreach($listaVehiculos as $vehiculo){
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);
			$vehiculos[$i]["placa"] = $vehiculo["placa"];
			$vehiculos[$i]["marca"] = "marca";
			$vehiculos[$i]["modelo"] = "modelo";
			if(in_array($vehiculo["idVehiculo"],$veh)){
				$vehiculos[$i]["estado"] = '<button class="btn btn-minier btn-inverse disabled">En Poliza</button>';
			}else{
				$vehiculos[$i]["estado"] = '<button class="btn btn-minier btn-success agregaAsegurado" x-pol="'.$this->request->idPoliza.'" x-link="'.$vehiculo["idVehiculo"].'">Agregar<i class="icon-arrow-right icon-on-right"></i></button>';
			}						
			$i++;				
		}
		
		$this->addLoop("vehiculos",$vehiculos);
		$this->addVar("placa",$this->request->placa);
		$this->addVar("idPoliza",$this->request->idPoliza);

		$this->processTemplate("vehiculos/buscar.html");
		
	}
}
?>