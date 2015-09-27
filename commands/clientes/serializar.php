<?php
class serializar extends SessionCommand{
	function execute(){
		$clientes=array();
		$query="
			SELECT SQL_CALC_FOUND_ROWS
				*
			FROM Cliente c
			WHERE nombre LIKE '%" . $this->request->nombre . "%'
			ORDER BY
				nombre ASC 
		";		
		$j = 0;
		//echo $query;
		$query=utf8_decode($query);		
		$link=&$this->fc->getLink();	
		$listaClientes = array();			
		$clientes = array();			
		if($result=$link->query($query)){
			while($row=$result->fetch_assoc()){
				$listaClientes[]=$row;
				$j++;
			}			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		//echo $query;
		$i=0;
		
		foreach($listaClientes as $cliente){
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);
			$clientes[$i]["idCliente"] = $cliente["idCliente"];
			$clientes[$i]["nombre"] = $cliente["nombre"];
			$i++;			
		}
		if($j > 0){
			echo json_encode($clientes);		
		}else{
			echo json_encode("");		
		}
	}
}
?>
