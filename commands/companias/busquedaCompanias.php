<?php
class busquedaCompanias extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		
		$usuario=$this->getUsuario();
		if(!$this->request->pagina){
			$paginaActual=1;
		}else{
			$paginaActual=$this->request->pagina;
			$_SESSION["busquedaCompanias"]["pagina"]=$this->request->pagina;
		}
		
		if(!$this->request->limite){
			$limite=10;
		}else{
			$limite=$this->request->limite;
			$_SESSION["busquedaCompanias"]["limite"] = $this->request->limite;
		}


		$minimoDePagina=Paginador::getMinimo($paginaActual,$limite);
		
		$where=array();
		

		$_SESSION["busquedaCompanias"]["nombre"]=$this->request->nombre;
		
		if($this->request->nombre){
			$where[]="c.nombre LIKE '%".$this->request->nombre."%'";
		}
		
		$pPaginador = "";
		
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}

		$companias=array();

		$query="
			SELECT SQL_CALC_FOUND_ROWS
				*
			FROM Compania c
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
			
			$listaCompanias=array();
			$this->fc->import("lib.Paginador");
			
			while($row=$result->fetch_assoc()){
				$listaCompanias[]=$row;
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		$i=0;
		
		foreach($listaCompanias as $compania){
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);
			$companias[$i]["nombre"] = $compania["nombre"];
			$companias[$i]["sigla"] = $compania["sigla"];
			$companias[$i]["idCompania"] = $compania["idCompania"];
			$i++;				
		}
		$tablaPaginas=Paginador::crearHtmlAjax($paginaActual,$num_rows,"?do=companias.busquedaCompanias".$pPaginador,"divBusquedaCompanias", $limite);

		$this->addVar("paginas",$tablaPaginas);
		$this->addVar("num_rows",$num_rows);
		
		$this->addLoop("companias",$companias);

		$this->processTemplate("companias/busquedaCompanias.html");
		
	}
}
?>
