<?php
class busquedaRamos extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		
		$usuario=$this->getUsuario();
		if(!$this->request->pagina){
			$paginaActual=1;
		}else{
			$paginaActual=$this->request->pagina;
			$_SESSION["busquedaRamos"]["pagina"]=$this->request->pagina;
		}
		
		if(!$this->request->limite){
			$limite=10;
		}else{
			$limite=$this->request->limite;
			$_SESSION["busquedaRamos"]["limite"] = $this->request->limite;
		}


		$minimoDePagina=Paginador::getMinimo($paginaActual,$limite);
		
		$where=array();
		

		$_SESSION["busquedaRamos"]["nombre"]=$this->request->nombre;
		
		if($this->request->nombre){
			$where[]="c.nombre LIKE '%".$this->request->nombre."%'";
		}
		
		$pPaginador = "";
		
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}

		$ramos=array();

		$query="
			SELECT SQL_CALC_FOUND_ROWS
				*
			FROM Ramo c
			".$whereCondition."
			ORDER BY
				nombre ASC 
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
			
			$listaRamos=array();
			$this->fc->import("lib.Paginador");
			
			while($row=$result->fetch_assoc()){
				$listaRamos[]=$row;
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		$i=0;
		
		foreach($listaRamos as $ramo){
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);
			$ramos[$i]["nombre"] = $ramo["nombre"];
			$ramos[$i]["sigla"] = $ramo["sigla"];
			$ramos[$i]["idRamo"] = $ramo["idRamo"];
			$i++;				
		}
		$tablaPaginas=Paginador::crearHtmlAjax($paginaActual,$num_rows,"?do=ramos.busquedaRamos".$pPaginador,"divBusquedaRamos", $limite);

		$this->addVar("paginas",$tablaPaginas);
		$this->addVar("num_rows",$num_rows);
		
		$this->addLoop("ramos",$ramos);

		$this->processTemplate("ramos/busquedaRamos.html");
		
	}
}
?>
