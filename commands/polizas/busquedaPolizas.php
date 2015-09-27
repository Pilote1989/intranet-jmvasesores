<?php
class busquedaPolizas extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		
		$usuario=$this->getUsuario();
		if(!$this->request->pagina){
			$paginaActual=1;
		}else{
			$paginaActual=$this->request->pagina;
			$_SESSION["busquedaPolizas"]["pagina"]=$this->request->pagina;
		}
		
		if(!$this->request->limite){
			$limite=10;
		}else{
			$limite=$this->request->limite;
			$_SESSION["busquedaPolizas"]["limite"] = $this->request->limite;
		}


		$minimoDePagina=Paginador::getMinimo($paginaActual,$limite);
		
		$where=array();
		

		
		if($this->request->idRamo){
			$_SESSION["busquedaPolizas"]["ramo"]=$this->request->idRamo;
			$where[]="Poliza.idRamo = '".$this->request->idRamo."'";
		}
		
		
		if($this->request->contratante){
			$_SESSION["busquedaPolizas"]["contratante"]=$this->request->contratante;
			$where[]="c.nombre LIKE '%".$this->request->contratante."%'";
		}
		
		$pPaginador = "";
		
		$where[]="Poliza.idCliente = c.idCliente";
		$where[]="r.idRamo = Poliza.idRamo";
		$where[]="cia.idCompania = Poliza.idCompania";
		
		$where[]="Poliza.estado = '1'";
		//$where[]="Poliza.tipo = 'POL'";
		
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}

		$polizas=array();



		if($this->checkAccess("crearUsuario", true)){
			$query="
				SELECT SQL_CALC_FOUND_ROWS
					DISTINCT r.nombre as ramo, c.nombre as contratante, idPoliza, inicioVigencia, finVigencia, numeroPoliza, cia.nombre as cia, cia.sigla as scia, min(inicioVigencia) as minimoVig, max(finVigencia) as maxVig, count(*)
				FROM Poliza, Cliente c, Ramo r, Compania cia
				".$whereCondition."
				GROUP BY numeroPoliza
				ORDER BY
					maxVig DESC
				LIMIT ".$minimoDePagina.", ".$limite."
			";
		}else{
			$query="
				SELECT SQL_CALC_FOUND_ROWS
					DISTINCT r.nombre as ramo, c.nombre as contratante, idPoliza, inicioVigencia, finVigencia, numeroPoliza, cia.nombre as cia, cia.sigla as scia, min(inicioVigencia) as minimoVig, max(finVigencia) as maxVig, count(*)
				FROM Poliza, Cliente c, Ramo r, Compania cia
				".$whereCondition." AND idPersona = " . $usuario->getId() . "
				GROUP BY numeroPoliza
				ORDER BY
					maxVig DESC
				LIMIT ".$minimoDePagina.", ".$limite."
			";
		}


/*

		$query="
			SELECT SQL_CALC_FOUND_ROWS
				DISTINCT r.nombre as ramo, c.nombre as contratante, idPoliza, inicioVigencia, finVigencia, numeroPoliza
			FROM Poliza, Cliente c, Ramo r
			".$whereCondition." AND idPersona = " . $usuario->getId() . "
			ORDER BY
				finVigencia DESC
			LIMIT ".$minimoDePagina.", ".$limite."
		";*/
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
			
			$listaPolizas=array();
			$this->fc->import("lib.Paginador");
			
			while($row=$result->fetch_assoc()){
				$listaPolizas[]=$row;
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		$i=0;
		
		foreach($listaPolizas as $poliza){
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);
			$polizas[$i]["contratante"] = $poliza["contratante"];
			$polizas[$i]["ramo"] = $poliza["ramo"];
			$polizas[$i]["numeroPoliza"] = $poliza["numeroPoliza"];			
			$polizas[$i]["idPoliza"] = $poliza["idPoliza"];		
			$polizas[$i]["cia"] = $poliza["cia"];		
			$polizas[$i]["scia"] = $poliza["scia"];
			$polizas[$i]["fechaFinVigencia"] = date("d/m/Y",strtotime($poliza["maxVig"]));
			$polizas[$i]["fechaInicioVigencia"] = date("d/m/Y",strtotime($poliza["minimoVig"]));
			$i++;				
		}
		$tablaPaginas=Paginador::crearHtmlAjax($paginaActual,$num_rows,"?do=polizas.busquedaPolizas".$pPaginador,"divBusquedaPolizas", $limite);

		$this->addVar("paginas",$tablaPaginas);
		$this->addVar("num_rows",$num_rows);
		
		$this->addLoop("polizas",$polizas);

		$this->processTemplate("polizas/busquedaPolizas.html");
		
	}
}
?>
