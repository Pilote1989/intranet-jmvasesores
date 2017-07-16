<?php
class busqueda extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		$usuario=$this->getUsuario();
		if(!$this->request->pagina){
			$paginaActual=1;
		}else{
			$paginaActual=$this->request->pagina;
			$_SESSION["busquedaCompras"]["pagina"]=$this->request->pagina;
		}
		if(!$this->request->limite){
			$limite=10;
		}else{
			$limite=$this->request->limite;
			$_SESSION["busquedaCompras"]["limite"] = $this->request->limite;
		}
		$minimoDePagina=Paginador::getMinimo($paginaActual,$limite);
		$where=array();
		if($this->request->vendedor){
			$_SESSION["busquedaCompras"]["contratante"]=$this->request->vendedor;
			$where[]="c.nombre LIKE '%".$this->request->vendedor."%'";
		}else{
			$_SESSION["busquedaPolizas"]["contratante"]="";
		}
		$pPaginador = "";
		$where[]="Compra.idCliente = c.idCliente";
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}
		$compras=array();
		$query="
			SELECT SQL_CALC_FOUND_ROWS
				DISTINCT idCompra, numeroFactura, c.nombre as vendedor, moneda, concepto, tipo, subtotal, igv, otros, total, fecha
			FROM Compra, Cliente c
			".$whereCondition."
			ORDER BY
				fecha DESC
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
			$listaPolizas=array();
			$this->fc->import("lib.Paginador");
			while($row=$result->fetch_assoc()){
				$listaCompras[]=$row;
			}
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		$i=0;
		foreach($listaCompras as $compra){
			$compras[$i]["idCompra"] = $compra["idCompra"];	
			$compras[$i]["numero"] = $compra["numeroFactura"];	
			
			$compras[$i]["vendedor"] = $compra["vendedor"];		
			$compras[$i]["tipo"] = $compra["tipo"];	
			$compras[$i]["moneda"] = $compra["moneda"];
			$compras[$i]["subtotal"] = $compra["subtotal"];
			$compras[$i]["igv"] = $compra["igv"];
			$compras[$i]["otros"] = $compra["otros"];
			$compras[$i]["total"] = $compra["total"];
			$compras[$i]["fechaCompra"] = date("d/m/Y",strtotime($compra["fecha"]));
			$i++;				
		}
		$tablaPaginas=Paginador::crearHtmlAjax($paginaActual,$num_rows,"?do=compras.busqueda".$pPaginador,"divBusqueda", $limite);
		$this->addVar("paginas",$tablaPaginas);
		$this->addVar("num_rows",$num_rows);
		$this->addLoop("compras",$compras);
		$this->processTemplate("compras/busqueda.html");
		
	}
}
?>