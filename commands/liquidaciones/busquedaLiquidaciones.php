<?php
class busquedaLiquidaciones extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		
		$usuario=$this->getUsuario();
		if(!$this->request->pagina){
			$paginaActual=1;
		}else{
			$paginaActual=$this->request->pagina;
			$_SESSION["busquedaLiquidaciones"]["pagina"]=$this->request->pagina;
		}
		
		if(!$this->request->limite){
			$limite=10;
		}else{
			$limite=$this->request->limite;
			$_SESSION["busquedaLiquidaciones"]["limite"] = $this->request->limite;
		}

		$minimoDePagina=Paginador::getMinimo($paginaActual,$limite);
		
		$where=array();
				
		if($this->request->idCompania){
			$_SESSION["busquedaLiquidaciones"]["compania"] = $this->request->idCompania;
			$where[]="cia.idCompania = '".$this->request->idCompania."'";
		}
		
		
		if($this->request->factura){
			$_SESSION["busquedaLiquidaciones"]["contratante"]=$this->request->factura;
			$where[]="l.factura LIKE '%".$this->request->factura."%'";
		}
		
		$pPaginador = "";
		
		$where[]="cia.idCompania = l.idCompania";
		
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}

		$liquidaciones=array();
		$query="
				SELECT SQL_CALC_FOUND_ROWS
					DISTINCT *
				FROM Liquidacion l, Compania cia
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
			
			$listaLiquidaciones=array();
			$this->fc->import("lib.Paginador");
			
			while($row=$result->fetch_assoc()){
				$listaLiquidaciones[]=$row;
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		$i=0;
		
		foreach($listaLiquidaciones as $liquidacion){
			$liquidaciones[$i]["idLista"] = ++$i;
			$liquidaciones[$i]["idLiquidacion"] = $liquidacion["idLiquidacion"];
			$liquidaciones[$i]["factura"] = $liquidacion["factura"];
			$liquidaciones[$i]["compania"] = $liquidacion["nombre"];
			$liquidaciones[$i]["fechaPago"] = date("d/m/Y",strtotime($liquidacion["fechaFactura"]));
		}
		$tablaPaginas=Paginador::crearHtmlAjax($paginaActual,$num_rows,"?do=liquidaciones.busquedaLiquidaciones".$pPaginador,"divBusquedaLiquidaciones", $limite);

		$this->addVar("paginas",$tablaPaginas);
		$this->addVar("num_rows",$num_rows);
		
		$this->addLoop("liquidaciones",$liquidaciones);

		$this->processTemplate("liquidaciones/busquedaLiquidaciones.html");
		
	}
}
?>
