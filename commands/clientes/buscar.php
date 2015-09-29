<?php
class buscar extends sessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		
		$usuario=$this->getUsuario();
		
		$where=array();
		
		if($this->request->nombre){
			$where[]="c.nombre LIKE '%".$this->request->nombre."%'";
		}
				
		if($this->request->tipoDoc){
			$where[]="c.tipoDoc = '".$this->request->tipoDoc."'";
		}
				
		if($this->request->doc){
			$where[]="c.doc LIKE '%".$this->request->doc."%'";
		}
		
		
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}
		
		//echo $this->request->idPoliza;
		$clienteEnPolizas = Fabrica::getAllFromDB("ClienteEnPoliza",array("idPoliza = '". $this->request->idPoliza."'"));
		
		$cli = array();
		$j=0;
		foreach($clienteEnPolizas as $clienteEnPoliza){
			$cli[$j++] = $clienteEnPoliza->getIdCliente();
		}
		//print_r($cli);
		
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
			$clientes[$i]["doc"] = $cliente["tipoDoc"] . " - " . $cliente["doc"];
			if(in_array($cliente["idCliente"],$cli)){
				$clientes[$i]["estado"] = '<button class="btn btn-minier btn-inverse disabled">En Poliza</button>';
			}else{
				$clientes[$i]["estado"] = '<button class="btn btn-minier btn-success agregaAsegurado" x-pol="'.$this->request->idPoliza.'" x-link="'.$cliente["idCliente"].'">Agregar<i class="icon-arrow-right icon-on-right"></i></button>';
			}
						
			$i++;				
		}
		
		$this->addLoop("clientes",$clientes);
		$this->addVar("idPoliza",$this->request->idPoliza);

		$this->processTemplate("clientes/buscar.html");
		
	}}
?>