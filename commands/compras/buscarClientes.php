<?php
class buscarClientes extends sessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		$usuario=$this->getUsuario();
		$where=array();
		if($this->request->nombre){
			$where[]="c.nombre LIKE '%".$this->request->nombre."%'";
		}
		$where[]="c.tipoDoc = 'RUC'";
		
		if($this->request->ruc){
			$where[]="c.doc LIKE '%".$this->request->ruc."%'";
		}
		
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
			$clientes[$i]["doc"] = $cliente["doc"];
			$i++;				
		}
		
		$this->addVar("ruc",$this->request->ruc);
		$this->addVar("nombre",$this->request->nombre);
		
		$this->addLoop("clientes",$clientes);
		$this->processTemplate("compras/buscarClientes.html");
		
	}}
?>