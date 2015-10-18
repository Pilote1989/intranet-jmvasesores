<?php
class busquedaVehiculos extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		
		$usuario=$this->getUsuario();
		if(!$this->request->pagina){
			$paginaActual=1;
		}else{
			$paginaActual=$this->request->pagina;
			$_SESSION["busquedaVehiculos"]["pagina"]=$this->request->pagina;
		}
		
		if(!$this->request->limite){
			$limite=10;
		}else{
			$limite=$this->request->limite;
			$_SESSION["busquedaVehiculos"]["limite"] = $this->request->limite;
		}


		$minimoDePagina=Paginador::getMinimo($paginaActual,$limite);
		
		$where=array();
		
		if($this->request->placa){
			$_SESSION["busquedaVehiculos"]["placa"]=$this->request->placa;
			$where[]="placa LIKE '%".$this->request->placa."%'";
		}
				
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}

		$vehiculos=array();


		$query="
				SELECT SQL_CALC_FOUND_ROWS
					idVehiculo,placa,modelo,marca,anio
				FROM reporteVehiculos
				".$whereCondition."
				ORDER BY placa
				LIMIT ".$minimoDePagina.", ".$limite."
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
			$this->fc->import("lib.Paginador");
			
			while($row=$result->fetch_assoc()){
				$listaVehiculos[]=$row;
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		$i=0;
		$vehiculos=array();
		foreach($listaVehiculos as $vehiculo){
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);
			$vehiculos[$i]["idVehiculo"] = $vehiculo["idVehiculo"];
			$vehiculos[$i]["placa"] = $vehiculo["placa"];
			$vehiculos[$i]["modelo"] = $vehiculo["modelo"];			
			$vehiculos[$i]["marca"] = $vehiculo["marca"];		
			$vehiculos[$i]["anio"] = $vehiculo["anio"];
			$i++;				
		}
		$tablaPaginas=Paginador::crearHtmlAjax($paginaActual,$num_rows,"?do=vehiculos.busquedaVehiculos".$pPaginador,"divBusquedaVehiculos", $limite);

		$this->addVar("paginas",$tablaPaginas);
		$this->addVar("num_rows",$num_rows);
		
		$this->addLoop("vehiculos",$vehiculos);

		$this->processTemplate("vehiculos/busquedaVehiculos.html");
		
	}
}
?>
