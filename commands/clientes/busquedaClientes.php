<?php
class busquedaClientes extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		
		$usuario=$this->getUsuario();
		if(!$this->request->pagina){
			$paginaActual=1;
		}else{
			$paginaActual=$this->request->pagina;
			$_SESSION["busquedaClientes"]["pagina"]=$this->request->pagina;
		}
		
		if(!$this->request->limite){
			$limite=10;
		}else{
			$limite=$this->request->limite;
			$_SESSION["busquedaClientes"]["limite"] = $this->request->limite;
		}


		$minimoDePagina=Paginador::getMinimo($paginaActual,$limite);
		
		$where=array();
		

		$_SESSION["busquedaClientes"]["nombre"]=$this->request->nombre;
		
		if($this->request->nombre){
			$where[]="c.nombre LIKE '%".$this->request->nombre."%'";
		}
		
		$_SESSION["busquedaClientes"]["doc"]=$this->request->doc;
		
		if($this->request->doc){
			$where[]="c.doc LIKE '%".$this->request->doc."%'";
		}
		
		$where[]="c.estado = '1'";
		
		$pPaginador = "";
		
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}

		$clientes=array();

		$query="
			SELECT SQL_CALC_FOUND_ROWS
				*
			FROM Cliente c
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
			
			$listaClientes=array();
			$this->fc->import("lib.Paginador");
			
			while($row=$result->fetch_assoc()){
				$listaClientes[]=$row;
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		$i=0;
		
		foreach($listaClientes as $cliente){
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);
			$clientes[$i]["nombre"] = $cliente["nombre"];
			$clientes[$i]["direccion"] = $cliente["direccion"];
			$clientes[$i]["idCliente"] = $cliente["idCliente"];
			$clientes[$i]["doc"] = $cliente["tipoDoc"] . " - " . $cliente["doc"];
			$i++;				
		}
		$tablaPaginas=Paginador::crearHtmlAjax($paginaActual,$num_rows,"?do=clientes.busquedaClientes".$pPaginador,"divBusquedaClientes", $limite);

		$this->addVar("paginas",$tablaPaginas);
		$this->addVar("num_rows",$num_rows);
		
		$this->addLoop("clientes",$clientes);

		$this->processTemplate("clientes/busquedaClientes.html");
		
	}
}
?>
