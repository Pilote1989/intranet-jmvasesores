<?php
class busquedaLiquidacionesCedidas extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		
		$usuario=$this->getUsuario();
		if(!$this->request->pagina){
			$paginaActual=1;
		}else{
			$paginaActual=$this->request->pagina;
			$_SESSION["busquedaLiquidacionesC"]["pagina"]=$this->request->pagina;
		}
		
		if(!$this->request->limite){
			$limite=10;
		}else{
			$limite=$this->request->limite;
			$_SESSION["busquedaLiquidacionesC"]["limite"] = $this->request->limite;
		}

		$minimoDePagina=Paginador::getMinimo($paginaActual,$limite);
		
		$where=array();
				
		if($this->request->idPersona){
			$_SESSION["busquedaLiquidacionesC"]["idPersona"] = $this->request->idPersona;
			$where[]="per.idPersona = '".$this->request->idPersona."'";
		}
		
		
		if($this->request->factura){
			$_SESSION["busquedaLiquidaciones"]["contratante"]=$this->request->factura;
			$where[]="c.factura LIKE '%".$this->request->factura."%'";
		}
		
		$pPaginador = "";
		
		$where[]="per.idPersona = c.idPersona";
		
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}

		$liquidaciones=array();
		$query="
				SELECT SQL_CALC_FOUND_ROWS
					DISTINCT *
				FROM Cedida c, Persona per
				".$whereCondition."
				LIMIT ".$minimoDePagina.", ".$limite."
			";
		//echo '<div>'.$query.'</div>'; 
		
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
			
			$listaCedidas=array();
			$this->fc->import("lib.Paginador");
			
			while($row=$result->fetch_assoc()){
				$listaCedidas[]=$row;
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		$i=0;
		
		$cedidas=array();		
		foreach($listaCedidas as $cedida){
			$cedidas[$i]["idLista"] = ++$i;
			$cedidas[$i]["idCedida"] = $cedida["idCedida"];
			$cedidas[$i]["factura"] = $cedida["factura"];
			$cedidas[$i]["persona"] = $cedida["nombres"] . " " . $cedida["apellidoPaterno"] .  " " . $cedida["apellidoMaterno"];
			$cedidas[$i]["fechaFactura"] = date("d/m/Y",strtotime($cedida["fechaFactura"]));
		}
		$tablaPaginas=Paginador::crearHtmlAjax($paginaActual,$num_rows,"?do=liquidaciones.busquedaLiquidacionesCedidas".$pPaginador,"divBusquedaLiquidaciones", $limite);

		$this->addVar("paginas",$tablaPaginas);
		$this->addVar("num_rows",$num_rows);
		//print_r();
		$this->addLoop("cedidas",$cedidas);

		$this->processTemplate("liquidaciones/busquedaLiquidacionesCedidas.html");
		
	}
}
?>
